<template>
  <div class="content-wrapper">
    <div class="d-flex align-items-center justify-content-between">
      <app-breadcrumb
        :page-title="selectedUrl ? $t('update_invoice') : $t('add_invoice')"
      />
    </div>
    <app-overlay-loader v-if="preloader" />
    <form
      v-else
      :data-url="selectedUrl ? selectedUrl : INVOICES"
      ref="form"
      class="d-flex flex-column"
      style="gap: 25px"
    >
      <!--Client details and date form with invoice number  hidden-->
      <div class="row">
        <div class="col">
          <div
            class="card border-0 card-with-shadow"
            style="margin-bottom: 20px"
          >
            <div
              class="card-body"
              style="
                border-bottom: 2px solid red;
                box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
                padding-top: 1px;
              "
            >
              <div class="row" style="font-weight: bold">
                <div class="col-md-12" >
                  <div>
                    
                    <h5 class="mb-3 pb-2 border-bottom">
                      {{ "Client details" }}
                    </h5>
                    <!-- Hidden Invoice number-->
                    <div class="col-12 col-md-3 mb-4" :hidden="true">
                      <label>{{ $t("invoice_number") }}</label>
                      <app-input
                        class="margin-right-8"
                        v-model="formData.invoice_number"
                        :disabled="true"
                        :error-message="$errorMessage(errors, 'invoice_number')"
                        :placeholder="$t('invoice_number')"
                        type="text"
                      />
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md-5 mb-4">
                  <label>{{ $t("client_name") }}</label>
                  <app-input
                    class="margin-right-8"
                    v-model="formData.client_name"
                    :error-message="$errorMessage(errors, 'client_name')"
                    :placeholder="$t('Name')"
                    type="text"
                  />
                </div>
                <div class="col-12 col-md-4 mb-4">
                  <label>{{ $t("Email") }}</label>
                  <app-input
                    class="margin-right-8"
                    v-model="formData.client_email"
                    :error-message="$errorMessage(errors, 'client_email')"
                    :placeholder="$t('Email')"
                    type="text"
                  />
                </div>
                <div class="col-12 col-md-3 mb-4">
                  <label>{{ $t("client_number") }}</label>
                  <app-input
                    type="text"
                    class="margin-right-8"
                    v-model="formData.client_number"
                    :error-message="$errorMessage(errors, 'client_number')"
                    :placeholder="$t('Number')"
                    @input="handleClientNumber"
                  />
                </div>
              </div>

              <div class="row" style="font-weight: bold">
                <div class="col-12 col-md-4 mb-4">
                  <label>{{ $t("issue_date") }}</label>
                  <app-input
                    id="date"
                    v-model="formData.date"
                    :error-message="$errorMessage(errors, 'date')"
                    type="date"
                    required
                  />
                </div>

                <div class="col-12 col-md-4 mb-4">
                  <label>Time</label>

                  <app-input id="time" v-model="timeOnly" type="time" />
                </div>
                <div class="col-12 col-md-4 mb-4">
                  <label>Packaging</label>
                  <app-input
                    class="margin-right-8"
                    id="packaging_type"
                    v-model="formData.packaging_type"
                    :list="packagingTypeList"
                    :error-message="$errorMessage(errors, 'packaging_type')"
                    list-value-field="name"
                    type="select"
                  />
                </div>
              </div>
            </div>
          </div>
          <div
            class="card border-0 card-with-shadow"
            style="margin-bottom: 20px"
          >
            <div
              class="card-body"
              style="
                border-bottom: 2px solid red;
                box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
                padding-top: 1px;
              "
            >
              <h5 class="mb-3 pb-2 border-bottom">
                {{ "Origin and Destination" }}
              </h5>

              <div class="row">
                <!-- From Address Section -->
                <div class="col-12 col-md-6 mb-4" style="font-weight: bold">
                  <div class="row">
                    <div class="col-12">
                      <label>{{ $t("from_address") }}</label>
                    </div>
                    <div class="col-12 mb-4">
                      <app-input
                        class="margin-right-8"
                        v-model="formData.from_address"
                        :error-message="$errorMessage(errors, 'from_address')"
                        :placeholder="$t('from_address_place_holder')"
                        type="textarea"
                      />
                    </div>
                    <div class="col-12 col-md-6 mb-4">
                      <label>{{ $t("floor") }}</label>
                      <app-input
                        class="margin-right-8"
                        v-model="formData.floor_from_address"
                        :placeholder="$t('floor_place_holder')"
                        :error-message="
                          $errorMessage(errors, 'floor_from_address')
                        "
                        type="number"
                      />
                    </div>
                    <div class="col-12 col-md-6 mb-4 mt-3">
                      <label>{{ $t("lift") }}</label>
                      <app-input
                        class="margin-right-8"
                        v-model="formData.lift_from_address"
                        :error-message="
                          $errorMessage(errors, 'lift_from_address')
                        "
                        :placeholder="$t('lift_place_holder')"
                        type="switch"
                      />
                    </div>
                  </div>
                </div>

                <!-- To Address Section -->
                <div class="col-12 col-md-6 mb-4" style="font-weight: bold">
                  <div class="row">
                    <div class="col-12">
                      <label>{{ $t("to_address") }}</label>
                    </div>
                    <div class="col-12 mb-4">
                      <app-input
                        class="margin-right-8"
                        v-model="formData.to_address"
                        :error-message="$errorMessage(errors, 'to_address')"
                        :placeholder="$t('to_address_place_holder')"
                        type="textarea"
                      />
                    </div>
                    <div class="col-12 col-md-6 mb-4">
                      <label>{{ $t("floor") }}</label>
                      <app-input
                        class="margin-right-8"
                        v-model="formData.floor_to_address"
                        :error-message="
                          $errorMessage(errors, 'floor_to_address')
                        "
                        :placeholder="$t('floor_place_holder')"
                        type="number"
                      />
                    </div>
                    <div class="col-12 col-md-6 mb-4 mt-3">
                      <label>{{ $t("lift") }}</label>
                      <app-input
                        class="margin-right-8"
                        v-model="formData.lift_to_address"
                        :error-message="
                          $errorMessage(errors, 'lift_to_address')
                        "
                        :placeholder="$t('lift_place_holder')"
                        type="switch"
                      />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Inventory List -->
      <div class="row border-bottom mt-2 mb-3" style="font-weight: bold">
        <div class="col-12">
          <div
            class="card border-0 card-with-shadow"
            style="margin-bottom: 20px"
          >
            <div
              class="card-body"
              style="
                border-bottom: 2px solid red;
                box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
                padding-top: 1px;
              "
            >
              <h5 class="mb-3 pb-2 border-bottom">
                {{ "Inventory List" }}
              </h5>

              <span
                v-if="$errorMessage(errors, 'products')"
                class="text-danger validation-error"
              >
                {{ $errorMessage(errors, "products") }}
              </span>

              <div v-if="tempSelectedProducts">
                <div class="accordion">
                  <div
                    v-for="(category, index) in tempSelectedProducts"
                    :key="index"
                    class="option"
                  >
                    <input
                      type="checkbox"
                      :id="'toggle' + index"
                      class="toggle"
                    />
                    <label class="title" :for="'toggle' + index">
                      {{ category.name }}
                    </label>
                    <div class="content">
                      <span
                        v-if="category.products.length > 0"
                        class="font-weight-bold edit-icon"
                        @click="handleEditCategoryAndProducts(category.name)"
                      >
                        <app-icon name="edit" class="menu-icon ml-3" />
                      </span>
                      <span
                        v-else
                        class="font-weight-bold plus-icon"
                        @click="handleEditCategoryAndProducts(category.name)"
                      >
                        <app-icon name="plus" class="menu-icon ml-3" />
                      </span>

                      <ul
                        class=""
                        style="list-style-type: none; padding-left: 0"
                      >
                        <li
                          v-for="(product, index) in category.products"
                          :key="`selected-product-item-${index}`"
                          class="mb-1"
                        >
                          {{ product.name }}:
                          <span>
                            {{ product.quantity }}
                          </span>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <div></div>
              </div>
            </div>
          </div>
          <!-- Charges breakdown with hiding charges functionalty-->
          <div
            class="card border-0 card-with-shadow"
            style="margin-bottom: 20px"
          >
            <div
              class="card-body"
              style="
                border-bottom: 2px solid red;
                box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
                padding-top: 1px;
              "
            >
              <div class="row border-bottom mt-2 mb-3">
                <div class="col-6">
                  <h5>Payment Breakdown</h5>
                </div>
                <div
                  class="col-6 d-flex align-items-center justify-content-end"
                >
                  <label class="mr-2">Hide Breakdown</label>
                  <app-input
                    class="margin-right-8"
                    style="display: inline-block"
                    v-model="formData.is_hide_charges"
                    :placeholder="$t('text_hide_break_down')"
                    :error-message="$errorMessage(errors, 'is_hide_charges')"
                    type="switch"
                  />
                </div>
              </div>

              <div class="row">
                <div class="col-6 col-md-3">
                  <div class="mb-3">
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
                  
                  <div class="mb-3">
                    <label>Local</label>
                    <app-input
                      class="margin-right-8"
                      v-model="formData.local"
                      :error-message="$errorMessage(errors, 'local')"
                      type="number"
                      @input="calculateSubTotal"
                    />
                  </div>
                  <div class="mb-3">
                    <label>GST</label>
                    <app-input
                      class="margin-right-8"
                      v-model="formData.gst"
                      :error-message="$errorMessage(errors, 'gst')"
                      type="number"
                      @input="calculateSubTotal"
                    />
                  </div>
                </div>
                <div class="col-6 col-md-3">
                  <div class="mb-3">
                    <label>Unpacking</label>
                    <app-input
                      class="margin-right-8"
                      v-model="formData.unpacking"
                      :error-message="$errorMessage(errors, 'unpacking')"
                      type="number"
                      @input="calculateSubTotal"
                    />
                  </div>
                  <div class="mb-3">
                    <label>Transport</label>
                    <app-input
                      class="margin-right-8"
                      v-model="formData.transport"
                      :error-message="$errorMessage(errors, 'transport')"
                      type="number"
                      @input="calculateSubTotal"
                    />
                  </div>
                  

                  <div class="mb-3">
                    <label>Insurance</label>
                    <app-input
                      class="margin-right-8"
                      v-model="formData.insuarance"
                      :error-message="$errorMessage(errors, 'insuarance')"
                      type="number"
                      @input="calculateSubTotal"
                    />
                  </div>
                </div>
                <div class="col-6 col-md-3">
                  <div class="mb-3">
                    <label>Loading</label>
                    <app-input
                      class="margin-right-8"
                      v-model="formData.loading"
                      :error-message="$errorMessage(errors, 'loading')"
                      type="number"
                      @input="calculateSubTotal"
                    />
                  </div>
                  <div class="mb-3">
                    <label>AC</label>
                    <app-input
                      class="margin-right-8"
                      v-model="formData.ac"
                      :error-message="$errorMessage(errors, 'ac')"
                      type="number"
                      @input="calculateSubTotal"
                    />
                  </div>
                </div>
                <div class="col-6 col-md-3">
                  <div class="mb-3">
                    <label>Unloading</label>
                    <app-input
                      class="margin-right-8"
                      v-model="formData.unloading"
                      :error-message="$errorMessage(errors, 'unloading')"
                      type="number"
                      @input="calculateSubTotal"
                    />
                  </div>
                  <div class="mb-3">
                    <label>Carpentry</label>
                    <app-input
                      class="margin-right-8"
                      v-model="formData.car_transport"
                      :error-message="$errorMessage(errors, 'car_transport')"
                      type="number"
                      @input="calculateSubTotal"
                    />
                  </div>
                  <div class="mb-3">
                    <label>Sub Total</label>
                    <app-input
                      class="margin-right-8"
                      v-model="formData.sub_total"
                      :error-message="$errorMessage(errors, 'sub_total')"
                      disabled
                      type="number"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Notes , Terms, Payment summary card  and discount calculations -->
      <div class="row" style="font-weight: bold">
        <div class="col-12 col-md-6 col-lg-9">
          <!-- other information card -->
          <div class="card border-0 card-with-shadow">
            <div class="card-body">
              <h5 class="mb-3 border-bottom pb-2">
                {{ $t("other_information") }}
              </h5>
              <div class="note note-warning px-4 py-2 mb-4">
                {{ $t("discount_will_be_applicable_on_subtotal_amount") }}
              </div>
              <div class="row mb-4">
                <div class="col">
                  <label>{{ $t("discount_type") }}</label>
                  <app-input
                    class="margin-right-8"
                    id="discount_type"
                    v-model="formData.discount_type"
                    :list="discountTypeList"
                    :error-message="$errorMessage(errors, 'discount_type')"
                    list-value-field="name"
                    type="select"
                  />
                </div>
                <div class="col">
                  <label>{{ $t("discount") }}</label>
                  <app-input
                    class="margin-right-8"
                    type="number"
                    v-model="formData.discount"
                    :error-message="$errorMessage(errors, 'discount')"
                    :disabled="formData.discount_type === 'none'"
                  />
                </div>
              </div>
              <div class="row mb-4">
                <div class="col">
                  <label>{{ $t("notes") }}</label>
                  <app-input
                    type="textarea"
                    v-model="formData.notes"
                    :error-message="$errorMessage(errors, 'notes')"
                  />
                </div>
              </div>
              <div class="row mb-4">
                <div class="col">
                  <label>{{ $t("terms") }}</label>
                  <app-input
                    type="textarea"
                    v-model="formData.terms"
                    :error-message="$errorMessage(errors, 'terms')"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col">
          <!-- Payment summary card  -->
          <div class="card border-0 card-with-shadow h-100">
            <div class="card-body">
              <h5 class="mb-3 pb-2 border-bottom">
                {{ $t("payment_summary") }}
              </h5>
              <div class="row">
                <div class="col">
                  <table class="w-100" style="color: var(--default-font-color)">
                    <tbody>
                      <tr>
                        <td colspan="2" class="p-1"></td>
                      </tr>
                      <tr>
                        <td colspan="2" class="p-1"></td>
                      </tr>
                      <tr>
                        <td>
                          <strong
                            class="label"
                            style="text-transform: uppercase"
                            >{{ $t("total") }}:</strong
                          >
                        </td>
                        <td class="text-right">
                          <strong>{{
                            numberWithCurrencySymbol(totalAmount)
                          }}</strong>
                        </td>
                      </tr>

                      <tr>
                        <td>
                          <strong> <span class="label " style="color: green;">
                            <!-- {{ $t("received_amount") }}: -->
                            ADVANCE PAYMENT:
                          </span> </strong>
                        </td>
                        <td>
                          <app-input
                            inputClass="text-right"
                            type="text"
                            v-model="formData.received_amount"
                            :error-message="
                              $errorMessage(errors, 'received_amount')
                            "
                          />
                        </td>
                      </tr>

                      <tr>
                        <td>
                          <span class="label">(-) {{ $t("discount") }}:</span>
                        </td>
                        <td class="text-right">
                          <span>
                            {{
                              numberWithCurrencySymbol(totalDiscountAmount)
                            }}</span
                          >
                        </td>
                      </tr>

                      <tr>
                        <td>
                          <span class="label">
                            {{ $t("return_amount") }}:
                          </span>
                        </td>
                        <td class="text-right">
                          <span>{{
                            numberWithCurrencySymbol(returnAmount)
                          }}</span>
                        </td>
                      </tr>

                      <tr>
                        <td>
                          <span class="label">
                            <!-- {{ $t("due_amount") }}: -->
                            Pending amount:
                          </span>
                        </td>
                        <td class="text-right">
                          <span>{{ numberWithCurrencySymbol(deuAmount) }}</span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col mt-5">
          <button class="btn btn-secondary" @click.prevent="resetData">
            {{ $t("cancel") }}
          </button>
          <button class="btn btn-primary ml-2" @click.prevent="submitData">
            {{ $t("save") }}
          </button>
        </div>
      </div>
    </form>

    <category-products-modal
      v-if="isCategoryProductsModalActive && selectCategoryId"
      :modal-id="categoryProductsModalId"
      :category-id="selectCategoryId"
      :category-name="selectCategoryName"
      :edit-products="editCategoryProductsOpenModal"
      :edit-products-old="selectedProductsDetails"
      @receiveProductListEvent="handleSelectedProductsList"
      @closeModal="closeCategoryProductsModal"
    />

    <product-add-edit-modal
      v-if="isProductModalActive"
      table-id="product-table"
      type="invoice"
      @closeModal="closeProductModal"
    />

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
import { SubmitFormMixins } from "../../../Mixins/billar/SubmitFormMixins";
import {
  axiosGet,
  axiosPost,
  urlGenerator,
} from "../../../Helpers/AxiosHelper";
import DateFunction from "../../../../core/helpers/date/DateFunction";
import { numberWithCurrencySymbol } from "../../../Helpers/Helpers";
import { formatDateForServer } from "../../../Helpers/DateTimeHelper";
import { INVOICES, INVOICES_LIST } from "../../../Config/BillarApiUrl";

export default {
  name: "InvoiceCreateEdit",
  mixins: [SubmitFormMixins],
  props: {
    statusList: {},
    recurringCycle: {},
    taxList: {},
    terms: {},
  },
  data() {
    return {
      error: undefined,
      numberWithCurrencySymbol,
      categoryProductsModalId: "category-products-modal",
      INVOICES,
      selectCategoryId: null,
      selectCategoryName: null,
      selectProductId: null,
      invoiceProductDeleteModal: false,
      deletedInvoiceProductContext: null,
      isCategoryProductsModalActive: false,
      editCategoryProductsOpenModal: false,
      // isClientModalActive: false,
      isProductModalActive: false,
      productData: [],
      productDetails: [],
      categoryData: [],
      tempSelectedProducts: [],
      groupedProducts: [],
      selectedProductsDetails: null,
      products: [
        {
          name: "Electronics",
          products: [
            {
              product_id: 1,
              name: "Fridge",
              quantity: 2,
            },
            {
              product_id: 2,
              name: "TV",
              quantity: 1,
            },
          ],
        },
      ],
      timeOnly: "06:00 AM",
      formData: {
        client_name: "",
        client_email: "",
        country_code: "+91",
        client_number: "",
        recurring: 2,
        invoice_number: null,
        date: DateFunction.getDateFormat(new Date(), "YYYY-MM-DD"),
        time: "06:00 AM",
        packaging_type: "none",
        discount_type: "none",
        discount: null,
        received_amount: null,
        discount_amount: 0,
        from_address: null,
        to_address: null,
        lift_from_address: false,
        lift_to_address: false,
        floor_from_address: null,
        floor_to_address: null,
        terms: "",
        is_hide_charges: false,
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
        sub_total: null,
      },
      packagingTypeList: [
        { id: "none", name: "Choose packaing type" },
        { id: "regular", name: "Regular" },
        { id: "premium", name: "Premium" },
      ],
      recurringList: [
        { id: 1, name: "Yes" },
        { id: 2, name: "No" },
      ],
      discountTypeList: [
        { id: "none", name: this.$t("discount_type") },
        { id: "fixed", name: this.$t("fixed") },
        { id: "percentage", name: this.$t("percentage") },
      ],
    };
  },

  computed: {
    productDetailsCalculated() {
      if (!this.productDetails.length) return [];
      return this.productDetails.map((product) => {
        const productTaxPercentage = this.taxList.find(
          (tax) => +tax.id === +product.tax_id
        );
        let productTaxValue = productTaxPercentage
          ? +product.price * (+productTaxPercentage.value / 100)
          : 0;
        const productTotalAmount =
          (+product.price + productTaxValue) * product.quantity;
        return { ...product, total_amount: productTotalAmount };
      });
    },

    totalDiscountAmount() {
      if (
        this.formData.discount_type === "fixed" &&
        this.formData.discount > 0
      ) {
        this.formData.discount_amount = this.formData.discount;
        return parseFloat(this.formData.discount);
      } else if (
        this.formData.discount_type === "percentage" &&
        this.formData.discount > 0
      ) {
        this.formData.discount_amount = Number(
          (this.formData.sub_total * this.formData.discount) / 100
        );
        return Number((this.formData.sub_total * this.formData.discount) / 100);
      } else if (this.formData.discount_type === "none") {
        return (this.formData.discount = null);
      }
      return 0;
    },

    totalAmount() {
      return this.computeTotal();
    },

    returnAmount() {
      if (this.totalAmount > this.formData.received_amount) {
        return 0;
      }
      return this.formData.received_amount - this.totalAmount;
    },
    deuAmount() {
      if (this.totalAmount < this.formData.received_amount) {
        return 0;
      }
      return this.totalAmount - this.formData.received_amount;
    },
  },
  methods: {
    handleClientNumber(inputValue) {
      if (!inputValue.startsWith(this.formData.country_code)) {
        this.formData.client_number = this.formData.country_code + inputValue;
      }
    },
    changeCategory() {
      const selectedCategory = this.categoryData.find(
        (product) => product.id === this.selectCategoryId
      );
      this.selectCategoryName = selectedCategory?.name;
      this.editCategoryProductsOpenModal = false;
      this.isCategoryProductsModalActive = true;
    },
    checkAndReplaceData(array, name, newProducts) {
      const updatedArray = array.map((item) => {
        if (item.name === name) {
          return { ...item, products: newProducts };
        }
        return item;
      });
      return updatedArray;
    },
    updateCategoryProductsByName(oldArray, newData) {
      const updatedArray = oldArray.map((category) => {
        const updatedCategory = newData.find(
          (updatedCategory) => updatedCategory.name === category.name
        );
        if (updatedCategory) {
          return { ...category, products: updatedCategory.products };
        }
        return category;
      });
      console.log("Updated array: ", updatedArray);
      return updatedArray;
    },
    handleSelectedProductsList(products) {
      this.editCategoryProductsOpenModal = false;
      const categoryAndProducts = {
        name: this.selectCategoryName,
        products,
      };
      const categoryIndex = this.tempSelectedProducts.findIndex(
        (category) => category.name === this.selectCategoryName
      );
      if (categoryIndex !== -1) {
        const updatedProducts = this.checkAndReplaceData(
          this.tempSelectedProducts,
          this.selectCategoryName,
          products
        );
        this.tempSelectedProducts = updatedProducts;
      } else {
        this.tempSelectedProducts.push(categoryAndProducts);
      }
      this.selectedProductsDetails = products;
      console.log("tempSelectedProducts: ", this.tempSelectedProducts);
    },
    handleEditCategoryAndProducts(name) {
      const selectedCategory = this.categoryData.find(
        (category) => category.name === name
      );
      const selectedProductDetails = this.tempSelectedProducts.find(
        (category) => category.name === name
      );
      this.selectCategoryId = selectedCategory.id;
      this.selectCategoryName = selectedCategory.name;
      this.selectedProductsDetails = selectedProductDetails.products;
      this.editCategoryProductsOpenModal = true;
      this.isCategoryProductsModalActive = true;
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
    computeTotal() {
      return this.formData.sub_total - this.totalDiscountAmount;
    },
    afterError({ data }) {
      this.errors = data.errors;
      if (this.productDetails.length < 1) {
        this.$toastr.e(this.$t("your_cart_is_empty"));
      }
    },
    convertTo12Hour(time24hr) {
      const regexPattern = /^(?:[01]\d|2[0-3]):(?:[0-5]\d):(?:[0-5]\d)$/;
      if (!regexPattern.test(time24hr)) {
        return time24hr;
      }
      const [hours, minutes, seconds] = time24hr.split(":");
      let hours12hr = (parseInt(hours) % 12).toString();
      if (hours12hr === "0") {
        hours12hr = "12";
      }
      const period = parseInt(hours) < 12 ? "AM" : "PM";
      const time12hr = `${hours12hr}:${minutes} ${period}`;
      return time12hr;
    },
    generateDateAndTime(dateStr, timeStr) {
      const _timeStr = this.convertTo12Hour(timeStr);
      const dateObj = new Date(dateStr);
      const [time, period] = _timeStr.split(" ");
      let [hours, minutes] = time.split(":");
      if (period === "PM") {
        hours = String(Number(hours) + 12);
      }
      dateObj.setUTCHours(hours);
      dateObj.setUTCMinutes(minutes);
      const year = dateObj.getUTCFullYear();
      const month = String(dateObj.getUTCMonth() + 1).padStart(2, "0");
      const day = String(dateObj.getUTCDate()).padStart(2, "0");
      const formattedTime = `${hours}:${minutes}:00`;
      const datetimeValue = `${year}-${month}-${day} ${formattedTime}`;
      return datetimeValue;
    },
    confirmDeleteInvoiceProduct() {
      axiosPost(
        this.deletedInvoiceProductContext.payload.url,
        this.deletedInvoiceProductContext.payload.data
      ).then((response) => {
        this.deletedInvoiceProductContext.callBack();
        this.$toastr.s(response.data.message);
        this.deletedInvoiceProductContext = null;
        this.closeInvoiceDeleteModal();
      });
    },
    closeInvoiceDeleteModal() {
      $(`#invoice-product-delete-confirm-modal`).modal("hide");
      this.invoiceProductDeleteModal = false;
    },
    closeCategoryProductsModal() {
      this.isCategoryProductsModalActive = false;
    },
    closeProductModal() {
      this.isProductModalActive = false;
    },
    getCategories() {
      axiosGet("all-category?search=&page=1&per_page=10").then((response) => {
        const categories = response.data.data;
        const tempSelectedProducts = categories.map((category) => ({
          name: category.name,
          products: [],
        }));
        this.categoryData = categories;
        this.tempSelectedProducts = this.updateCategoryProductsByName(
          tempSelectedProducts,
          this.groupedProducts
        );
      });
    },
    afterSuccess({ data }) {
      this.$toastr.s(data.message);
      setTimeout(() => {
        window.location = urlGenerator(INVOICES_LIST);
      });
    },
    afterSuccessFromGetEditData(response) {
      console.log("After success from edit: ", response);
      if (response) {
        console.log("Response loaded: ", response.data);
        this.formData = response.data;
        this.formData.country_code = "+91";
        console.log("Data: ", this.formData);
        const timeValue = this.convertTo12Hour(
          DateFunction.getDateTimeFormatForBackend(
            new Date(response.data.date)
          ).split(" ")[1]
        );
        this.timeOnly = timeValue;
        const groupedProducts = response.data.invoice_details.reduce(
          (groups, item) => {
            const { category_name, product_id, product_name, quantity } = item;
            if (!groups[category_name]) {
              groups[category_name] = {
                name: category_name,
                products: [],
              };
            }
            groups[category_name].products.push({
              product_id,
              name: product_name,
              quantity,
            });
            return groups;
          },
          {}
        );
        this.groupedProducts = Object.values(groupedProducts);
        this.productDetails = this.formData.invoice_details.map((item) => ({
          id: item.id,
          product_id: item.product_id,
          name: item.product?.name,
          quantity: item.quantity,
          price: item.price,
          tax_id: item.tax ? item.tax_id : null,
          amount: item.quantity * item.price,
          packages: item.packages,
        }));
        this.preloader = false;
      }
    },
    submitData() {
      let products = this.tempSelectedProducts.flatMap((obj) => obj.products);
      if (this.selectedUrl) {
        products = products.map((item) => {
          const matchingItem = this.formData.invoice_details.find(
            (a) => a.product_id === item.product_id
          );
          if (matchingItem) {
            return { ...item, id: matchingItem.id };
          }
          return item;
        });
      }
      const date = this.generateDateAndTime(this.formData.date, this.timeOnly);
      const formData = {
        ...this.formData,
        date,
        discount: this.formData.discount || 0,
        sub_total: this.formData.sub_total,
        total: this.totalAmount,
        received_amount: this.formData.received_amount
          ? this.formData.received_amount - this.returnAmount
          : 0,
        due_amount: this.deuAmount,
        products,
      };
      formData.recurring_cycle_id = null;
      delete formData.time;
      console.log("Submit: ", this.formData.time, formData);
      this.save(formData);
    },
    resetData() {
      window.location = urlGenerator(INVOICES_LIST);
    },
    getInvoiceCount() {
      this.axiosGet("invoice-number").then((response) => {
        const invoice = response.data.id !== undefined ? response.data.id : 0;
        this.formData.invoice_number =
          Number(invoice) + Number(window.settings.invoice_starting_number);
      });
    },
  },
  mounted() {
    this.getCategories();
    if (!this.selectedUrl) {
      this.editorShow = true;

      this.getInvoiceCount();
    }

    if (this.terms && !this.terms.error) {
      this.formData.terms = this.terms.value;
    }
  },
};
</script>

<style scoped lang="scss">
.table {
  &__item {
    &--products {
      width: 30%;
    }

    &--quantity {
      width: 20%;
    }

    &--unit_price {
      width: 15%;
    }

    &--tax {
      width: 15%;
    }

    &--total_amount {
      width: 15%;
    }

    &--total_action {
      width: 5%;
    }
  }
}

.accordion {
  // margin: 3em auto;
  // max-width: 30em;
}

.accordion ul {
  margin-bottom: 0;
  // margin-top: 10px;
  // margin-left: 10px;
}

.toggle {
  display: none;
}

.option {
  position: relative;
  margin-bottom: 1em;

  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.05);
}

.title,
.content {
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  transform: translateZ(0);
  transition: all 0.2s;
}

.content {
  // padding: 1rem;
  position: relative;
}

.content .edit-icon,
.content .plus-icon {
  position: absolute;
  right: 10px;
}

.content .edit-icon:hover {
  cursor: pointer;
}

.title {
  background: #fff;
  padding: 1em;
  display: block;
  color: #7a7572;
  font-weight: bold;

  padding-bottom: 0;
}

.title:after,
.title:before {
  content: "";
  position: absolute;
  right: 1.25em;
  top: 1.25em;
  width: 2px;
  height: 0.75em;
  background-color: #7a7572;
  transition: all 0.2s;
}

.title:after {
  transform: rotate(90deg);
}

.content {
  max-height: 0;
  overflow: hidden;
  background-color: #fff;
}

.content p {
  margin: 0;
  padding: 0.5em 1em 1em;
  font-size: 0.9em;
  line-height: 1.5;
}

.toggle:checked + .title {
  padding-bottom: 10px;
}

.toggle:checked + .title,
.toggle:checked + .title + .content {
  box-shadow: 3px 3px 6px #ddd, -3px 3px 6px #ddd;
}

.toggle:checked + .title + .content {
  min-height: 60px;
  max-height: 500px;
  padding: 1rem;
}

.toggle:checked + .title:before {
  transform: rotate(90deg) !important;
}
</style>