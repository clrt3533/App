import AppFunction from "../../core/helpers/app/AppFunction";
import { formatDateToLocal, numberWithCurrencySymbol } from "../Helpers/Helpers";
import { FormMixin } from "../../core/mixins/form/FormMixin";
import { DeleteMixins } from "./billar/DeleteMixins";
import { INVOICES, STOP_RECURRING_INVOICES } from "../Config/BillarApiUrl";
import { mapGetters } from "vuex";
import { axiosGet, axiosPatch, urlGenerator } from "../Helpers/AxiosHelper";
import { status } from "./FilterMixin";

export default {
	mixins: [FormMixin, DeleteMixins, status],
	data() {
		return {
			AppFunction,
			tableId: 'invoices-table',
			productList: [],
			isInvoicePaymentModalActive: false,
			clientModalActive: false,
			options: {
				url: INVOICES,
				name: this.$t('invoices_table'),
				filters: [
					{
						'title': this.$t('created'),
						'type': 'range-picker',
						'key': 'date',
						'option': ['today', 'thisMonth', 'last7Days', 'nextYear']
					},
					{
						'title': this.$t('issue_date'),
						'type': 'range-picker',
						'key': 'date',
						'option': ['today', 'thisMonth', 'last7Days', 'nextYear']
					},
					// {
					// 	title: this.$t("status"),
					// 	type: "checkbox",
					// 	key: "status",
					// 	option: [],
					// 	permission: this.$can('show_all_data') ? true : false
					// },
					// {
					// 	title: this.$t("clients"),
					// 	type: "search-and-select-filter",
					// 	key: "clients",
					// 	settings: {
					// 		url: urlGenerator('client-users'),
					// 		modifire: (item) => {
					// 			return { id: item.id, value: item.full_name }
					// 		},
					// 		per_page: 10,
					// 		loader: 'app-pre-loader',
					// 		multiple: true,
					// 	},
					// 	permission: this.$can('show_all_data') ? true : false
					// },
					{
						title: this.$t("amount"),
						type: "range-filter",
						key: "amount_range",
						minTitle: this.$t('minimum_amount'),
						maxTitle: this.$t('maximum_amount'),
						maxRange: 100,
						minRange: 0,
						permission: this.$can('show_all_data') ? true : false
					}, {
						title: this.$t("paid"),
						type: "range-filter",
						key: "paid_range",
						minTitle: this.$t('minimum_paid'),
						maxTitle: this.$t('maximum_paid'),
						maxRange: 100,
						minRange: 0,
						permission: this.$can('show_all_data') ? true : false
					}, {
						title: this.$t("due"),
						type: "range-filter",
						key: "due_range",
						minTitle: this.$t('minimum_due'),
						maxTitle: this.$t('maximum_due'),
						maxRange: 100,
						minRange: 0,
						permission: this.$can('show_all_data') ? true : false
					}
				],
				columns: [
					{
						title: this.$t('invoice_number'),
						type: 'custom-html',
						key: 'invoice_number',
						modifier: (value, row) => {
							return this.$can('show_all_data') ?
								`<a onclick="window.open(this.href,'_blank');return false;" href="${urlGenerator(`invoices/${row.id}/details`)}"> ${+row.is_from_estimate == 1 ? "EST-" : ""}${row.invoice_number}</a>`
								:
								`<p> ${+row.is_from_estimate == 1 ? "EST-" : ""}${row.invoice_number}</p>`
								;
						}
					},
					// {
					// 	title: this.$t('status'),
					// 	type: 'custom-html',
					// 	key: 'status',
					// 	modifier: (value) => {
					// 		return `<span class="badge badge-${value.class} badge-pill mr-2">${value.translated_name}</span>`
					// 	}
					// },

					{
						title: this.$t('client'),
						type: 'object',
						key: 'client_name',
						isVisible: !!this.$can('show_all_data'),
						modifier: (client => client)
					},
					{
						title: this.$t('issue_date'),
						type: 'object',
						key: 'date',
						modifier: (date => formatDateToLocal(date))
					}, /* {
						title: this.$t('due_date'),
						type: 'object',
						key: 'due_date',
						modifier: (due_date => formatDateToLocal(due_date))
					}, */
					{
						title: this.$t('total'),
						type: 'object',
						key: 'total',
						modifier: (total => numberWithCurrencySymbol(total))
					},
					{
						title: this.$t('paid'),
						type: 'object',
						key: 'received_amount',
						modifier: (received_amount => numberWithCurrencySymbol(received_amount))
					},
					{
						title: this.$t('amount_due'),
						type: 'object',
						key: 'due_amount',
						modifier: (due_amount => numberWithCurrencySymbol(due_amount))
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
						modifier: () => this.$can("invoice_resend"),
					},
					{
						title: this.$t('action_invoice_download'),
						type: 'download',
						modifier: () => this.$can("invoice_download"),
					},
					{
						title: this.$t('view'),
						type: 'view',
						url: AppFunction.getAppUrl('/invoice-details'),
						modifier: () => this.$can('show_all_data') ? true : false
					},
					{
						title: this.$t('add_payment'),
						type: 'add_payment',
						alias: "status",
						modifier: (row) => {
							if (this.$can('is_app_admin')) {
								return true;
							} else {
								if (this.$can('invoice_checkout')) {
									return row?.status.name != 'status_paid' ? true : false;
								}

							}
						},
					},
					{
						title: this.$t('stop_recurring'),
						type: 'stop_recurring',
						modifier: (row) => {
							return !!(Number(row.recurring) === 1 && this.$can('is_app_admin'));
						},
					}, {
						title: this.$t('edit'),
						type: 'edit',
						modifier: () => this.$can("update_invoices"),
					},
					{
						title: this.$t('delete'),
						type: 'delete',
						modifier: () => this.$can("delete_invoices"),
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
			isInvoiceAddEditModalActive: false,
			isPaymentAddEditModalActive: false,
			invoiceId: null,
			invoiceSummation: {
				total_amount: 0,
				paid_amount: 0,
				due_amount: 0
			},
			numberWithCurrencySymbol
		}
	},
	computed: {
		...mapGetters({
			recurringCycleList: "getRecurringCycle",
		}),

	},
	methods: {
		getListAction(rowData, actionObj) {
			if (actionObj.type === 'resend') {
				this.resendInvoice(rowData);
			} else if (actionObj.type === 'download') {
				window.open(AppFunction.getAppUrl(`invoice-download/${rowData.id}`))
			} else if (actionObj.type === 'view') {
				this.selectUrl = AppFunction.getAppUrl(`/invoices/${rowData.id}/details`);
				window.location = this.selectUrl;
			} else if (actionObj.type === 'add_payment') {
				console.log(this.$can('is_app_admin'))
				if (this.$can('is_app_admin') || this.$can('create_invoices')) {
					this.invoiceId = rowData.id;
					this.isPaymentAddEditModalActive = true;
				} else {
					this.selectUrl = `${INVOICES}/${rowData.id}`;
					this.isInvoicePaymentModalActive = true;
				}
			} else if (actionObj.type === 'edit') {
				window.location.replace(AppFunction.getAppUrl(`/invoice/${rowData.id}/edit`));
			} else if (actionObj.type === 'delete') {
				this.selectUrl = `${INVOICES}/${rowData.id}`;
				this.confirmationModalActive = true;
			} else if (actionObj.type === 'stop_recurring') {
				this.stopRecurringInvoice(rowData);
			}
		},
		stopRecurringInvoice(rowData) {
			axiosPatch(`${STOP_RECURRING_INVOICES}/${rowData.id}`).then((response) => {
				this.$toastr.s(response.data.message);
				this.$hub.$emit('reload-' + this.tableId);

			})
		},
		resendInvoice(rowData) {
			axiosGet(`invoice-resend/${rowData.id}`).then((response) => {
				this.$toastr.s(response.data.message);
				this.$hub.$emit('reload-' + this.tableId);

			}).catch((error) => {
				console.log(error)
			})
		},
		downloadInvoice() {
			this.axiosGet(`invoice-generate`).then(({ data }) => {

			})
		},
		closeInvoiceModal() {
			this.selectUrl = "";
			this.invoiceRangeFilter();
			this.$store.dispatch("getInvoice");
			this.isInvoiceAddEditModalActive = false;
		},
		closePaymentAddEditModal() {
			this.isPaymentAddEditModalActive = false;
		},
		closeInvoicePaymentModal() {
			this.selectUrl = "";
			this.isInvoicePaymentModalActive = false;
			$("#invoice-payment-modal").modal("hide");
		},
		viewInvoiceDetails() {
			location.replace(AppFunction.getAppUrl('/invoice-details'));
		},
		getTableMediaAction() {
			this.$hub.$on('getTableMediaAction', (data) => {
				this.viewInvoiceDetails();
			})
		},
		getAllProduct() {
			this.axiosGet(`all-products`).then(({ data }) => {
				this.productList = data
			})
		},
		deleteProductFromInvoice(data) {
			this.deletedInvoiceProductContext = data;
			setTimeout(() => {
				$('#invoice-add-edit-modal').css({ opacity: '.5' });
				this.invoiceProductDeleteModal = true;
			});
		},

		invoiceRangeFilter() {
			this.axiosGet(`invoice-range-filter`).then((response) => {
				let amountValueFilter = this.options.filters.find(item => item.key === 'amount_range');
				let paidValueFilter = this.options.filters.find(item => item.key === 'paid_range');
				let dueValueFilter = this.options.filters.find(item => item.key === 'due_range');

				amountValueFilter.maxRange = response.data.max_invoice_amount;
				amountValueFilter.minRange =
					response.data.min_invoice_amount < response.data.max_invoice_amount ? response.data.min_invoice_amount : 0;

				paidValueFilter.maxRange = response.data.max_invoice_paid;
				paidValueFilter.minRange =
					response.data.min_invoice_paid < response.data.max_invoice_paid ? response.data.min_invoice_paid : 0;

				dueValueFilter.maxRange = response.data.max_invoice_due;
				dueValueFilter.minRange =
					response.data.min_invoice_due < response.data.max_invoice_due ? response.data.min_invoice_due : 0;
			});
		},
		getInvoiceSummation() {
			axiosGet('invoice-summation').then((response) => {
				this.invoiceSummation.total_amount = response.data.total_amount ?? 0;
				this.invoiceSummation.paid_amount = response.data.paid_amount ?? 0;
				this.invoiceSummation.due_amount = response.data.due_amount ?? 0;
			})
		},
		openClientModal() {
			this.clientModalActive = true;
			setTimeout(() => {
				$('#invoice-add-edit-modal').css({
					opacity: '0.5',
				});
			});
		},
		closeClientModal() {
			this.clientModalActive = false;
			setTimeout(() => {
				$('#invoice-add-edit-modal').css({
					opacity: '1',
				});
			});
			$("#client-add-edit-modal").modal("hide");
		},
	},
	mounted() {
		this.getTableMediaAction();
		this.getAllProduct();
		this.getInvoiceSummation();
		this.$store.dispatch("getRecurringCycle");
		this.$store.dispatch("getPaymentMethod");
		if (this.$can('show_all_data')) {
			this.invoiceRangeFilter();
		}
	}
}