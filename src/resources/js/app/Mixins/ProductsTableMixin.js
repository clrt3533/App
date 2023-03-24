import * as actions from '../Config/ApiUrl';
import AppFunction from '../../core/helpers/app/AppFunction';
import {PRODUCTS_LIST} from "../Config/BillarApiUrl";
import {numberWithCurrencySymbol} from "../Helpers/Helpers";
import {urlGenerator} from "../Helpers/AxiosHelper";

export default {
    data() {
        return {
            tableId: 'products-table',
            dataEditUrl: '',
            deleteUrl: '',
            rowData: {},
            options: {
                url: PRODUCTS_LIST,
                name: this.$t('products_table'),
                columns: [
                    {
                        title: this.$t('image'),
                        type: 'component',
                        key: 'file',
                        componentName: 'app-media-column'
                    },
                    {
                        title: this.$t('name'),
                        type: 'text',
                        key: 'name',
                    },
                    {
                        title: this.$t('code'),
                        type: 'text',
                        key: 'code',
                    },
                    {
                        title: this.$t('category'),
                        type: 'object',
                        key: 'category',
                        modifier: (value => value ? value.name : '')
                    },
                    {
                        title: this.$t('price'),
                        type: 'object',
                        key: 'unit_price',
                        modifier: (price => numberWithCurrencySymbol(price))
                    },
                    {
                        title: this.$t('actions'),
                        type: 'action'
                    }
                ],
                filters: [
                    {
                        'title': this.$t('created'),
                        'type': 'range-picker',
                        'key': 'date',
                        'option': ['today', 'thisMonth', 'last7Days', 'nextYear']
                    },
                    {
                        title: this.$t("categories"),
                        type: "search-and-select-filter",
                        key: "categories",
                        settings: {
                            url: urlGenerator('all-category'),
                            modifire: (item) => {
                                return {id: item.id, value: item.name}
                            },
                            per_page: 10,
                            loader: 'app-pre-loader',
                            multiple: true,
                        },
                    }
                ],
                actions: [
                    {
                        title: this.$t('edit'),
                        type: 'edit',
                        modifier: () => this.$can("update_products"),
                    },
                    {
                        title: this.$t('delete'),
                        type: 'delete',
                        modifier: () => this.$can("delete_products"),
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
            isCategoryAddEditModalActive: false,
        }
    },
    methods: {
        getListAction(rowData, actionObj) {
            this.selectUrl = `${PRODUCTS_LIST}/${rowData.id}`;
            if (actionObj.type === 'edit') {
                this.isModalActive = true;
            } else if (actionObj.type === 'delete') {
                this.confirmationModalActive = true;
                // if (rowData.invoice_details.length) {
                //     this.$toastr.e(this.$t('you_have_a_pending_invoice_for_this_product'));
                // } else {
                //     this.confirmationModalActive = true;
                // }
            }
        },

        openCategoryAddEditModal() {
            this.isCategoryAddEditModalActive = true;
            setTimeout(() => {
                $('#product-add-edit-modal').css({
                    opacity: '0.5',
                });
            });
        },
        closeCategoryAddEditModal() {
            this.isCategoryAddEditModalActive = false;
            //this.$store.dispatch("getCategory");
            setTimeout(() => {
                $('#product-add-edit-modal').css({
                    opacity: '1',
                });
            });
            $("#category-add-edit-modal").modal("hide");
        },
        getTableMediaAction() {
            this.$hub.$on('getTableMediaAction', (data) => {
                this.viewInvoiceDetails();
            })
        },
        viewCategoryPage() {
            location.replace(AppFunction.getAppUrl('/categories/list/view'));
        }
    },
    mounted() {
        this.getTableMediaAction();
    }
}