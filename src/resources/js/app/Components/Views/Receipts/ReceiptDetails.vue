<template>
  <div>
    <div class="content-wrapper">
      <div class="d-flex align-items-center justify-content-between">
        <app-breadcrumb :page-title="$t('receipt_details')" />
        <div class="mb-4">
          <button
            v-if="$can('invoice_download')"
            class="btn btn-primary btn-with-shadow mr-1"
            type="button"
            @click="downloadReceipt"
          >
            <app-icon class="size-20 mr-2" name="download" />
            {{ $t("action_invoice_download") }}
          </button>
        </div>
      </div>

      <!-- Receipt design-->
      <div class="row justify-content-center">
        <div v-if="dataLoaded" class="invoice_preview primary-card-color">
          <div id="print-invoice" class="cus-invoice_container">
            <!-- Company logo -->
            <div class="cus-invoice_container__item cus-px-5 cus-mt-5">
              <div class="cus-w-100">
                <div>
                  <img
                    style="width: 100%"
                    src="http://app.saipackersandmovers.com/images/card.jpeg"
                    alt="Sai Packers and Movers logo"
                  />
                </div>
              </div>
            </div>

            <!-- Receipt details -->
            <div class="recipt-container">
              <div class="rc-title-container">
                <div class="rc-title">
                  <h3>Money Receipt</h3>
                </div>
              </div>
            </div>

            <div class="cus-invoice_container__item cus-px-5 receipt-no-date">
              <table
                cellspacing="1"
                cellpadding="0"
                class="cus-w-100 cus-font-xm cus-table-strip"
              >
                <tr class="cus-bg-transparent">
                  <td
                    colspan="1"
                    class="cus-p-1 cus-text-left"
                    style="width: 50%"
                  >
                    <p class="cus-mt-6">
                      <span>Receipt No.</span>
                      <span class="cus-bold">{{ formData.id }}</span>
                    </p>
                  </td>
                  <td
                    colspan="1"
                    class="cus-p-1 cus-text-left"
                    style="width: 50%; text-align: right"
                  >
                    <p class="cus-mt-6">
                      <span class="cus-bold">Date:</span
                      ><span>{{ formData.date }}</span>
                    </p>
                  </td>
                </tr>
              </table>
            </div>

            <div class="cus-invoice_container__item cus-px-5 client-name-phone">
              <table
                cellspacing="1"
                cellpadding="0"
                class="cus-w-100 cus-font-xm cus-table-strip"
              >
                <tr class="cus-bg-transparent">
                  <td
                    colspan="1"
                    class="cus-p-1 cus-text-left"
                    style="width: 40%"
                  >
                    <p class="cus-mt-6">
                      <span>Received with thanks from M/S.</span>
                    </p>
                  </td>
                  <td
                    colspan="1"
                    class="cus-p-1 cus-text-left cus-bold"
                    style="width: 30%"
                  >
                    <p class="cus-mt-6">
                      <span class="">{{ formData.client_name }}</span>
                    </p>
                  </td>
                  <td
                    colspan="1"
                    class="cus-p-1 cus-text-right"
                    style="width: 30%"
                  >
                    <p class="cus-mt-6">
                      <span>Phone:</span
                      ><span class="cus-bold">{{ formData.client_phone }}</span>
                    </p>
                  </td>
                </tr>
              </table>
            </div>

            <div class="cus-invoice_container__item cus-px-5 to-from">
              <table
                cellspacing="1"
                cellpadding="0"
                class="cus-w-100 cus-font-xm cus-table-strip"
              >
                <tr class="cus-bg-transparent">
                  <td
                    colspan="1"
                    class="cus-p-1 cus-text-left"
                    style="width: 50%"
                  >
                    <p class="cus-mt-6">
                      <span>From.</span>
                      <span class="cus-bold">{{ formData.from }}</span>
                    </p>
                  </td>
                  <td
                    colspan="1"
                    class="cus-p-1 cus-text-left"
                    style="width: 50%"
                  >
                    <p class="cus-mt-6">
                      <span>To:</span>
                      <span class="cus-bold">{{ formData.to }}</span>
                    </p>
                  </td>
                </tr>
              </table>
            </div>

            <div class="cus-invoice_container__item cus-px-5 payment-method">
              <table
                cellspacing="1"
                cellpadding="0"
                class="cus-w-100 cus-font-xm cus-table-strip"
              >
                <tr class="cus-bg-transparent">
                  <td
                    colspan="1"
                    class="cus-p-1 cus-text-left"
                    style="width: 100%"
                  >
                    <p class="cus-mt-6">
                      <span>Paid In:</span>
                      <span class="cus-bold">Cash</span>
                    </p>
                  </td>
                </tr>
              </table>
            </div>

            <div class="cus-invoice_container__item cus-px-5 amount-words">
              <table
                cellspacing="1"
                cellpadding="0"
                class="cus-w-100 cus-font-xm cus-table-strip"
              >
                <tr class="cus-bg-transparent">
                  <td
                    colspan="1"
                    class="cus-p-1 cus-text-left"
                    style="width: 100%"
                  >
                    <p class="cus-mt-6">
                      <span>Rs.</span>
                      <span class="cus-bold">{{ formData.amount_words }}</span>
                    </p>
                  </td>
                </tr>
              </table>
            </div>
          </div>

          <div class="amount-signature">
            <div class="receipt-container">
              <div class="rc-title-container">
                <div class="rc-title">
                  <h4>{{ numberWithCurrencySymbol(formData.amount) }}</h4>
                </div>
              </div>
            </div>

            <div class="signature">signature here</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import AppFunction from "../../../../core/helpers/app/AppFunction";
import { SubmitFormMixins } from "../../../Mixins/billar/SubmitFormMixins";
import {
  formatDateToLocal,
  numberWithCurrencySymbol,
} from "../../../Helpers/Helpers";
import { mapGetters } from "vuex";
import { INVOICES } from "../../../Config/BillarApiUrl";
import { clientUser, status } from "../../../Mixins/FilterMixin";

export default {
  name: "receiptDetails",
  mixins: [SubmitFormMixins],
  props: {
    configData: {},
  },
  data() {
    return {
      numberWithCurrencySymbol,
      formatDateToLocal,
      dataLoaded: false,
      AppFunction,
      isInvoiceAddEditModalActive: false,
      selectUrl: "",
      formData: {},
    };
  },
  methods: {
    afterSuccessFromGetEditData(response) {
      this.formData = response.data;
      this.dataLoaded = true;
    },
    downloadReceipt() {
      window.open(
        AppFunction.getAppUrl(`receipt-download/${this.formData.id}`)
      );
    },
    formatDate(date) {
      const formattedDate = new Date(date);
      const day = String(formattedDate.getDate()).padStart(2, "0");
      const month = String(formattedDate.getMonth() + 1).padStart(2, "0");
      const year = formattedDate.getFullYear();

      return `${day}/${month}/${year}`;
    },

    formatTime(date) {
      const formattedTime = new Date(date);
      const hours = String(formattedTime.getHours()).padStart(2, "0");
      const minutes = String(formattedTime.getMinutes()).padStart(2, "0");

      return `${hours}:${minutes}`;
    },
  },
};
</script>

<style scoped>
/* dev */
.t {
  border: 1px solid red;
}

.invoice_preview {
  width: 100%;
  max-width: 800px;
  min-height: 700px;
}

.cus-bg-t {
  background-color: #00660050;
}

.cus-bg-dark {
  background-color: #d9251c !important;
}

.cus-bg-secondary {
  background-color: #dddddd !important;
}

.cus-bg-transparent {
  background-color: transparent !important;
}

.invoice_preview * {
  font-family: Arial, Helvetica, sans-serif;
  margin: 0;
  padding: 0;
}

/*common*/
.cus-m-0 {
  margin: 0;
}

.cus-m-1 {
  margin: 5px;
}

.cus-m-2 {
  margin: 10px;
}

.cus-m-3 {
  margin: 15px;
}

.cus-m-4 {
  margin: 20px;
}

.cus-m-5 {
  margin: 25px;
}

.cus-mx-1 {
  margin: 0 5px;
}

.cus-mx-2 {
  margin: 0 10px;
}

.cus-mx-3 {
  margin: 0 15px;
}

.cus-mx-4 {
  margin: 0 20px;
}

.cus-mx-5 {
  margin: 0 25px;
}

.cus-my-1 {
  margin: 5px 0;
}

.cus-my-2 {
  margin: 10px 0;
}

.cus-my-3 {
  margin: 15px 0;
}

.cus-my-4 {
  margin: 20px 0;
}

.cus-my-5 {
  margin: 25px 0;
}

.cus-mt-1 {
  margin-top: 5px;
}

.cus-mt-2 {
  margin-top: 10px;
}

.cus-mt-3 {
  margin-top: 15px;
}

.cus-mt-4 {
  margin-top: 20px;
}

.cus-mt-5 {
  margin-top: 25px;
}

.cus-mb-1 {
  margin-bottom: 5px;
}

.cus-mb-2 {
  margin-bottom: 10px;
}

.cus-mb-3 {
  margin-bottom: 15px;
}

.cus-mb-4 {
  margin-bottom: 20px;
}

.cus-mb-5 {
  margin-bottom: 25px;
}

.cus-p-0 {
  padding: 0;
}

.cus-p-1 {
  padding: 5px;
}

.cus-p-2 {
  padding: 10px;
}

.cus-p-3 {
  padding: 15px;
}

.cus-p-4 {
  padding: 20px;
}

.cus-p-5 {
  padding: 25px;
}

.cus-px-1 {
  padding: 0 5px;
}

.cus-px-2 {
  padding: 0 10px;
}

.cus-px-3 {
  padding: 0 15px;
}

.cus-px-4 {
  padding: 0 20px;
}

.cus-px-5 {
  padding: 0 25px;
}

.cus-py-1 {
  padding: 5px 0;
}

.cus-py-2 {
  padding: 10px 0;
}

.cus-py-3 {
  padding: 15px 0;
}

.cus-py-4 {
  padding: 20px 0;
}

.cus-py-5 {
  padding: 25px 0;
}

.cus-w-10 {
  width: 10%;
}

.cus-w-15 {
  width: 15%;
}

.cus-w-45 {
  width: 45%;
}

.cus-w-25 {
  width: 25%;
}

.cus-w-50 {
  width: 50%;
}

.cus-w-75 {
  width: 75%;
}

.cus-w-100 {
  width: 100%;
}

.cus-h-100 {
  height: 100%;
}

.cus-f-left {
  float: left;
}

.cus-f-right {
  float: right;
}

.cus-f-clear {
  clear: both;
}

.cus-text-left {
  text-align: left;
}

.cus-text-right {
  text-align: right;
}

.cus-text-center {
  text-align: center;
}

.cus-text-secondary {
  color: #666666;
}

.cus-text-black {
  color: #000 !important;
}

.cus-text-light {
  color: #fff;
}

.cus-text-capital {
  text-transform: uppercase;
}

.cus-thin {
  font-weight: lighter;
}

.cus-bold {
  font-weight: bold;
}

.cus-font-xm {
  font-size: small;
}

.cus-font-md {
  font-size: medium;
}

.cus-font-lg {
  font-size: large;
}

.cus-table-strip tr:nth-child(even) {
  background-color: #66666610;
}

.cus-invoice_container * {
  box-sizing: content-box;
}

.cus-logo {
  width: 96px;
}

.cus-hr {
  background-color: #999999;
  border: none;
  height: 1px;
}

.rc-title-container .rc-title {
  width: 50%;
  margin: 0 auto;
  margin-top: 20px;
  margin-bottom: 20px;
  border-radius: 10px;
  color: #fff;
  background: #d9251c;
  text-align: center;
  height: 50px;
  padding-top: 10px;
}

.cus-invoice_container__item {
  padding-top: 15px;
}

.amount-signature {
  display: flex;
  justify-content: space-between;
}

.amount-signature .receipt-container {
    width: 50%
}

.amount-signature .rc-title-container .rc-title {
  width: 60%;
  margin: unset;
  margin-top: 20px;
  text-align: left;
  padding-left: 15px;
}
</style>
