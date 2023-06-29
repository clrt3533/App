<template>
  <div class="content-wrapper">
    <div class="d-flex align-items-center justify-content-between">
      <app-breadcrumb :page-title="selectedUrl ? $t('update_invoice') : $t('add_invoice')" />
    </div>
    <app-overlay-loader v-if="preloader" />
    <form v-else :data-url="selectedUrl ? selectedUrl : INVOICES" ref="form" class="d-flex flex-column" style="gap: 25px">
      <div class="row">
        <div class="col">
          <div class="card border-0 card-with-shadow">
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  <div>
                    <div class="note note-warning p-4 mb-primary clearfix">
                      <p class="m-1">{{ $t("setup_email_address") }}</p>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md-5 mb-4">
                  <label>{{ $t("client_name") }}</label>
                  <app-input class="margin-right-8" v-model="formData.client_name"
                    :error-message="$errorMessage(errors, 'client_name')" :placeholder="$t('Name')" type="text" />
                </div>
                <div class="col-12 col-md-4 mb-4">
                  <label>{{ $t("client_email") }}</label>
                  <app-input class="margin-right-8" v-model="formData.client_email"
                    :error-message="$errorMessage(errors, 'client_email')" :placeholder="$t('Email')" type="text" />
                </div>
                <div class="col-12 col-md-3 mb-4">
                  <label>{{ $t("client_number") }}</label>
                  <app-input type="text" class="margin-right-8" v-model="formData.client_number"
                    :error-message="$errorMessage(errors, 'client_number')" :placeholder="$t('Number')"
                    @input="handleClientNumber" />
                </div>
                <!-- <div class="col-12 col-md-3 mb-4">
                                    <label>{{ $t('status') }}</label>
                                    <app-input id="status" v-model="formData.status_id"
                                        :error-message="$errorMessage(errors, 'status_id')" :list="statusList"
                                        :placeholder="$t('choose_a_status')" list-value-field="translated_name"
                                        type="search-select" />
                                </div> -->
              </div>

              <div class="row">
                <div class="col-12 col-md-3 mb-4">
                  <label>{{ $t("invoice_number") }}</label>
                  <app-input class="margin-right-8" v-model="formData.invoice_number" :disabled="true"
                    :error-message="$errorMessage(errors, 'invoice_number')" :placeholder="$t('invoice_number')"
                    type="text" />
                </div>
                <div class="col-12 col-md-3 mb-4">
                  <label>{{ $t("issue_date") }}</label>
                  <app-input id="date" v-model="formData.date" :error-message="$errorMessage(errors, 'date')" type="date"
                    required />
                </div>
                <div class="col-12 col-md-3 mb-4">
                  <label>Time</label>
                  <app-input id="time" v-model="formData.time" :error-message="$errorMessage(errors, 'date')"
                    type="time" />
                </div>
                <div class="col-12 col-md-3 mb-4">
                  <label>Packaging</label>
                  <app-input class="margin-right-8" id="packaging_type" v-model="formData.packaging_type"
                    :list="packagingTypeList" :error-message="$errorMessage(errors, 'packaging_type')"
                    list-value-field="name" type="select" />
                </div>
              </div>

              <hr />

              <div class="row" style="
                  border: 2px solid red;
                  box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.2);
                  padding-top: 10px;
                ">
                <!-- From Address Section -->
                <div class="col-12 col-md-6 mb-4" style="font-weight: bold">
                  <div class="row">
                    <div class="col-12">
                      <label>{{ $t("from_address") }}</label>
                    </div>
                    <div class="col-12 mb-4">
                      <app-input class="margin-right-8" v-model="formData.from_address"
                        :error-message="$errorMessage(errors, 'from_address')"
                        :placeholder="$t('from_address_place_holder')" type="textarea" />
                    </div>
                    <div class="col-12 col-md-6 mb-4">
                      <label>{{ $t("floor") }}</label>
                      <app-input class="margin-right-8" v-model="formData.floor_from_address"
                        :placeholder="$t('floor_place_holder')" :error-message="$errorMessage(errors, 'floor_from_address')
                          " type="number" />
                    </div>
                    <div class="col-12 col-md-6 mb-4 mt-3">
                      <label>{{ $t("lift") }}</label>
                      <app-input class="margin-right-8" v-model="formData.lift_from_address" :error-message="$errorMessage(errors, 'lift_from_address')
                        " :placeholder="$t('lift_place_holder')" type="switch" />
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
                      <app-input class="margin-right-8" v-model="formData.to_address"
                        :error-message="$errorMessage(errors, 'to_address')" :placeholder="$t('to_address_place_holder')"
                        type="textarea" />
                    </div>
                    <div class="col-12 col-md-6 mb-4">
                      <label>{{ $t("floor") }}</label>
                      <app-input class="margin-right-8" v-model="formData.floor_to_address" :error-message="$errorMessage(errors, 'floor_to_address')
                        " :placeholder="$t('floor_place_holder')" type="number" />
                    </div>
                    <div class="col-12 col-md-6 mb-4 mt-3">
                      <label>{{ $t("lift") }}</label>
                      <app-input class="margin-right-8" v-model="formData.lift_to_address" :error-message="$errorMessage(errors, 'lift_to_address')
                        " :placeholder="$t('lift_place_holder')" type="switch" />
                    </div>
                  </div>
                </div>
              </div>
              <!-- <hr />
              <div class="row mb-4">
                <div class="col">
                  <div class="row align-items-end">
                    <div class="col-4">
                      <label>{{ $t("choose_a_category") }}</label>
                      <app-input
                        type="search-and-select"
                        v-model="selectCategoryId"
                        :placeholder="$t('choose_a_category')"
                        :options="categoryList"
                        @input="changeCategory"
                      />
                    </div>
                    <div class="col-4">
                      <label>{{ $t("choose_a_product") }}</label>
                      <app-input
                        :disabled="selectCategoryId === null"
                        type="search-and-select"
                        :placeholder="$t('choose_a_product')"
                        :options="productList"
                        v-model="selectProductId"
                        :error-message="
                          $errorMessage(errors, 'selectProductId')
                        "
                        @input="changeProduct"
                      />
                    </div>
                    <div class="col">
                      <div class="row">
                        <div class="col">
                          <button
                            class="btn btn-success mb-1"
                            @click.prevent="isProductModalActive = true"
                          >
                            {{ $t("add_product") }}
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div> -->
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="card border-0 card-with-shadow" style="margin-bottom: 25px">
            <div class="card-body">
              <h5 class="text-muted mb-3 border-bottom pb-2">
                {{ "Items to Pack and Move" }}
              </h5>

              <div v-if="tempSelectedProducts">
                <div class="accordion">
                  <div v-for="(category, index) in tempSelectedProducts" :key="index" class="option">
                    <input type="checkbox" :id="'toggle' + index" class="toggle" />
                    <label class="title" :for="'toggle' + index">
                      {{ category.name }}
                    </label>
                    <div class="content">
                      <span v-if="category.products.length > 0" class="font-weight-bold edit-icon"
                        @click="handleEditCategoryAndProducts(category.name)">
                        <app-icon name="edit" class="menu-icon ml-3" />
                      </span>
                      <span v-else class="font-weight-bold plus-icon"
                        @click="handleEditCategoryAndProducts(category.name)">
                        <app-icon name="plus" class="menu-icon ml-3" />
                      </span>

                      <ul class="" style="list-style-type: none; padding-left: 0">
                        <li v-for="(product, index) in category.products" :key="`selected-product-item-${index}`"
                          class="mb-1">
                          {{ product.name }}:
                          <span>
                            {{ product.quantity }}
                          </span>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>

                <!-- {{ selectCategoryName }}
                <ul
                  v-for="(product, index) in selectedProductsDetails"
                  :key="`selected-product-item-${index}`"
                  style="list-style-type: none; padding-left: 0"
                  class="mb-3"
                >
                  <li>
                    {{ product.name }}:
                    <span>
                      {{ product.quantity }}
                    </span>
                  </li>
                </ul> -->

                <!-- OLD ONE -->

                <!-- <div
                  v-for="(category, index) in tempSelectedProducts"
                  :key="index"
                  class="mb-3"
                >
                  <span
                    class="font-weight-bold mb-2"
                    @click="handleEditCategoryAndProducts(category.name)"
                  >
                    {{ category.name }}
                    <app-icon name="edit" class="menu-icon ml-3" />
                  </span>

                  <ul
                    class="mb-3"
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
                </div> -->

                <div>
                  <h5 class="text-muted border-bottom mt-2 mb-3">Charges</h5>

                  <div class="mb-3">
                    <label>Hide charges from invoice</label>
                    <app-input class="margin-right-8" style="display: inline-block; margin-left: 10px"
                      v-model="formData.is_hide_charges" :placeholder="$t('text_hide_break_down')"
                      :error-message="$errorMessage(errors, 'is_hide_charges')" type="switch" />
                  </div>
                  <div class="row">
                    <div class="col col-lg-4">
                      <div class="mb-3">
                        <label>Packing</label>
                        <app-input class="margin-right-8" v-model="formData.packing"
                          :error-message="$errorMessage(errors, 'packing')" placeholder="" type="number"
                          @input="calculateSubTotal" />
                      </div>

                      <div class="mb-3">
                        <label>Unloading</label>
                        <app-input class="margin-right-8" v-model="formData.unloading"
                          :error-message="$errorMessage(errors, 'unloading')" type="number" @input="calculateSubTotal" />
                      </div>

                      <div class="mb-3">
                        <label>Local</label>
                        <app-input class="margin-right-8" v-model="formData.local"
                          :error-message="$errorMessage(errors, 'local')" type="number" @input="calculateSubTotal" />
                      </div>

                      <div class="mb-3">
                        <label>GST</label>
                        <app-input class="margin-right-8" v-model="formData.gst"
                          :error-message="$errorMessage(errors, 'gst')" type="number" @input="calculateSubTotal" />
                      </div>
                    </div>
                    <div class="col col-lg-4">
                      <div class="mb-3">
                        <label>Transport</label>
                        <app-input class="margin-right-8" v-model="formData.transport"
                          :error-message="$errorMessage(errors, 'transport')" type="number" @input="calculateSubTotal" />
                      </div>

                      <div class="mb-3">
                        <label>Unpacking</label>
                        <app-input class="margin-right-8" v-model="formData.unpacking"
                          :error-message="$errorMessage(errors, 'unpacking')" type="number" @input="calculateSubTotal" />
                      </div>

                      <div class="mb-3">
                        <label>Car Transport</label>
                        <app-input class="margin-right-8" v-model="formData.car_transport" :error-message="$errorMessage(errors, 'car_transport')
                          " type="number" @input="calculateSubTotal" />
                      </div>
                    </div>
                    <div class="col col-lg-4">
                      <div class="mb-3">
                        <label>Loading</label>
                        <app-input class="margin-right-8" v-model="formData.loading"
                          :error-message="$errorMessage(errors, 'loading')" type="number" @input="calculateSubTotal" />
                      </div>

                      <div class="mb-3">
                        <label>AC</label>
                        <app-input class="margin-right-8" v-model="formData.ac"
                          :error-message="$errorMessage(errors, 'ac')" type="number" @input="calculateSubTotal" />
                      </div>

                      <div class="mb-3">
                        <label>Insuarance</label>
                        <app-input class="margin-right-8" v-model="formData.insuarance"
                          :error-message="$errorMessage(errors, 'insuarance')" type="number" @input="calculateSubTotal" />
                      </div>

                      <div class="mb-3">
                        <label>Sub Total</label>
                        <app-input class="margin-right-8" v-model="formData.sub_total"
                          :error-message="$errorMessage(errors, 'sub_total')" disabled type="number" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6 col-lg-9">
          <!-- other information card -->
          <div class="card border-0 card-with-shadow">
            <div class="card-body">
              <h5 class="text-muted mb-3 border-bottom pb-2">
                {{ $t("other_information") }}
              </h5>
              <div class="note note-warning px-4 py-2 mb-4">
                {{ $t("discount_will_be_applicable_on_subtotal_amount") }}
              </div>
              <div class="row mb-4">
                <div class="col">
                  <label>{{ $t("discount_type") }}</label>
                  <app-input class="margin-right-8" id="discount_type" v-model="formData.discount_type"
                    :list="discountTypeList" :error-message="$errorMessage(errors, 'discount_type')"
                    list-value-field="name" type="select" />
                </div>
                <div class="col">
                  <label>{{ $t("discount") }}</label>
                  <app-input class="margin-right-8" type="number" v-model="formData.discount"
                    :error-message="$errorMessage(errors, 'discount')" :disabled="formData.discount_type === 'none'" />
                </div>
              </div>
              <div class="row mb-4">
                <div class="col">
                  <label>{{ $t("notes") }}</label>
                  <app-input type="textarea" v-model="formData.notes" :error-message="$errorMessage(errors, 'notes')" />
                </div>
              </div>
              <div class="row mb-4">
                <div class="col">
                  <label>{{ $t("terms") }}</label>
                  <app-input type="textarea" v-model="formData.terms" :error-message="$errorMessage(errors, 'terms')" />
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
                          <strong class="label" style="text-transform: uppercase">{{ $t("total") }}:</strong>
                        </td>
                        <td class="text-right">
                          <strong>{{
                            numberWithCurrencySymbol(totalAmount)
                          }}</strong>
                        </td>
                      </tr>

                      <tr>
                        <td>
                          <span class="label">
                            <!-- {{ $t("received_amount") }}: -->
                            Advance Payment:
                          </span>
                        </td>
                        <td>
                          <app-input inputClass="text-right" type="text" v-model="formData.received_amount"
                            :error-message="$errorMessage(errors, 'received_amount')
                              " />
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
                            }}</span>
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

    <category-products-modal v-if="isCategoryProductsModalActive && selectCategoryId" :modal-id="categoryProductsModalId"
      :category-id="selectCategoryId" :category-name="selectCategoryName" :edit-products="editCategoryProductsOpenModal"
      :edit-products-old="selectedProductsDetails" @receiveProductListEvent="handleSelectedProductsList"
      @closeModal="closeCategoryProductsModal" />

    <product-add-edit-modal v-if="isProductModalActive" table-id="product-table" type="invoice"
      @closeModal="closeProductModal" />

    <app-delete-modal v-if="invoiceProductDeleteModal" modal-id="invoice-product-delete-confirm-modal"
      :message="$t('are_you_sure_to_delete_this_product')" @confirmed="confirmDeleteInvoiceProduct"
      @cancelled="closeInvoiceDeleteModal" />
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
      formData: {
        client_name: "",
        client_email: "",
        country_code: "+91",
        client_number: "",
        recurring: 2,
        invoice_number: null,
        date: DateFunction.getDateFormat(new Date(), "YYYY-MM-DD"),
        time: DateFunction.getDateTimeFormatForBackend(new Date()),
        packaging_type: "",
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
      // Check if the input value starts with the country code
      if (!inputValue.startsWith(this.formData.country_code)) {
        // If not, set the input value to the country code prepended with the entered value
        this.formData.client_number = this.formData.country_code + inputValue;
      }
    },
    changeCategory() {
      this.selectCategoryName = this.categoryData.filter(
        (product) => product.id === this.selectCategoryId
      )[0]?.name;

      this.editCategoryProductsOpenModal = false;
      this.isCategoryProductsModalActive = true;
    },
    checkAndReplaceData(array, name, newProducts) {
      for (let i = 0; i < array.length; i++) {
        if (array[i].name === name) {
          array[i].products = newProducts;
          return array;
        }
      }
      return array;
    },

    updateCategoryProductsByName(oldArray, newData) {
      const updatedArray = [...oldArray]; // Create a copy of the oldArray to avoid modifying the original array

      newData.forEach((updatedCategory) => {
        // Find the category object with the matching name in the updatedArray
        const categoryObj = updatedArray.find(
          (category) => category.name === updatedCategory.name
        );

        // If category object is found, update its products
        if (categoryObj) {
          categoryObj.products = updatedCategory.products;
        }
      });

      // console.log("updatedArray: ", updatedArray);
      return updatedArray; // Return the updated array
    },
    handleSelectedProductsList(products) {
      this.editCategoryProductsOpenModal = false;

      const categoryAndProducts = {
        name: this.selectCategoryName,
        products,
      };

      const checkIfCategoryExists = this.tempSelectedProducts.filter(
        (category) => category.name === this.selectCategoryName
      ).length;

      if (checkIfCategoryExists) {
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
      // console.log("tempSelectedProducts: ", this.tempSelectedProducts);
    },
    handleEditCategoryAndProducts(name) {
      const getNewSelectCategory = this.categoryData.filter(
        (category) => category.name === name
      )[0];
      const newSelectedProductDetails = this.tempSelectedProducts.filter(
        (category) => category.name === name
      )[0];

      this.selectCategoryId = getNewSelectCategory.id;
      this.selectCategoryName = getNewSelectCategory.name;
      this.selectedProductsDetails = newSelectedProductDetails.products;

      this.editCategoryProductsOpenModal = true;
      this.isCategoryProductsModalActive = true;
    },
    calculateSubTotal() {
      const subTotal =
        Number(this.formData.packing) +
        Number(this.formData.unloading) +
        Number(this.formData.local) +
        Number(this.formData.gst) +
        Number(this.formData.transport) +
        Number(this.formData.unpacking) +
        Number(this.formData.car_transport) +
        Number(this.formData.loading) +
        Number(this.formData.ac) +
        Number(this.formData.insuarance);

      this.formData.sub_total = subTotal;
    },
    computeTotal() {
      const total = this.formData.sub_total - this.totalDiscountAmount;
      return total;
    },
    afterError({ data }) {
      this.errors = data.errors;
      if (this.productDetails.length < 1) {
        this.$toastr.e(this.$t("your_cart_is_empty"));
      }
    },
    convertTo12Hour(time24) {
      const regex = /^([01]\d|2[0-3]):[0-5]\d$/;
      if (regex.test(time24)) {
        return new Date("1970-01-01T" + time24 + "Z").toLocaleTimeString(
          "en-US",
          {
            hour: "numeric",
            minute: "numeric",
            hour12: true,
          }
        );
      }
      return time24;
    },
    generateDateAndTime(dateStr, timeStr) {
      // Create a new Date object with the date string
      const _timeStr = this.convertTo12Hour(timeStr);
      const dateObj = new Date(dateStr);

      // Extract the hours and minutes from the time string
      const [time, period] = _timeStr.split(" ");
      let [hours, minutes] = time.split(":");

      // Adjust hours if it's PM
      if (period === "PM") {
        hours = String(Number(hours) + 12);
      }

      // Set the time components to the date object
      dateObj.setHours(hours);
      dateObj.setMinutes(minutes);

      // Get the combined datetime value to UTC
      // const datetimeValue = dateObj
      //   .toISOString()
      //   .slice(0, 19)
      //   .replace("T", " ");

      // Get the combined datetime value to local timezone
      const options = {
        year: "numeric",
        month: "2-digit",
        day: "2-digit",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
        hour12: false,
      };
      const datetimeValue = dateObj
        .toLocaleString("en-US", options)
        .replace(/[/]/g, "-")
        .replace(",", "");

      return datetimeValue;
    },
    submitData() {
      let products = this.tempSelectedProducts.flatMap((obj) => obj.products);

      if (this.selectedUrl) {
        const updatedProducts = products.map((item) => {
          const matchingItem = this.formData.invoice_details.find(
            (a) => a.product_id === item.product_id
          );
          if (matchingItem) {
            item.id = matchingItem.id;
          }
          return item;
        });

        products = updatedProducts;
      }

      const date = this.generateDateAndTime(
        this.formData.date,
        this.formData.time
      );
      const formData = {
        ...this.formData,
        date,
        // date: formatDateForServer(this.formData.date),
        discount: this.formData.discount ? this.formData.discount : 0,
        sub_total: this.formData.sub_total,
        total: this.totalAmount,
        received_amount: this.formData.received_amount
          ? this.formData.received_amount - this.returnAmount
          : 0,
        due_amount: this.deuAmount,
        products,
      };

      for (const [key, value] of Object.entries(formData)) {
        if (!isNaN(value)) {
          formData[key] = Number(value);
        }
      }

      formData.recurring_cycle_id = null;
      // console.log("Submit: ", formData);
      this.save(formData);
    },
    resetData() {
      window.location = urlGenerator(INVOICES_LIST);
    },
    afterSuccess({ data }) {
      this.$toastr.s(data.message);
      setTimeout(() => {
        window.location = urlGenerator(INVOICES_LIST);
      });
    },
    afterSuccessFromGetEditData(response) {
      if (response) {
        this.formData = response.data;
        this.formData.time = DateFunction.getDateTimeFormatForBackend(
          new Date(response.data.date)
        );

        let groupedProducts = [];
        response.data.invoice_details.forEach((item) => {
          const { category_name, product_id, product_name, quantity } = item;

          let category = groupedProducts.find(
            (category) => category.name === category_name
          );

          if (!category) {
            category = {
              name: category_name,
              products: [],
            };
            groupedProducts.push(category);
          }

          category.products.push({
            product_id,
            name: product_name,
            quantity,
          });
        });

        this.groupedProducts = groupedProducts;
        this.productDetails = this.formData.invoice_details.map((item) => {
          return {
            id: item.id,
            product_id: item.product_id,
            name: item.product?.name,
            quantity: item.quantity,
            price: item.price,
            tax_id: item.tax ? item.tax_id : null,
            amount: item.quantity * item.price,
            packages: item.packages,
          };
        });
        this.preloader = false;
      }
    },
    getInvoiceCount() {
      this.axiosGet(`invoice-number`).then((response) => {
        let invoice = response.data.id != undefined ? response.data.id : 0;
        this.formData.invoice_number =
          Number(invoice) + Number(window.settings.invoice_starting_number);
      });
    },
    confirmDeleteInvoiceProduct() {
      axiosPost(
        this.deletedInvoiceProductContext["payload"].url,
        this.deletedInvoiceProductContext["payload"].data
      ).then((response) => {
        this.deletedInvoiceProductContext["callBack"]();
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
      let categories = [];
      let tempSelectedProducts = [];
      axiosGet("all-category?search=&page=1&per_page=10").then((response) => {
        const _categories = response.data.data;
        categories = _categories;
        _categories.map((category) => {
          tempSelectedProducts.push({
            name: category.name,
            products: [],
          });
        });

        this.categoryData = categories;
        this.tempSelectedProducts = this.updateCategoryProductsByName(
          tempSelectedProducts,
          this.groupedProducts
        );

        return categories;
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

.toggle:checked+.title {
  padding-bottom: 10px;
}

.toggle:checked+.title,
.toggle:checked+.title+.content {
  box-shadow: 3px 3px 6px #ddd, -3px 3px 6px #ddd;
}

.toggle:checked+.title+.content {
  min-height: 60px;
  max-height: 500px;
  padding: 1rem;
}

.toggle:checked+.title:before {
  transform: rotate(90deg) !important;
}
</style>
