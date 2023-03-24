import AppFunction from "../../core/helpers/app/AppFunction";
import {formatDateToLocal, numberWithCurrencySymbol} from "../Helpers/Helpers";
import {FormMixin} from "../../core/mixins/form/FormMixin";
import {DeleteMixins} from "./billar/DeleteMixins";
import {INVOICES} from "../Config/BillarApiUrl";
import {mapGetters} from "vuex";
import {axiosGet, urlGenerator} from "../Helpers/AxiosHelper";
import {status} from "./FilterMixin";

export default {
	mixins: [FormMixin, DeleteMixins, status], data() {
		return {
			tableId: 'invoices-table',
			productList: [],
			isInvoicePaymentModalActive: false,
			invoiceProductDeleteModal: false,
			deletedInvoiceProductContext: null,
			clientModalActive: false,
			options: {
				url: `${INVOICES}?invoice_type=recurring`,
				name: this.$t('invoices_table'),
				filters: [{
					'title': this.$t('created'),
					'type': 'range-picker',
					'key': 'date',
					'option': ['today', 'thisMonth', 'last7Days', 'nextYear']
				}, {
					'title': this.$t('due_date'),
					'type': 'range-picker',
					'key': 'due',
					'option': ['today', 'thisMonth', 'last7Days', 'nextYear']
				}, {
					title: this.$t("status"),
					type: "checkbox",
					key: "status",
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
				columns: [{
					title: this.$t('invoice_number'),
					type: 'link',
					key: 'invoice_number',
					modifier: (value, row) => {
						return this.$can('show_all_data') ?
							`<a onclick="window.open(this.href,'_blank');return false;" href="${urlGenerator(`invoices/${row.id}/details`)}"> ${row.invoice_number}</a>`
							:
							`<p> ${row.invoice_number}</p>`
							;
					}
				}, {
					title: this.$t('status'),
					type: 'custom-html',
					key: 'status',
					modifier: (value) => {
						return `<span class="badge badge-${value.class} badge-pill mr-2">${value.translated_name}</span>`
					}
				}, {
					title: this.$t('recurring_cycle'),
					type: 'object',
					key: 'recurring_cycle',
					modifier: (recurring_cycle => recurring_cycle?.name)
				}, {
					title: this.$t('client'),
					type: 'object',
					key: 'client',
					isVisible: !!this.$can('show_all_data'),
					modifier: (client => client ? client.full_name : '')
				}, {
					title: this.$t('issue_date'),
					type: 'object',
					key: 'date',
					modifier: (date => formatDateToLocal(date))
				}, {
					title: this.$t('due_date'),
					type: 'object',
					key: 'due_date',
					modifier: (due_date => formatDateToLocal(due_date))
				}, {
					title: this.$t('total'),
					type: 'object',
					key: 'total',
					modifier: (total => numberWithCurrencySymbol(total))
				}, {
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
					}],
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
						title: this.$t('add_payment'), type: 'add_payment', alias: "status", modifier: (row) => {
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
						title: this.$t('delete'),
						type: 'delete',
						modifier: () => this.$can("delete_invoices"),
					}],
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
				total_amount: 0, paid_amount: 0, due_amount: 0
			},
			numberWithCurrencySymbol
		}
	}, computed: {
		...mapGetters({
			recurringCycleList: "getRecurringCycle",
			taxList: "getTax",
		}),

	}, methods: {
		getListAction(rowData, actionObj) {
			if (actionObj.type === 'resend') {
				this.resendInvoice(rowData);
			} else if (actionObj.type === 'download') {
				window.open(AppFunction.getAppUrl(`invoice-download/${rowData.id}`))
			} else if (actionObj.type === 'view') {
				this.selectUrl = AppFunction.getAppUrl(`/invoices/${rowData.id}/details`);
				window.location = this.selectUrl;
			} else if (actionObj.type === 'add_payment') {
				if (this.$can('is_app_admin')) {
					this.invoiceId = rowData.id;
					this.isPaymentAddEditModalActive = true;
				} else {
					this.selectUrl = `${INVOICES}/${rowData.id}`;
					this.isInvoicePaymentModalActive = true;
				}
			} else if (actionObj.type === 'delete') {
				this.selectUrl = `${INVOICES}/${rowData.id}`;
				this.confirmationModalActive = true;
			}
		},
		resendInvoice(rowData) {
			axiosGet(`invoice-resend/${rowData.id}`).then((response) => {
				this.$toastr.s(response.data.message);
			}).catch((error) => {
				console.log(error)
			})
		},
		downloadInvoice() {
			this.axiosGet(`invoice-generate`).then(({data}) => {
			})
		},
		closeInvoiceModal() {
			this.selectUrl = "";
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
		deleteProductFromInvoice(data) {
			this.deletedInvoiceProductContext = data;
			setTimeout(() => {
				$('#invoice-add-edit-modal').css({opacity: '.5'});
				this.invoiceProductDeleteModal = true;
			});
		},
		closeInvoiceDeleteModal() {
			$(`#invoice-product-delete-confirm-modal`).modal('hide');
			$('#invoice-add-edit-modal').css({opacity: '1'});
			this.invoiceProductDeleteModal = false;
		},
		confirmDeleteInvoiceProduct() {
			this.axiosPost(this.deletedInvoiceProductContext['payload'])
			.then((response) => {
				this.deletedInvoiceProductContext['callBack']();
				this.$toastr.s(response.data.message);
				this.deletedInvoiceProductContext = null;
				setTimeout(() => {
					this.closeInvoiceDeleteModal();
				});
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
			this.$store.dispatch('getClientUser')
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
		//this.getAllProduct();
		//this.$store.dispatch("getRecurringCycle");
		this.$store.dispatch("getTax");
		this.$store.dispatch("getInvoice");
		this.$store.dispatch("getPaymentMethod");

	}
}