<template>
  <div>
    <div class="content-wrapper">
      <div class="d-flex align-items-center justify-content-between">
        <app-breadcrumb :page-title="$t('inventory_details')" />
        <div class="mb-4">
          <button
            v-if="$can('invoice_download')"
            class="btn btn-primary btn-with-shadow mr-1"
            type="button"
            @click="downloadInventory"
          >
            <app-icon class="size-20 mr-2" name="download" />
            {{ $t("action_invoice_download") }}
          </button>
        </div>
      </div>

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

            <!-- Invoice & product details -->
            <div class="cus-invoice_container__item cus-px-5">
              <table class="cus-w-100 cus-font-xm cus-table-strip" border="0" cellspacing="0" cellpadding="0">
                <thead>
                  <tr class="cus-bg-dark cus-text-light">
                    <!-- Client contact -->
                    <th class="cus-w-25 cus-p-1 cus-text-center">
                    <p v-if="formData.invoice.client_number">{{ $t('contact') }}: {{
                        formData.invoice.client_number }}</p>
                    </th>

                    <!-- Client name -->
                    <th class="cus-w-25 cus-p-1 cus-text-left">
                      <p v-if="formData.invoice.client_name" class="cus-bold">{{ $t('Name') }}: {{
                        formData.invoice.client_name }}</p>
                    </th>

                    <!-- Invoice number -->
                    <th class="cus-w-25 cus-p-1 cus-text-center">
                    <td class="cus-bold">{{ $t('invoice_no') }}</td>
                    <td>:</td>
                    <td class="cus-text-right">{{ formData.invoice.is_from_estimate ? 'EST-' : '' }}{{
                      formData.invoice.invoice_number }}</td>
                    </th>

                    <!-- Invoice date -->
                    <th class="cus-w-25 cus-text-right">
                    <td class="cus-bold cus-text-right">{{ "Date : " }}</td>
                    <td class="cus-text-right">{{ formData.invoice.date }}</td>

                    </th>
                  </tr>
                </thead>
              </table>

              <table class="cus-w-100">
                    <thead>
                        <tr>
                            <th class="cus-w-33 cus-p-1 cus-text-left">SN No.</th>
                            <th class="cus-w-33 cus-p-1 cus-text-left">Particulars</th>
                            <th class="cus-w-33 cus-p-1 cus-text-left">Condition</th>
                            <th class="cus-w-33 cus-p-1 cus-text-left">Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="index in Math.ceil(formData.inventory_details.length / 2)" :key="index">
                            <td class="cus-p-1">{{ index }}</td>
                            <td class="cus-p-1">{{ formData.inventory_details[(index - 1) * 2].product_name }}</td>
                            <td class="cus-p-1">
                            {{ 
                                (() => {
                                const condition = formData.inventory_details[(index - 1) * 2].condition;
                                if (condition === 'S') {
                                    return 'Scratched';
                                } else if (condition === 'D') {
                                    return 'Damaged';
                                } else if (condition === 'N') {
                                    return 'New';
                                } else if (condition === 'U') {
                                    return 'Used';
                                } else {
                                    return 'Unknown';
                                }
                                })()
                            }}
                            </td>
                            <td class="cus-p-1">{{ formData.inventory_details[(index - 1) * 2].quantity }}</td>
                        </tr>
                    </tbody>
              </table>
            </div>
            <div class="cus-f-clear"></div>

            <!-- Notes -->
            <div class="cus-invoice_container__item cus-p-5">
              <div class="cus-w-100 cus-f-left">
                <div class="cus-w-100 cus-f-left">
                  <p class="cus-bold">Notes:</p>
                  <p>{{ formData.notes }}</p>
                </div>
                <div class="cus-f-clear"></div>
              </div>
              <div class="cus-f-clear"></div>
            </div>

            <!-- Signature -->
            <div class="cus-invoice_container__item cus-p-5">
              <div class="cus-w-100 cus-f-left">
                <div class="cus-w-100 cus-f-left">
                  <img :src="getImageUrl(formData.signature)" alt="signature" style="max-width: 100%;" />
                </div>
                <div class="cus-w-100 cus-f-left">
                  <img :src="getImageUrl(formData.delivery_signature)" alt="signature" style="max-width: 100%;" />
                </div>
                <div class="cus-f-clear"></div>
              </div>
              <div class="cus-f-clear"></div>
            </div>
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

export default {
  name: "InventoryDetails",
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
      isBillAddEditModalActive: false,
      selectUrl: "",
      formData: {},
    };
  },
  methods: {
    afterSuccessFromGetEditData(response) {
      this.formData = response.data;
      this.dataLoaded = true;
      console.log("Data: ", this.formData);
    },
    downloadInventory() {
      window.open(
        AppFunction.getAppUrl(
          `inventory-download/${this.formData.id}`
        )
      );
    },
    getImageUrl(fileName) {
      return `${window.location.origin}/signatures/${fileName}`;
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

table tbody tr td {
  padding: 5px !important;
  border: 2px solid #000;
}

table thead tr th:last-of-type,
table tbody tr td:last-of-type {
  text-align: right;
}
</style>