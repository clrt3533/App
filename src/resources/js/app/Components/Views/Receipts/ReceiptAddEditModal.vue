<template>
  <modal
    :modal-id="modalId"
    :title="selectedUrl ? $t('update_receipt') : $t('add_receipt')"
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
  props: ["paymentId"],
  data() {
    return {
      RECEIPTHISTORY,
      modalId: "receipt-add-edit-modal",
      editorShow: false,
      paymentList: [],
      formData: {
        payment_id: "",
        invoice_id: null,
        data: "",
        invoice_number: "",
        client_name: "",
        client_phone: "",
        to: "",
        from: "",
        payment_method_id: "",
        amount: "",
        amount_words: "",
      },
      invoiceNumberOptions: {
        url: urlGenerator("payment-histories?orderBy=desc&"),
        per_page: 10,
        modifire: (item) => {
          this.paymentList.push(item);
          return {
            id: item.id,
            value: `${item.id}`,
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
    amountInWords(amount) {
      // Arrays of words for single-digit, double-digit, and powers of ten
      const singleDigit = [
        "",
        "One",
        "Two",
        "Three",
        "Four",
        "Five",
        "Six",
        "Seven",
        "Eight",
        "Nine",
      ];
      const twoDigits = [
        "",
        "Ten",
        "Twenty",
        "Thirty",
        "Forty",
        "Fifty",
        "Sixty",
        "Seventy",
        "Eighty",
        "Ninety",
      ];
      const powersOfTen = [
        "Hundred",
        "Thousand",
        "Million",
        "Billion",
        "Trillion",
      ];

      // Helper function to convert a number less than 1000 into words
      function convertLessThanOneThousand(num) {
        let result = "";

        if (num >= 100) {
          result +=
            singleDigit[Math.floor(num / 100)] + " " + powersOfTen[0] + " ";
          num %= 100;
        }

        if (num >= 10 && num <= 19) {
          // Special cases for 10-19
          result += singleDigit[num] + " ";
        } else if (num >= 20) {
          result += twoDigits[Math.floor(num / 10)] + " ";
          num %= 10;
        }

        if (num > 0) {
          result += singleDigit[num] + " ";
        }

        return result.trim();
      }

      if (amount === 0) {
        return "Zero";
      }

      // Split the number into groups of three digits
      const chunks = [];
      while (amount > 0) {
        chunks.push(amount % 1000);
        amount = Math.floor(amount / 1000);
      }

      // Convert each chunk to words and append power of ten
      let words = "";
      for (let i = 0; i < chunks.length; i++) {
        if (chunks[i] !== 0) {
          words =
            convertLessThanOneThousand(chunks[i]) +
            powersOfTen[i] +
            " " +
            words;
        }
      }

      return words.trim() + "rupees only";
    },
    handleInvoiceIdInput(paymentId) {
      const payment = this.paymentList.filter(
        (item) => paymentId === item.id
      )[0];

      this.formData.payment_id = paymentId;
      this.formData.invoice_number = payment.invoice.invoice_number;
      this.formData.date = payment.received_on;
      this.formData.client_name = payment.invoice.client_name;
      this.formData.client_phone = payment.invoice.client_number;
      this.formData.payment_method_id = payment.payment_method.id;
      this.formData.amount = payment.amount;
      this.formData.amount_words = this.amountInWords(payment.amount);
    },
    afterSuccessFromGetEditData(response) {
      this.formData = response.data;
      this.formData.invoice_id = response.data.payment_id;
      ;

      this.editorShow = false;
      setTimeout(() => {
        this.editorShow = true;
      });
      this.preloader = false;
    },
    submit() {
      let payload = this.formData;
      payload.date = moment(this.formData.date).format("YYYY-MM-DD");

      this.save(payload);
    },
  },
  mounted() {
    if (!this.selectedUrl) {
      this.editorShow = true;
    }
  },
};
</script>