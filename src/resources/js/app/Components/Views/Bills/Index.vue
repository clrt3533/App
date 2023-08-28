<template>
  <div class="content-wrapper">
    <div class="d-flex align-items-center justify-content-between">
      <app-breadcrumb page-title="Bills" />
      <button
        type="button"
        v-if="$can('create_payment_histories')"
        class="btn btn-success btn-with-shadow mb-4"
        @click="openModal"
      >
        <app-icon name="plus" class="size-20 mr-2" />
        New Bill
      </button>
    </div>

    <app-table :id="tableId" :options="options" @action="getListAction" />

    <!-- BILL Add/Edit Modal -->
    <bill-add-edit-modal
      v-if="isModalActive"
      :table-id="tableId"
      :selected-url="selectUrl"
      @closeModal="closeModal"
    />

    <!-- Payment Delete Modal -->
    <app-delete-modal
      v-if="confirmationModalActive"
      modal-id="delete-confirm-modal"
      :message="$t('are_you_sure_to_delete_this_bill')"
      @confirmed="confirmDelete"
      @cancelled="cancelledDelete"
    />
  </div>
</template>    
  
  <script>
import BillsTableMixin from "../../../Mixins/BillsTableMixin";

export default {
  name: "Index",
  mixins: [BillsTableMixin],
};
</script>