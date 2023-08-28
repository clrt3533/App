<template>
  <modal
    :modal-id="modalId"
    :title="selectedUrl ? $t('update_bill') : $t('add_bill')"
    :preloader="preloader"
    @submit="submit"
    @closeModal="closeModal"
  >
    <template slot="body">
      <app-overlay-loader v-if="preloader" />

      <form
        v-else
        class="mb-0"
        :class="{ 'loading-opacity': preloader }"
        ref="form"
        :data-url="selectedUrl ? selectedUrl : BILLHISTORY"
      >
        <div class="form-group">
          <label for="invoice">
            {{ $t("invoice") }}<sup class="text-size-20 top-1">*</sup>
          </label>
          <app-input
            id="invoice"
            v-model="formData.invoice_id"
            :error-message="$errorMessage(errors, 'invoice_id')"
            :options="invoiceNumberOptions"
            @input="handleInvoiceIdInput"
            :placeholder="$t('choose_an_invoice')"
            type="search-and-select"
          />
        </div>

        <div class="form-group">
          <label>Packing</label>
          <app-input
            class="margin-right-8"
            v-model="formData.packing"
            :error-message="$errorMessage(errors, 'packing')"
            placeholder=""
            type="number"
            @input="calculateSubTotal"
          />
        </div>

        <div class="form-group">
          <label>Local</label>
          <app-input
            class="margin-right-8"
            v-model="formData.local"
            :error-message="$errorMessage(errors, 'local')"
            type="number"
            @input="calculateSubTotal"
          />
        </div>
        <div class="form-group">
          <label>GST</label>
          <app-input
            class="margin-right-8"
            v-model="formData.gst"
            :error-message="$errorMessage(errors, 'gst')"
            type="number"
            @input="calculateSubTotal"
          />
        </div>
        <div class="form-group">
          <label>Transport</label>
          <app-input
            class="margin-right-8"
            v-model="formData.transport"
            :error-message="$errorMessage(errors, 'transport')"
            type="number"
            @input="calculateSubTotal"
          />
        </div>
        <div class="form-group">
          <label>Unpacking</label>
          <app-input
            class="margin-right-8"
            v-model="formData.unpacking"
            :error-message="$errorMessage(errors, 'unpacking')"
            type="number"
            @input="calculateSubTotal"
          />
        </div>
        <div class="form-group">
          <label>Insurance</label>
          <app-input
            class="margin-right-8"
            v-model="formData.insuarance"
            :error-message="$errorMessage(errors, 'insuarance')"
            type="number"
            @input="calculateSubTotal"
          />
        </div>
        <div class="form-group">
          <label>Loading</label>
          <app-input
            class="margin-right-8"
            v-model="formData.loading"
            :error-message="$errorMessage(errors, 'loading')"
            type="number"
            @input="calculateSubTotal"
          />
        </div>
        <div class="form-group">
          <label>AC</label>
          <app-input
            class="margin-right-8"
            v-model="formData.ac"
            :error-message="$errorMessage(errors, 'ac')"
            type="number"
            @input="calculateSubTotal"
          />
        </div>
        <div class="form-group">
          <label>Unloading</label>
          <app-input
            class="margin-right-8"
            v-model="formData.unloading"
            :error-message="$errorMessage(errors, 'unloading')"
            type="number"
            @input="calculateSubTotal"
          />
        </div>

        <div class="form-group">
          <label>Carpentry</label>
          <app-input
            class="margin-right-8"
            v-model="formData.car_transport"
            :error-message="$errorMessage(errors, 'car_transport')"
            type="number"
            @input="calculateSubTotal"
          />
        </div>
      </form>
    </template>
  </modal>
</template>

<script>
import { SubmitFormMixins } from "../../../Mixins/billar/SubmitFormMixins";
import { axiosGet, urlGenerator } from "../../../Helpers/AxiosHelper";
import { BILLHISTORY } from "../../../Config/BillarApiUrl";

export default {
  name: "BillAddEditModal",
  mixins: [SubmitFormMixins],
  props: ["billId"],
  data() {
    return {
      BILLHISTORY,
      modalId: "bill-add-edit-modal",
      editorShow: false,
      invoiceList: [],
      billList: [],
      formData: {
        invoice_id: null,
        packing: 0,
        unloading: 0,
        local: 0,
        gst: 0,
        transport: 0,
        unpacking: 0,
        car_transport: 0,
        loading: 0,
        ac: 0,
        insuarance: 0,
      },
      invoiceNumberOptions: {
        url: urlGenerator("all-invoice"),
        per_page: 10,
        modifire: (item) => {
          const checkIfBillExists = this.billList.some(
            (bill) => bill.invoice_id === item.id
          );
          this.invoiceList.push(item);

          if (this.selectedUrl) {
            return { id: item.id, value: item.id };
          } else {
            if (!checkIfBillExists) {
              return { id: item.id, value: item.id };
            }
            return {};
          }
        },
        loader: "app-pre-loader",
      },
    };
  },
  methods: {
    handleInvoiceIdInput(invoiceId) {
      if (!this.selectedUrl) {
        axiosGet(`${BILLHISTORY}/${invoiceId}?invoice=true`).then(
          ({ data }) => {
            let newBill = data?.bill;
            newBill.invoice_id = Number(data?.bill.invoice_id);
            this.formData = newBill;
            console.log("New bill data: ", this.formData);
          }
        );
      }
    },
    calculateSubTotal() {
      const fieldsToSum = [
        "packing",
        "unloading",
        "local",
        "gst",
        "transport",
        "unpacking",
        "car_transport",
        "loading",
        "ac",
        "insuarance",
      ];
      const subTotal = fieldsToSum.reduce(
        (sum, field) => sum + Number(this.formData[field]),
        0
      );
      this.formData.sub_total = subTotal;
    },
    convertStringsToNumbers() {
      for (const key in this.formData) {
        if (typeof this.formData[key] === "string") {
          this.formData[key] = parseFloat(this.formData[key]);
        }
      }

      return this.formData;
    },
    submit() {
      const payload = this.convertStringsToNumbers();
      this.save(payload);
    },
    afterSuccessFromGetEditData(response) {
      if (response.data.bill.id) {
        console.log("Old bill");
        const url = this.selectedUrl;
        const firstPart = url.match(/^[^\d/]+/)[0];
        this.selectedUrl = firstPart + "/" + response.data.bill.id;
      } else {
        console.log("New bill");
        this.selectedUrl = false;
      }
      this.formData = response.data.bill;

      this.editorShow = false;
      setTimeout(() => {
        this.editorShow = true;
      });
      this.preloader = false;
    },
  },
  mounted() {
    if (!this.selectedUrl) {
      this.editorShow = true;
    }

    axiosGet(`/bill-histories?per_page=100&page=1&searhc=&orderBy=desc`).then(
      ({ data }) => {
        this.billList = data.data;
      }
    );
  },
};
</script>