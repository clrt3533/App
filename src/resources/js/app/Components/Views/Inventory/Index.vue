<template>
  <div class="content-wrapper">
    <div class="d-flex align-items-center justify-content-between">
      <app-breadcrumb page-title="Inventory" />

      <a
        :href="AppFunction.getAppUrl('inventory/create/view')"
        v-if="$can('create_invoices')"
        class="btn btn-success btn-with-shadow"
      >
        <app-icon name="plus" class="size-20 mr-2" />
        {{ $t("new_inventory") }}
      </a>
    </div>

    <app-table :id="tableId" :options="options" @action="getListAction" />

    <!-- Delete inventory -->
    <app-delete-modal
      v-if="confirmationModalActive"
      modal-id="delete-confirm-modal"
      :message="$t('are_you_sure_to_delete_this_inventory')"
      @confirmed="confirmDelete"
      @cancelled="cancelledDelete"
    />
  </div>
</template>    
    
<script>
import InventoryTableMixin from "../../../Mixins/InventoryTableMixin";

export default {
  name: "Index",
  mixins: [InventoryTableMixin],
};
</script>