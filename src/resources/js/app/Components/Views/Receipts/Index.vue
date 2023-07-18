<template>
  <div class="content-wrapper">
    <div class="d-flex align-items-center justify-content-between">
      <app-breadcrumb page-title="Receipts" />
      <button
        type="button"
        v-if="$can('create_payment_histories')"
        class="btn btn-success btn-with-shadow mb-4"
        @click="openModal"
      >
        <app-icon name="plus" class="size-20 mr-2" />
        New Receipt
      </button>
    </div>

    <div class="row">
        
    </div>

    <!-- Receipt Add/Edit Modal -->
    <receipt-add-edit-modal
      v-if="isModalActive"
      :table-id="tableId"
      :selected-url="selectUrl"
      @closeModal="closeModal"
    />
  </div>
</template>
        

<script>
import PaymentsTableMixin from "../../../Mixins/PaymentsTableMixin";

export default {
  name: "Index",
  mixins: [PaymentsTableMixin],
};
</script>