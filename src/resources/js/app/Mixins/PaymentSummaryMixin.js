import {PAYMENT_SUMMARY_REPORT} from "../Config/ApiUrl";
import {formatDateToLocal, numberWithCurrencySymbol} from "../Helpers/Helpers";
import {urlGenerator} from "../Helpers/AxiosHelper";

export default {
    data() {
        return {
            formatDateToLocal,
            tableId: 'payment-summary-table',
            selectedUrl: '',
            rowData: {},
            options: {
                url: PAYMENT_SUMMARY_REPORT,
                name: this.$t('payment_summary_table'),
                filters: [
                    {
                        'title': this.$t('date'),
                        'type': 'range-picker',
                        'key': 'date',
                        'option': ['today', 'thisMonth', 'last7Days', 'nextYear']
                    },
                    {
                        title: this.$t("payment_methods"),
                        type: "checkbox",
                        key: "payment_methods",
                        option: [],
                        permission: this.$can('show_all_data') ? true : false
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
                    },
                ],
                columns: [
                    {
                        title: this.$t('date'),
                        type: 'custom-html',
                        key: 'received_on',
                        modifier: (value) => {
                            return value ? formatDateToLocal(value) : '-'
                        }
                    },
                    {
                        title: this.$t('invoice_number'),
                        type: 'object',
                        key: 'invoice',
                        modifier: (value) => {
                            return value ? value.invoice_number : '-'
                        }
                    },
                    {
                        title: this.$t('payment_method'),
                        type: 'object',
                        key: 'payment_method',
                        modifier: (value) => {
                            return value ? value.name : '-'
                        }
                    },
                    {
                        title: this.$t('client'),
                        type: 'object',
                        key: 'invoice',
                        isVisible: !!this.$can('show_all_data'),
                        modifier: (value) => {
                            return value.client ? value.client.full_name : '-'
                        }
                    },
                    {
                        title: this.$t('amount'),
                        type: 'object',
                        key: 'amount',
                        modifier: (amount => numberWithCurrencySymbol(amount))
                    }
                ],
                actions: [],
                rowLimit: 10,
                orderBy: 'desc',
                responsive: true,
                showHeader: true,
                showFilter: true,
                showSearch: true,
                showAction: false,
                tableShadow: true,
                actionType: 'dropdown',
                datatableWrapper: true,
                paginationType: 'pagination'
            },
            isCategoryAddEditModalActive: false,
            isCategoryDeleteModalActive: false,
        }
    },
    methods: {}
}