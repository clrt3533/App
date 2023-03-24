import AppFunction from "../../core/helpers/app/AppFunction";
import {formatDateToLocal, numberWithCurrencySymbol} from "../Helpers/Helpers";
import {FormMixin} from "../../core/mixins/form/FormMixin";
import {DeleteMixins} from "./billar/DeleteMixins";
import {ESTIMATE, INVOICES, STOP_RECURRING_INVOICES} from "../Config/BillarApiUrl";
import {mapGetters} from "vuex";
import {axiosGet, axiosPatch, urlGenerator} from "../Helpers/AxiosHelper";
import {status} from "./FilterMixin";

export default {
    mixins: [FormMixin, DeleteMixins],
    data() {
        return {
            AppFunction,
            tableId: 'estimate-table',
            productList: [],
            isInvoicePaymentModalActive: false,
            clientModalActive: false,
            options: {
                url: ESTIMATE,
                name: this.$t('estimate_table'),
                filters: [
                    {
                        'title': this.$t('created'),
                        'type': 'range-picker',
                        'key': 'date',
                        'option': ['today', 'thisMonth', 'last7Days', 'nextYear']
                    },
                    // {
                    //     title: this.$t("status"),
                    //     type: "checkbox",
                    //     key: "status",
                    //     option: [],
                    //     permission: this.$can('show_all_data') ? true : false
                    // },
                    {
                        title: this.$t('status'),
                        type: "search-and-select-filter",
                        key: "status",
                        option: [],
                        // listValueField: 'translated_name',
                        settings: {
                            url: urlGenerator('get-status/estimate'),
                            modifire: (item) => {
                                return {id: item.id, value: item.translated_name}
                            },
                            per_page: 10,
                            loader: 'app-pre-loader',
                            multiple: true,
                        },
                    },
                    {
                        title: this.$t("clients"),
                        type: "search-and-select-filter",
                        key: "clients",
                        settings: {
                            url: urlGenerator('client-users'),
                            modifire: (item) => {
                                return {id: item.id, value: item.full_name}
                            },
                            per_page: 10,
                            loader: 'app-pre-loader',
                            multiple: true,
                        },
                        permission: this.$can('show_all_data') ? true : false
                    }
                ],
                columns: [
                    {
                        title: this.$t('estimate_number'),
                        type: 'custom-html',
                        key: 'estimate_number',
                        modifier: (value, row) => {
                            return `<p> EST-${row.estimate_number}</p>`;
                        }
                    },
                    {
                        title: this.$t('status'),
                        type: 'custom-html',
                        key: 'status',
                        modifier: (value) => {
                            return `<span class="badge badge-${value.class} badge-pill mr-2">${value.translated_name}</span>`
                        }
                    },
                    {
                        title: this.$t('client'),
                        type: 'object',
                        key: 'client',
                        isVisible: !!this.$can('show_all_data'),
                        modifier: (client => client ? client.full_name : '')
                    },
                    {
                        title: this.$t('issue_date'),
                        type: 'object',
                        key: 'date',
                        modifier: (date => formatDateToLocal(date))
                    },
                    {
                        title: this.$t('total'),
                        type: 'object',
                        key: 'total',
                        modifier: (total => numberWithCurrencySymbol(total))
                    },
                    {
                        title: this.$t('actions'),
                        type: 'action'
                    }
                ],
                actions: [
                    {
                        title: this.$t('resend'),
                        type: 'resend',
                        modifier: () => this.$can("estimate_resend"),
                    },
                    {
                        title: this.$t('clone_as_invoice'),
                        type: 'clone_as_invoice',
                        modifier: (row) => {
                            if (this.$can("clone_invoice")) {
                                if (+row.is_clone === 1) {
                                    return false
                                } else {
                                    return true
                                }
                            } else {
                                return false
                            }
                        },
                    },
                    {
                        title: this.$t('download_estimate'),
                        type: 'download',
                        modifier: () => this.$can('download_estimate')
                    },
                    {
                        title: this.$t('edit'),
                        type: 'edit',
                        modifier: () => this.$can("update_estimates"),
                    },
                    {
                        title: this.$t('delete'),
                        type: 'delete',
                        modifier: () => this.$can("delete_estimates"),
                    }
                ],
                rowLimit: 10,
                orderBy: 'desc',
                responsive: true,
                showHeader: true,
                showFilter: true,
                showSearch: true,
                showAction: true,
                tableShadow: true,
                actionType: 'dropdown',
                datatableWrapper: true,
                paginationType: 'pagination'
            },
            isEstimateAddEditModalActive: false,
            estimateId: null,
            invoiceSummation: {
                total_amount: 0,
                paid_amount: 0,
                due_amount: 0
            },
            numberWithCurrencySymbol
        }
    },
    methods: {
        getListAction(rowData, actionObj) {
            if (actionObj.type === 'resend') {
                this.resendEstimate(rowData);
            } else if (actionObj.type === 'download') {
                window.open(AppFunction.getAppUrl(`estimate-download/${rowData.id}`))
            } else if (actionObj.type === 'clone_as_invoice') {
                this.isShowCloneModal = true
                this.estimateId = rowData.id
            } else if (actionObj.type === 'edit') {
                window.location.replace(AppFunction.getAppUrl(`/estimates/${rowData.id}/edit/view`));
            } else if (actionObj.type === 'delete') {
                this.selectUrl = `${ESTIMATE}/${rowData.id}`;
                this.confirmationModalActive = true;
            }
        },
        cloneInvoice(rowData) {
            axiosGet(`clone-as-invoice/${rowData.id}`).then((response) => {
                this.$toastr.s(response.data.message);
                this.$hub.$emit('reload-' + this.tableId);
            })
        },
        resendEstimate(rowData) {
            axiosGet(`estimate-resend/${rowData.id}`).then((response) => {
                this.$toastr.s(response.data.message);
                this.$hub.$emit('reload-' + this.tableId);

            }).catch((error) => {
                console.log(error)
            })
        },
        closeEstimateModal() {
            this.selectUrl = "";
            this.estimateRangeFilter();
            this.$store.dispatch("getInvoice");
            this.isInvoiceAddEditModalActive = false;
        },
        viewInvoiceDetails() {
            location.replace(AppFunction.getAppUrl('/estimate-details'));
        },
        getTableMediaAction() {
            this.$hub.$on('getTableMediaAction', (data) => {
                this.viewInvoiceDetails();
            })
        },
        getAllProduct() {
            this.axiosGet(`all-products`).then(({data}) => {
                this.productList = data
            })
        },
        deleteProductFromEstimate(data) {
            this.deletedInvoiceProductContext = data;
            setTimeout(() => {
                $('#invoice-add-edit-modal').css({opacity: '.5'});
                this.invoiceProductDeleteModal = true;
            });
        },

        openClientModal() {
            this.clientModalActive = true;
            setTimeout(() => {
                $('#estimate-add-edit-modal').css({
                    opacity: '0.5',
                });
            });
        },
        closeClientModal() {
            this.clientModalActive = false;
            setTimeout(() => {
                $('#estimate-add-edit-modal').css({
                    opacity: '1',
                });
            });
        },
    },
    mounted() {
        this.getTableMediaAction();
        this.getAllProduct();
    }
}