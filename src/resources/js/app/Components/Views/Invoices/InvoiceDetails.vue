<template>
  <div>
    <div class="content-wrapper">
      <div class="d-flex align-items-center justify-content-between">
        <app-breadcrumb :page-title="$t('invoice_details')" />
        <div class="mb-4">
          <button v-if="$can('invoice_download')" class="btn btn-primary btn-with-shadow mr-1" type="button"
            @click="downloadInvoice">
            <app-icon class="size-20 mr-2" name="download" />
            {{ $t('action_invoice_download') }}
          </button>
<<<<<<< Updated upstream
          <!-- <button v-if="$can('invoice_send')" class="btn btn-success btn-with-shadow" type="button"
            @click="openSendInvoiceModal">
            <app-icon class="size-20 mr-2" name="send" />
            {{ $t('send') }}
          </button> -->
        </div>
      </div>
      <!-- Invoice design-->
=======
          <button v-if="$can('invoice_send')" class="btn btn-success btn-with-shadow" type="button"
            @click="openSendInvoiceModal">
            <app-icon class="size-20 mr-2" name="send" />
            {{ $t('send') }}
          </button>
        </div>
      </div>

>>>>>>> Stashed changes
      <div class="row justify-content-center">
        <div v-if="dataLoaded" class="invoice_preview primary-card-color">
          <div id="print-invoice" class="cus-invoice_container">
            <!-- Company logo -->
            <div class="cus-invoice_container__item cus-px-5 cus-mt-5">
              <div class="cus-w-100">
                <div>
                  <img style="width: 100%;" src="http://app.saipackersandmovers.com/images/card.jpeg"
<<<<<<< Updated upstream
                    alt="Sai Packers and Movers logo">
=======
                    alt="Sai Packers and Movers">
>>>>>>> Stashed changes
                </div>
              </div>
            </div>

            <!-- Invoice details -->
            <div class="cus-invoice_container__item cus-px-5">
              <table class="cus-w-100 cus-font-xm cus-table-strip" border="0" cellspacing="0" cellpadding="0">
                <thead>
                  <tr class="cus-bg-dark cus-text-light">
                    <!-- Client name -->
                    <th class="cus-w-25 cus-p-1 cus-text-left">
                      <p v-if="formData.client_name" class="cus-bold">{{ $t('Name') }}: {{
                        formData.client_name }}</p>
                    </th>

                    <!-- Client contact -->
                    <th class="cus-w-25 cus-p-1 cus-text-center">
                      <p v-if="formData.client_number">{{ $t('contact') }}: {{
                        formData.client_number }}</p>
                    </th>

                    <!-- Invoice number -->
                    <th class="cus-w-25 cus-p-1 cus-text-center">
                    <td class="cus-bold">{{ $t('invoice_no') }}</td>
                    <td>:</td>
                    <td class="cus-text-right">{{ formData.is_from_estimate ? 'EST-' : '' }}{{
                      formData.invoice_number }}</td>
                    </th>

                    <!-- Invoice date -->
<<<<<<< Updated upstream
                    <th class="cus-w-25 cus-text-right">
=======
                    <th class="cus-w-25 cus-p-1 cus-text-right">
>>>>>>> Stashed changes
                    <td class="cus-bold cus-text-right">{{ $t('date') }}</td>
                    <td>:</td>
                    <td class="cus-text-right">{{ formatDateToLocal(formData.date) }}</td>
                    </th>
                  </tr>
                </thead>
              </table>

            </div>
            <div class="cus-f-clear"></div>
            <!-- Invoice  Address details -->
            <div class="cus-invoice_container__item cus-px-5">
              <table class="cus-w-100 cus-font-xm cus-table-strip" cellspacing="1" cellpadding="0">
                <template v-if="formData.from_address && formData.to_address">
                  <tr class="cus-bg-transparent">
                    <!-- From Address Column -->
                    <td class="cus-p-1 cus-text-left" style="width: 50%;" colspan="1">
                      <p class="cus-mt-6"><span class="cus-bold">{{ $t('from_address') }}:</span> <span
                          v-html="formData.from_address"></span>
                      </p>
                      <p class="cus-mt-3">
                        <span class="cus-bold">{{ $t('Lift') }}:</span>
                        <span v-if="formData.lift_from_address === true">Available</span>
                        <span v-else-if="formData.lift_from_address === false">N/A</span>
                      </p>
                      <p class="cus-mt-3"><span class="cus-bold">{{ $t('Floor') }}:</span><span
                          v-html="formData.floor_from_address"></span></p>
                    </td>

                    <!-- To Address Column -->
                    <td class="cus-p-1 cus-text-left" style="width: 50%;" colspan="1">
                      <p class="cus-mt-6"> <span class="cus-bold">{{ $t('to_address') }}:</span><span
                          v-html="formData.to_address"></span>
                      </p>
                      <p class="cus-mt-3">
                        <span class="cus-bold">{{ $t('Lift') }}:</span>
                        <span v-if="formData.lift_to_address === true">Available</span>
                        <span v-else-if="formData.lift_to_address === false">N/A</span>
                      </p>
                      <p class="cus-mt-3"> <span class="cus-bold">{{ $t('Floor') }}:</span><span
                          v-html="formData.floor_to_address"></span></p>
                    </td>

                  </tr>
<<<<<<< Updated upstream
                </template>
=======

                </template>

>>>>>>> Stashed changes
              </table>
              <div class="cus-hr cus-mt-2"></div>
            </div>

            <div class="cus-f-clear"></div>

            <!-- Invoice Thank you message -->
            <div class="cus-invoice_container__item cus-p-5">
              <div class="cus-w-100 cus-f-left">
                <div class="cus-w-100 cus-f-left">
                  <p class="cus-bold">{{ $t('Dear ') }}{{ formData.client_name
                  }},</p>
                  <p>Thank you for choosing our services. We greatly appreciate your business and the
                    trust you have placed in us. We are committed to providing you with exceptional
                    services.</p>
                  <p><br></p>
                  <p class="cus-bold"> Please find the details of your invoice below:</p>
                </div>
                <div class="cus-f-clear"></div>
              </div>
              <div class="cus-f-clear"></div>
            </div>

<<<<<<< Updated upstream
            <!-- Invoice charges details  table -->
=======
            <!-- Invoice product detaila and charges table -->
>>>>>>> Stashed changes
            <div class="cus-invoice_container__item cus-px-5">
              <table class="cus-w-100 cus-font-xm cus-table-strip" border="0" cellspacing="0" cellpadding="0">
                <thead>
                  <tr class="cus-bg-dark cus-text-light">
<<<<<<< Updated upstream
                    <th class="cus-w-25 cus-p-1 cus-text-left">{{ $t('product') }}</th>

                    <th class="cus-w-15 cus-p-1 cus-text-right" v-if="!formData.is_breakdown">

                      {{ $t('quantity') }}
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(invoiceProduct, index) in formData.invoice_details" :key="`invoice-item-${index}`">
                    <td class="cus-p-1">{{ invoiceProduct.product_name }}</td>
                    <td class="cus-p-1 cus-text-right" v-if="!formData.is_breakdown">
                      {{ invoiceProduct.quantity }}
                    </td>
                  </tr>
                  <tr class="cus-bg-transparent">
                    <td colspan="6">
                      <div class="cus-hr cus-mt-2"></div>
                    </td>
                  </tr>
<<<<<<< Updated upstream
=======

                  <!-- Charges section (visible only when is_hide_charges is false) -->
>>>>>>> Stashed changes
                  <tr class="cus-bg-transparent" v-if="!formData.is_breakdown">
                    <td colspan="2" class="cus-bold">
                      {{ formData.is_hide_charges === 0 ? "Charges" : "" }}
                    </td>
                    <td colspan="1"></td>
                    <td colspan="2" class="cus-bold cus-p-1"></td>
                    <td class="cus-text-right cus-p-1"></td>
                  </tr>
                  <tr class="cus-bg-transparent" v-if="!formData.is_breakdown">
                    <td colspan="2" class="cus-bold">
                      {{ formData.is_hide_charges === 0 ? "Packing" : "" }}
                    </td>
                    <td colspan="1">
                      {{ formData.is_hide_charges === 0 ? numberWithCurrencySymbol(formData.packing) : "" }}
                    </td>
                    <td colspan="2" class="cus-bold cus-p-1">{{ $t('sub_total') }} :</td>
                    <td class="cus-text-right cus-p-1">{{ numberWithCurrencySymbol(formData.sub_total) }}</td>
                  </tr>
<<<<<<< Updated upstream
=======

                  <!-- Additional charges (visible only when is_hide_charges is false) -->
>>>>>>> Stashed changes
                  <tr class="cus-bg-transparent" v-if="!formData.is_breakdown">
                    <td colspan="2" class="cus-bold">
                      {{ formData.is_hide_charges === 0 ? "Transport" : "" }}
                    </td>
                    <td colspan="1">
                      {{ formData.is_hide_charges === 0 ? numberWithCurrencySymbol(formData.transport) : "" }}
                    </td>
                    <td colspan="2" class="cus-bold cus-p-1">{{ $t('discount') }} :
                      <template v-if="formData.discount_type === 'percentage'">
                        {{ formData.discount }} %
                      </template>
                    </td>
<<<<<<< Updated upstream
                    <td class="cus-text-right cus-p-1">{{
                      numberWithCurrencySymbol(formData.discount_amount) }}</td>
=======
                    <td class="cus-text-right cus-p-1">{{ numberWithCurrencySymbol(formData.discount_amount) }}</td>
>>>>>>> Stashed changes
                  </tr>
                  <tr class="cus-bg-transparent">
                    <td colspan="2" class="cus-bold">
                      {{ formData.is_hide_charges === 0 ? "Loading" : "" }}
                    </td>
                    <td colspan="1">
                      {{ formData.is_hide_charges === 0 ? numberWithCurrencySymbol(formData.loading) : "" }}
                    </td>
                    <td colspan="2" class="cus-bold p-1">{{ $t('total') }} :</td>
<<<<<<< Updated upstream
                    <td class="cus-text-right cus-p-1">{{ numberWithCurrencySymbol(formData.total) }}
                    </td>
=======
                    <td class="cus-text-right cus-p-1">{{ numberWithCurrencySymbol(formData.total) }}</td>
>>>>>>> Stashed changes
                  </tr>
                  <tr class="cus-bg-transparent">
                    <td colspan="2" class="cus-bold">
                      {{ formData.is_hide_charges === 0 ? "Unloading" : "" }}
                    </td>
                    <td colspan="1">
                      {{ formData.is_hide_charges === 0 ? numberWithCurrencySymbol(formData.unloading) : "" }}
                    </td>
                    <td colspan="2" class="cus-bold p-1">{{ $t('paid') }} :</td>
<<<<<<< Updated upstream
                    <td class="cus-text-right cus-p-1">{{
                      numberWithCurrencySymbol(formData.received_amount) }}</td>
=======
                    <td class="cus-text-right cus-p-1">{{ numberWithCurrencySymbol(formData.received_amount) }}</td>
>>>>>>> Stashed changes
                  </tr>
                  <tr class="cus-bg-transparent">
                    <td colspan="2" class="cus-bold">
                      {{ formData.is_hide_charges === 0 ? "Unpacking" : "" }}
                    </td>
                    <td colspan="1">
                      {{ formData.is_hide_charges === 0 ? numberWithCurrencySymbol(formData.unpacking) : "" }}
                    </td>
                    <td colspan="3" class="cus-bg-transparent">
                      <div class="cus-hr cus-mt-2"></div>
                    </td>
                  </tr>
                  <tr class="cus-bg-transparent">
                    <td colspan="2" class="cus-bold">
                      {{ formData.is_hide_charges === 0 ? "AC" : "" }}
                    </td>
                    <td colspan="1">
                      {{ formData.is_hide_charges === 0 ? numberWithCurrencySymbol(formData.ac) : "" }}
                    </td>
                    <td colspan="2" class="cus-p-1 cus-bold">{{ $t('due_amount') }} :</td>
<<<<<<< Updated upstream
                    <td class="cus-text-right cus-p-1 cus-bold">{{ numberWithCurrencySymbol(formData.due_amount)
                    }}</td>
=======
                    <td class="cus-text-right cus-p-1 cus-bold">{{ numberWithCurrencySymbol(formData.due_amount) }}</td>
>>>>>>> Stashed changes
                  </tr>
                  <tr class="cus-bg-transparent">
                    <td colspan="2" class="cus-bold">
                      {{ formData.is_hide_charges === 0 ? "Local" : "" }}
                    </td>
                    <td colspan="1">
                      {{ formData.is_hide_charges === 0 ? numberWithCurrencySymbol(formData.local) : "" }}
                    </td>
                    <td colspan="2" class="cus-bold p-1"></td>
                    <td class="cus-text-right cus-p-1"></td>
                  </tr>
                  <tr class="cus-bg-transparent">
                    <td colspan="2" class="cus-bold">
<<<<<<< Updated upstream
                      {{ formData.is_hide_charges === 0 ? "Carpentry" : "" }}
=======
                      {{ formData.is_hide_charges === 0 ? "Car Transport" : "" }}
>>>>>>> Stashed changes
                    </td>
                    <td colspan="1">
                      {{ formData.is_hide_charges === 0 ? numberWithCurrencySymbol(formData.car_transport) : "" }}
                    </td>
                    <td colspan="2" class="cus-bold p-1"></td>
                    <td class="cus-text-right cus-p-1"></td>
                  </tr>
                  <tr class="cus-bg-transparent">
                    <td colspan="2" class="cus-bold">
                      {{ formData.is_hide_charges === 0 ? "Insuarance" : "" }}
                    </td>
                    <td colspan="1">
                      {{ formData.is_hide_charges === 0 ? numberWithCurrencySymbol(formData.insuarance) : "" }}
                    </td>
                    <td colspan="2" class="cus-bold p-1"></td>
                    <td class="cus-text-right cus-p-1"></td>
                  </tr>
                  <tr class="cus-bg-transparent">
                    <td colspan="2" class="cus-bold">
                      {{ formData.is_hide_charges === 0 ? "GST" : "" }}
                    </td>
                    <td colspan="1">
                      {{ formData.is_hide_charges === 0 ? numberWithCurrencySymbol(formData.gst) : "" }}
                    </td>
                    <td colspan="2" class="cus-bold p-1"></td>
                    <td class="cus-text-right cus-p-1"></td>
                  </tr>
                </tbody>
              </table>
            </div>

<<<<<<< Updated upstream
=======

>>>>>>> Stashed changes
            <!-- Invoice notes -->
            <div class="cus-invoice_container__item cus-p-5">
              <template v-if="formData.notes">
                <div class="cus-bold">{{ $t('notes') }}</div>
                <p v-html="formData.notes"></p>
              </template>

              <template v-if="formData.terms">
                <div class="cus-bold cus-mt-3">{{ $t('terms') }}</div>
                <p v-html="formData.terms"></p>
              </template>

            </div>
          </div>
        </div>
      </div>


      <send-invoice-modal v-if="isSendInvoiceModalActive" :email="formData.client.email" :invoice-information="formData"
        @closeModal="closeSendInvoiceModal" />

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
  name: "InvoiceDetails",
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
      isSendInvoiceModalActive: false,
      isInvoiceAddEditModalActive: false,
      productList: [],
      selectUrl: "",
      formData: {},
    };
  },

  computed: {
    totalTax() {
      let totalTaxes = 0;
      this.formData.invoice_details.map((item) => {
        if (item.tax) {
          totalTaxes =
            totalTaxes + (item.price * item.quantity * item.tax.value) / 100;
        } else {
          totalTaxes = totalTaxes + 0;
        }
      });
      return totalTaxes;
    },
  },
  // The above code is a Vue.js component's methods section that contains various functions.
  methods: {
    calculateProductTax(tax, totalPrice) {
      console.log("lift" + this.formData.lift);
      console.log("floor" + this.formData.floor);
      let taxes = 0;
      taxes = (totalPrice * tax) / 100;
      return taxes;
    },
    afterSuccessFromGetEditData(response) {
      this.formData = response.data;
      console.log("Data: ", response.data);
      this.dataLoaded = true;
    },
    downloadInvoice() {
      window.open(
        AppFunction.getAppUrl(`invoice-download/${this.formData.id}`)
      );
    },
    openSendInvoiceModal() {
      this.isSendInvoiceModalActive = true;
    },
    closeSendInvoiceModal() {
      this.isSendInvoiceModalActive = false;
      $("#send-invoice-modal").modal("hide");
    },

    invoicePackages(value) {
      let returnedValue;
      returnedValue = "";
      if (value === 1) {
        returnedValue = "None";
      } else if (value === 2) {
        returnedValue = "Bubble";
      } else if (value === 3) {
        returnedValue = "Corrugated";
      } else if (value === 4) {
        returnedValue = "Packing";
      } else {
        returnedValue = "Foam";
      }
      return returnedValue;
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

.cus-table-strip {}

.cus-table-strip tr:nth-child(even) {
  background-color: #66666610;
}

/*layout*/
.cus-invoice_container {}

.cus-invoice_container * {
  box-sizing: content-box;
}

.cus-invoice_container__item {}

.cus-logo {
  width: 96px;
}

.cus-hr {
  background-color: #999999;
  border: none;
  height: 1px;
}
</style>
