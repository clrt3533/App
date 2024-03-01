<template>
  <div class="content-wrapper">
    <div class="d-flex align-items-center justify-content-between">
      <app-breadcrumb
        :page-title="selectedUrl ? $t('update_inventory') : $t('add_inventory')"
      />
    </div>
    <app-overlay-loader v-if="preloader" />
    <form
      v-else
      :data-url="selectedUrl ? INVENTORY + '-update' : INVENTORY"
      ref="form"
      class="d-flex flex-column"
      style="gap: 25px"
    >
      <div class="row">
        <div class="col-12">
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
        </div>

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
              <h5 class="mb-3 border-bottom mt-2">
                {{ "Inventory List" }}
              </h5>

              <span
                v-if="$errorMessage(errors, 'products')"
                class="text-danger validation-error"
              >
                {{ $errorMessage(errors, "products") }}
              </span>
              <span
                v-if="$errorMessage(errors, 'products.0.condition')"
                class="text-danger validation-error"
              >
                {{ $errorMessage(errors, "products.0.condition") }}
              </span>

              <div v-if="tempSelectedProducts && formData.invoice_id">
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
                          {{ product.name }}
                          <strong>{{ " : " }}</strong>
                          <span>
                            {{ product.quantity }}
                          </span>
                          <strong>{{ " : " }}</strong>
                          <span>
                            {{
                              product.condition === "U"
                                ? "Used"
                                : product.condition === "N"
                                ? "New"
                                : product.condition === "D"
                                ? "Damaged"
                                : product.condition === "S"
                                ? "Scratched"
                                : "Unknown"
                            }}
                          </span>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
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

      <div class="row signaatures">
        <div class="col-lg-6 col-12">
          <div class="signature-container">
            <VueSignaturePad ref="signaturePad" />
          </div>

          <div class="signature-buttons">
            <label>Pick Up Signature</label>
            <button @click="clearSignature" class="btn btn-secondary">Clear</button>
          </div>
        </div>
        <br />
        <div class="col-lg-6 col-12">
          <div class="signature-container">
            <VueSignaturePad ref="deliverySignaturePad" />
          </div>

          <div class="signature-buttons">
            <label>Drop Signature</label>
            <button @click="clearDeliverySignature" class="btn btn-secondary">Clear</button>
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

    <inventory-category-products-modal
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
  </div>
</template>

<script>
import { SubmitFormMixins } from "../../../Mixins/billar/SubmitFormMixins";
import { axiosGet, urlGenerator } from "../../../Helpers/AxiosHelper";
import { INVENTORY, INVENTORY_LIST } from "../../../Config/BillarApiUrl";

export default {
  name: "InventoryCreateEdit",
  mixins: [SubmitFormMixins],
  data() {
    return {
      INVENTORY,
      categoryProductsModalId: "category-products-modal",
      selectCategoryId: null,
      selectCategoryName: null,
      selectProductId: null,
      invoiceProductDeleteModal: false,
      deletedInvoiceProductContext: null,
      isCategoryProductsModalActive: false,
      editCategoryProductsOpenModal: false,
      // isClientModalActive: false,
      isProductModalActive: false,
      imagePreview: null,
      showPreview: false,
      inventoriesList: [],
      invoiceList: [],
      productData: [],
      productDetails: [],
      categoryData: [],
      tempSelectedProducts: [],
      groupedProducts: [],
      formData: {
        invoice_id: this.invoiceId ? Number(this.invoiceId) : null,
        inventory_details: [],
        notes: "",
        signature: "",
        delivery_signature: "",
      },
      invoiceNumberOptions: {
        url: urlGenerator("all-invoice"),
        per_page: 10,
        modifire: (item) => {
          const checkIfInventoryExists = this.inventoriesList.some(
            (inventory) => inventory.invoice_id === item.id
          );
          this.invoiceList.push(item);

          if (this.selectedUrl) {
            return { id: item.id, value: item.id };
          } else {
            if (!checkIfInventoryExists) {
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
        axiosGet(`/invoices/${invoiceId}`).then(({ data }) => {
          this.formData.inventory_details = data.invoice_details;
          const groupedProducts = data.invoice_details.reduce(
            (groups, item) => {
              const { category_name, product_id, product_name, quantity } =
                item;
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
          this.productDetails = this.formData.inventory_details.map((item) => ({
            id: item.id,
            product_id: item.product_id,
            name: item.product?.name,
            quantity: item.quantity,
            price: item.price,
            tax_id: item.tax ? item.tax_id : null,
            amount: item.quantity * item.price,
            packages: item.packages,
          }));

          const tempSelectedProducts = this.categoryData.map((category) => ({
            name: category.name,
            products: [],
          }));
          this.tempSelectedProducts = this.updateCategoryProductsByName(
            tempSelectedProducts,
            Object.values(groupedProducts)
          );
        });
      }
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
      console.log("updatedArray: ", updatedArray);
      return updatedArray;
    },
    getInventories() {
      axiosGet(`/inventory?per_page=100&page=1&searhc=&orderBy=desc`).then(
        ({ data }) => {
          this.inventoriesList = data.data;
        }
      );
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
    clearSignature(e) {
      e.preventDefault();
      this.$refs.signaturePad.undoSignature();
    },
    clearDeliverySignature(e) {
      e.preventDefault();
      this.$refs.deliverySignaturePad.undoSignature();
    },
    dataURItoBlob(dataURI) {
      if (dataURI) {
        const byteString = atob(dataURI.split(",")[1]);
        const ab = new ArrayBuffer(byteString.length);
        const ia = new Uint8Array(ab);
        for (let i = 0; i < byteString.length; i++) {
          ia[i] = byteString.charCodeAt(i);
        }
        return new Blob([ab], { type: "image/png" });
      }
      return null;
    },
    async convertToBase64(imagePath) {
      try {
        const response = await fetch(imagePath);
        const blob = await response.blob();
        const reader = new FileReader();

        reader.onload = () => {
          this.$refs.signaturePad.fromDataURL(reader.result);
        };

        reader.readAsDataURL(blob);
      } catch (error) {
        console.error("Error fetching or converting the image:", error);
      }
    },
    async deliveryConvertToBase64(imagePath) {
      try {
        const response = await fetch(imagePath);
        const blob = await response.blob();
        const reader = new FileReader();

        reader.onload = () => {
          this.$refs.deliverySignaturePad.fromDataURL(reader.result);
        };

        reader.readAsDataURL(blob);
      } catch (error) {
        console.error("Error fetching or converting the image:", error);
      }
    },
    afterSuccessFromGetEditData(response) {
      if (response) {
        this.formData = response.data;
        console.log("Response: ", this.formData);
        const signatureImg = `${window.location.origin}/signatures/${response.data.signature}`;
        const deliverySignatureImg = `${window.location.origin}/signatures/${response.data.delivery_signature}`;
        this.convertToBase64(signatureImg);
        this.deliveryConvertToBase64(deliverySignatureImg);
        const groupedProducts = response.data.inventory_details.reduce(
          (groups, item) => {
            const {
              category_name,
              product_id,
              product_name,
              quantity,
              condition,
            } = item;
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
              condition,
            });
            return groups;
          },
          {}
        );
        this.groupedProducts = Object.values(groupedProducts);
        this.productDetails = this.formData.inventory_details.map((item) => ({
          id: item.id,
          product_id: item.product_id,
          name: item.product?.name,
          quantity: item.quantity,
          price: item.price,
          tax_id: item.tax ? item.tax_id : null,
          amount: item.quantity * item.price,
          packages: item.packages,
          condition: item.condition,
        }));
        this.preloader = false;
      }
    },
    submitData() {
      let products = this.tempSelectedProducts.flatMap((obj) => obj.products);
      if (this.selectedUrl) {
        products = products.map((item) => {
          const matchingItem = this.formData.inventory_details.find(
            (a) => a.product_id === item.product_id
          );
          if (matchingItem) {
            return { ...item, id: matchingItem.id };
          }
          return item;
        });
      }

      const { data } = this.$refs.signaturePad.saveSignature();
      const { data: deliveryData } =
        this.$refs.deliverySignaturePad.saveSignature();
      const imageBlob = this.dataURItoBlob(data);
      const deliveryImageBlob = this.dataURItoBlob(deliveryData);

      let formData = new FormData();
      formData.append("invoice_id", this.formData.invoice_id);
      formData.append("notes", this.formData.notes);

      if (imageBlob) {
        formData.append("signature", "image.png");
        formData.append("signatureBase64", imageBlob, "image.png");
      }

      if (deliveryImageBlob) {
        formData.append("delivery_signature", "delivery_image.png");
        formData.append(
          "delivery_signatureBase64",
          deliveryImageBlob,
          "delivery_image.png"
        );
      }

      formData.append("products", JSON.stringify(products));

      if (data) {
        if (this.selectedUrl) {
          formData.append("inventory_id", this.formData.id);
          this.update(formData);
        } else {
          this.save(formData);
        }
      }
    },
    resetData() {
      window.location = urlGenerator(INVENTORY_LIST);
    },
    afterSuccess({ data }) {
      this.$toastr.s(data.message);
      setTimeout(() => {
        window.location = urlGenerator(INVENTORY_LIST);
      });
    },
  },
  mounted() {
    this.getInventories();
    this.getCategories();
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

.signature-container {
  max-height: 300px;
  border: 2px solid #090909;
  text-align: center;
  padding: 10px;
  margin: 10px;
  height: 100%;
  width: 90%; /* Set the width to 90% of the container */
  display: inline-block; /* Ensures the container doesn't take the full width */
  box-sizing: border-box; /* Includes padding and border in the element's total width and height */
  position: relative; /* Allows absolute positioning of the buttons */
}

.signature-container VueSignaturePad {
  width: 100%; /* Set the width of the VueSignaturePad component to 100% */
  height: 100%; /* Set the height of the VueSignaturePad component to 100% */
  position: absolute; /* Allows the signature pad to be outside the box */
  top: 0;
  left: 0;
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

@media only screen and (max-width: 767px) {
  .signaatures {
    display: block;
  }

  .signature-container {
    margin: 0 auto;
    width: 100%;
    height: 300px;
  }

  .signature-buttons {
    margin-top: 15px;
  }
}
</style>