<template>
  <modal
    modal-size="large"
    :title="categoryName"
    :modal-id="modalId"
    @closeModal="closeModal"
    @submit="sendSelectedProductsToInvoice"
  >
    <template slot="body">
      <app-overlay-loader v-if="preloader" />
      <form
        v-else
        ref="form"
        :class="{ 'loading-opacity': preloader }"
        :data-url="'#'"
        class="mb-0"
      >
        <div class="row">
          <div class="col">
            <table class="w-100">
              <thead>
                <tr>
                  <th class="table__item table__item--products px-1">
                    <div class="text-muted">{{ $t("products") }}</div>
                  </th>
                  <th class="table__item table__item--quantity px-1">
                    <div class="text-muted text-center">
                      {{ $t("quantity") }}
                    </div>
                  </th>
                  <th class="table__item table__item--quantity px-1">
                    <div class="text-muted">Condition</div>
                  </th>
                </tr>
              </thead>
              <tbody v-if="categoryProductsList">
                <tr
                  v-for="(product, index) in categoryProductsList"
                  :key="`products-item-${index}`"
                >
                  <td class="px-1">{{ product.name }}</td>
                  <td class="px-1">
                    <div
                      class="d-flex align-items-center d-flex justify-content-between gap-2 w-100"
                      style="max-width: 200px"
                    >
                      <a
                        @click.prevent="decrementQty(product)"
                        class="text-primary text-decoration-none"
                        style="cursor: pointer"
                      >
                        <app-icon name="minus-circle" />
                      </a>
                      <input
                        type="text"
                        v-model="product.quantity"
                        class="form-control text-center pr-5"
                      />
                      <a
                        @click.prevent="incrementQty(product)"
                        class="text-primary text-decoration-none"
                        style="cursor: pointer"
                      >
                        <app-icon name="plus-circle" />
                      </a>
                    </div>
                  </td>
                  <td class="px-1">
                    <app-input
                      class="margin-right-8"
                      id="condition"
                      v-model="product.condition"
                      :list="conditionList"
                      :error-message="$errorMessage(errors, 'condition')"
                      list-value-field="name"
                      type="select"
                    />
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </form>
    </template>
  </modal>
</template>

<script>
import { FormMixin } from "../../../../core/mixins/form/FormMixin";
import { SubmitFormMixins } from "../../../Mixins/billar/SubmitFormMixins";

import { axiosGet } from "../../../Helpers/AxiosHelper";
import { numberWithCurrencySymbol } from "../../../Helpers/Helpers";

export default {
  name: "CategoryProductsModal",
  mixins: [SubmitFormMixins, FormMixin],
  props: [
    "categoryId",
    "editProducts",
    "editProductsOld",
    "categoryName",
    "modalId",
  ],
  data() {
    return {
      categoryProductsList: null,
      productDetails: [],
      numberWithCurrencySymbol,
      conditionList: [
        { id: "none", name: "Choose condition" },
        { id: "S", name: "Scratched" },
        { id: "N", name: "New" },
        { id: "D", name: "Damaged" },
        { id: "U", name: "Used" },
      ],
    };
  },
  computed: {
    getCategoryProducts() {
      if (this.categoryId) {
        axiosGet(
          `/products?categories=${this.categoryId}&orderBy=desc&per_page=50`
        ).then(({ data }) => {
          this.categoryProductsList = data.data;
          return data.data;
        });
      }
      return null;
    },
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
  },
  methods: {
    getProducts(id) {
      axiosGet(`/products?categories=${id}&orderBy=desc&per_page=50`).then(
        ({ data }) => {
          let _products = [];
          if (data && data.data) {
            _products = data.data.map((obj) => {
              return {
                product_id: obj.id,
                name: obj.name,
                quantity: 0,
              };
            });
          }

          if (this.editProducts) {
            const updatedWithEditData =
              this.updateProductsWithOldProductsQuantity(
                _products,
                this.editProductsOld
              );

            this.categoryProductsList = updatedWithEditData;
            this.productDetails = updatedWithEditData;

            return updatedWithEditData;
          } else {
            this.categoryProductsList = _products;
            this.productDetails = _products;

            return _products;
          }
        }
      );
    },
    updateProductsWithOldProductsQuantity(oldData, newData) {
      oldData.forEach((obj1) => {
        const obj2 = newData.find((obj) => obj.product_id === obj1.product_id);
        if (obj2) {
          // Update values in oldData with values from array2
          Object.keys(obj2).forEach((key) => {
            obj1[key] = obj2[key];
          });
        }
      });

      return oldData;
    },
    incrementQty(product) {
      this._data.productDetails.find(
        (item) => item.product_id === product.product_id
      ).quantity++;
    },
    decrementQty(priduct) {
      if (priduct.quantity > 0) {
        this._data.productDetails.find(
          (item) => item.product_id === priduct.product_id
        ).quantity--;
      }
    },
    sendSelectedProductsToInvoice() {
      const filteredProducts = this.productDetails.filter(
        (product) => product.quantity > 0
      );
      this.$emit("receiveProductListEvent", filteredProducts);

      $("#" + this.modalId).modal("hide");
      this.$emit("closeModal");
    },
  },
  mounted() {
    if (this.categoryId !== undefined) {
      this.getProducts(this.categoryId);
    }
  },
};
</script>