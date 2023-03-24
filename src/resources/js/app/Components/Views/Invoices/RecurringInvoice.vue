<template>
	<div class="content-wrapper">
		<div class="d-flex align-items-center justify-content-between">
			<app-breadcrumb :page-title="$t('recurring_invoices')"/>
		</div>
		
		<app-table :id="tableId" :options="options" @action="getListAction"/>
		
		
		<!-- Payment Add/Edit Modal -->
		<payment-add-edit-modal
			v-if="isPaymentAddEditModalActive"
			:table-id="tableId"
			:invoice-id="invoiceId"
			@closeModal="closePaymentAddEditModal"
		/>
		
		<invoice-payment-modal
			v-if="isInvoicePaymentModalActive"
			:table-id="tableId"
			:invoice-id="invoiceId"
			:selected-url="selectUrl"
			@closeModal="closeInvoicePaymentModal"
		/>
		
		<!-- Client Delete Modal -->
		<app-delete-modal
			v-if="confirmationModalActive"
			modal-id="delete-confirm-modal"
			:message="$t('are_you_sure_to_delete_this_invoice')"
			@confirmed="confirmDelete"
			@cancelled="cancelledDelete"
		/>
		
		<!-- Client Delete Modal -->
		<app-delete-modal
			v-if="invoiceProductDeleteModal"
			modal-id="invoice-product-delete-confirm-modal"
			:message="$t('are_you_sure_to_delete_this_product')"
			@confirmed="confirmDeleteInvoiceProduct"
			@cancelled="closeInvoiceDeleteModal"
		/>
	
	</div>
</template>

<script>
import RecurringInvoiceTableMixin from "../../../Mixins/RecurringInvoiceTableMixin";

export default {
	name: "RecurringInvoice",
	mixins: [RecurringInvoiceTableMixin]
}
</script>