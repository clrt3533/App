<template>
  <modal
    :modal-id="modalId"
    :title="selectedUrl ? 'Edit Receipt' : 'Add Receipt'"
    :preloader="preloader"
    @submit="submit"
    @closeModal="closeModal"
  >
    <template slot="body">
      <app-overlay-loader v-if="preloader" />

      <form
        v-else
        ref="form"
        class="mb-0"
        :class="{ 'loading-opacity': preloader }"
        :data-url="selectedUrl ? selectedUrl : RECEIPTHISTORY"
      >
        <div class="row">
          <div class="col-lg-6">
            <div class="form-group">
              <label for="payment">
                {{ $t("payment") }} <sup class="text-size-20 top-1">*</sup>
              </label>
              <app-input
                id="payment"
                v-model="formData.invoice_id"
                :error-message="$errorMessage(errors, 'invoice_id')"
                :options="invoiceNumberOptions"
                @input="handleInvoiceIdInput"
                placeholder="Choose payment"
                type="search-and-select"
              />
            </div>
          </div>
          <div class="col-lg-6">
            <div class="form-group">
              <label for="receivedOn"
                >{{ $t("date") }} <sup class="text-size-20 top-1">*</sup></label
              >
              <app-input
                id="receivedOn"
                type="date"
                v-model="formData.date"
                :error-message="$errorMessage(errors, 'date')"
              />
            </div>
          </div>

          <div class="col-lg-6">
            <div class="form-group">
              <label for="client_name"
                >Received from <sup class="text-size-20 top-1">*</sup>
              </label>
              <app-input
                id="client_name"
                type="text"
                :placeholder="'Mr/s'"
                v-model="formData.client_name"
                :error-message="$errorMessage(errors, 'client_name')"
              />
            </div>
          </div>

          <div class="col-lg-6">
            <div class="form-group">
              <label for="client_phone"
                >Phone no. <sup class="text-size-20 top-1">*</sup>
              </label>
              <app-input
                id="client_phone"
                type="text"
                :placeholder="'+91...'"
                v-model="formData.client_phone"
                :error-message="$errorMessage(errors, 'client_phone')"
              />
            </div>
          </div>

          <div class="col-lg-6">
            <div class="form-group">
              <label for="from"
                >From <sup class="text-size-20 top-1">*</sup>
              </label>
              <app-input
                id="from"
                type="text"
                :placeholder="''"
                v-model="formData.from"
                :error-message="$errorMessage(errors, 'from')"
              />
            </div>
          </div>

          <div class="col-lg-6">
            <div class="form-group">
              <label for="to"
                >To <sup class="text-size-20 top-1">*</sup>
              </label>
              <app-input
                id="to"
                type="text"
                :placeholder="''"
                v-model="formData.to"
                :error-message="$errorMessage(errors, 'to')"
              />
            </div>
          </div>

          <div class="col-lg-12">
            <div class="form-group">
              <label for="paymentMethod">
                {{ $t("payment_method") }}
                <sup class="text-size-20 top-1">*</sup>
              </label>
              <app-input
                id="paymentMethod"
                type="search-select"
                :placeholder="$t('choose_a_payment_method')"
                v-model="formData.payment_method_id"
                list-value-field="name"
                :list="paymentMethodList"
                :error-message="$errorMessage(errors, 'payment_method_id')"
              />
            </div>
          </div>

          <div class="col-12">
            <label
              >Amount in words <sup class="text-size-20 top-1">*</sup></label
            >
          </div>
          <div class="col-12 mb-4">
            <app-input
              class="margin-right-8"
              v-model="formData.amount_words"
              :error-message="$errorMessage(errors, 'amount_words')"
              placeholder=""
              type="textarea"
            />
          </div>
        </div>
      </form>
    </template>
  </modal>
</template>

<script>
import moment from "moment";
import { mapGetters } from "vuex";

import { SubmitFormMixins } from "../../../Mixins/billar/SubmitFormMixins";
import { urlGenerator } from "../../../Helpers/AxiosHelper";
import { RECEIPTHISTORY } from "../../../Config/BillarApiUrl";

export default {
  name: "ReceiptAddEditModal",
  mixins: [SubmitFormMixins],
  props: ["invoiceId"],
  data() {
    return {
      RECEIPTHISTORY,
      modalId: "receipt-add-edit-modal",
      editorShow: false,
      paymentList: [],
      formData: {
        received_on: moment(new Date()).format("YYYY-MM-DD"),
        invoice_id: null,
        invoice_number: "",
        client_name: "",
        client_phone: "",
        to: "",
        from: "",
        payment_method_id: "",
        amount: null,
        amount_words: "",
      },
      invoiceNumberOptions: {
        url: urlGenerator("payment-histories?orderBy=desc&"),
        per_page: 10,
        modifire: (item) => {
          this.paymentList.push(item);
          return {
            id: item.id,
            value: `#${item.invoice.invoice_number} - ${item.amount}`,
          };
        },
        loader: "app-pre-loader",
      },
    };
  },
  computed: {
    ...mapGetters({
      paymentMethodList: "getPaymentMethod",
    }),
  },
  methods: {
    handleInvoiceIdInput(invoiceId) {
      const invoice = this.paymentList.filter(
        (payment) => invoiceId === payment.invoice_id
      )[0];

      this.formData.invoice_number = invoice.invoice.invoice_number;
      this.formData.client_name = invoice.invoice.client_name;
      this.formData.payment_method_id = invoice.payment_method.id;
      this.formData.amount = invoice.amount;

      console.log("Data: ", invoice, this.formData);
    },
    submit() {
      this.save(this.formData);
    },
  },
  mounted() {
    if (!this.selectedUrl) {
      this.editorShow = true;
    }
  },
};
</script>