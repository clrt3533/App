<template>
    <div class="content-wrapper">
        <div class="d-flex align-items-center justify-content-between">
            <app-breadcrumb :page-title="selectedUrl ? $t('update_invoice') : $t('add_invoice')"/>
        </div>
        <app-overlay-loader v-if="preloader"/>
        <form v-else :data-url="selectedUrl ? selectedUrl : INVOICES" ref="form" class="d-flex flex-column"
              style="gap: 25px;">
            <div class="row">
                <div class="col">
                    <div class="card border-0 card-with-shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div>
                                        <div class="note note-warning p-4 mb-primary clearfix">
                                            <p class="m-1">{{ $t('setup_email_address') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 mb-4">
                                    <div class="d-flex align-items-end">
                                        <div class="flex-fill">
                                            <label>{{ $t('client') }}</label>
                                            <app-input
                                                class="margin-right-8"
                                                type="search-and-select"
                                                :placeholder="$t('choose_a_client')"
                                                :options="clientOptions"
                                                v-model="formData.client_id"
                                                :error-message="$errorMessage(errors, 'client_id')"
                                            />
                                        </div>
                                        <div>
                                            <button class="btn btn-success" style="margin-bottom: 2px;"
                                                    @click.prevent="openClientModal">
                                                <app-icon name="plus"/>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 mb-4">
                                    <label>{{ $t('invoice_number') }}</label>
                                    <app-input
                                        class="margin-right-8"
                                        v-model="formData.invoice_number"
                                        :disabled="true"
                                        :error-message="$errorMessage(errors, 'invoice_number')"
                                        :placeholder="$t('invoice_number')"
                                        type="text"
                                    />
                                </div>
                                <div class="col-12 col-md-4 mb-4">
                                    <label>{{ $t('status') }}</label>
                                    <app-input
                                        id="status"
                                        v-model="formData.status_id"
                                        :error-message="$errorMessage(errors, 'status_id')"
                                        :list="statusList"
                                        :placeholder="$t('choose_a_status')"
                                        list-value-field="translated_name"
                                        type="search-select"
                                    />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 col-md-4 mb-4">
                                    <label>{{ $t('issue_date') }}</label>
                                    <app-input
                                        id="date"
                                        v-model="formData.date"
                                        :error-message="$errorMessage(errors, 'date')"
                                        type="date"
                                    />
                                </div>
                                <div class="col-12 col-md-4 mb-4">
                                    <label>{{ $t('due_date') }}</label>
                                    <app-input
                                        id="dueDate"
                                        :min-date="formData.due_date"
                                        v-model="formData.due_date"
                                        :error-message="$errorMessage(errors, 'due_date')"
                                        type="date"
                                    />
                                </div>


                            </div>
                            <div class="row">
                              <div class="col-12 col-md-4 mb-4">
                                <label>{{ $t('from_address') }}</label>
                                <app-input
                                    class="margin-right-8"
                                    v-model="formData.from_address"
                                    :placeholder="$t('from_address_place_holder')"
                                    type="text"
                                />
                              </div>

                              <div class="col-12 col-md-4 mb-4">
                                <label>{{ $t('to_address') }}</label>
                                <app-input
                                    class="margin-right-8"
                                    v-model="formData.to_address"
                                    :placeholder="$t('to_address_place_holder')"
                                    type="text"
                                />
                              </div>


                            </div>

                          <div class="row">
                            <div class="col-12 col-md-4 mb-4">
                              <label>{{ $t('hide_break_down') }}</label>
                              <app-input
                                  class="margin-right-8"
                                  v-model="formData.is_breakdown"
                                  :placeholder="$t('text_hide_break_down')"
                                  type="switch"
                              />
                            </div>
<!--                            <div class="col-12 col-md-4 mb-4">-->
<!--                              <label>{{ $t('choose_tax') }}</label>-->
<!--                              <app-input v-model="product.tax_id" :list="taxList"-->
<!--                                         :placeholder="taxList && taxList.length > 1 ? $t('choose_tax') : $t('n_a_tax')"-->
<!--                                         list-value-field="name"-->
<!--                                         type="select"-->
<!--                                        @input="changeTax(product)"-->
<!--                              />-->
<!--                            </div>-->
                          </div>

                            <hr>
                            <div class="row mb-4">
                                <div class="col col-md-8">
                                    <div class="row  align-items-end">
                                        <div class="col-8">
                                            <label>{{ $t('choose_a_product') }}</label>
                                            <app-input
                                                type="search-and-select"
                                                :placeholder="$t('choose_a_product')"
                                                :options="productList"
                                                v-model="selectProductId"
                                                @input="changeProduct"
                                            />
                                        </div>
                                        <div class="col">
                                            <div class="row">
                                                <div class="col">
                                                    <button class="btn btn-success mb-1"
                                                            @click.prevent="isProductModalActive = true">
                                                        {{ $t('add_product') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <table class="w-100">
                                        <thead>
                                        <tr>
                                            <th class="table__item table__item--products px-1">
                                                <div class="text-muted">{{ $t('products') }}</div>
                                            </th>
                                          <th class="table__item table__item--tax px-1">
                                            <div class="text-muted">{{ $t('packages') }}</div>
                                          </th>
                                            <th class="table__item table__item--quantity px-1">
                                                <div class="text-muted">{{ $t('quantity') }}</div>
                                            </th>
                                            <th class="table__item table__item--unit_price px-1">
                                                <div class="text-muted">{{ $t('unit_price') }}</div>
                                            </th>
                                          <th class="table__item table__item--tax px-1">
                                            <div class="text-muted">{{ $t('tax') }}</div>
                                          </th>

                                            <th class="table__item table__item--total_amount px-1 text-right">
                                                <div class="text-muted">{{ $t('total_amount') }}</div>
                                            </th>
                                            <th class="table__item table__item--total_action px-1 text-right">
                                                <div class="text-muted">{{ $t('action') }}</div>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(product, index) in productDetails" :key="`products-item-${index}`">
                                            <td class="px-1">{{ product.name }}</td>
                                             <td class="px-1">
                                              <app-input v-model="product.packages"
                                                       :list="formData.packageList"
                                                       type="select"
                                              />
                                              </td>
                                            <td class="px-1">
                                                <div
                                                    class="d-flex align-items-center d-flex justify-content-between gap-2 w-100"
                                                    style="max-width: 200px;">
                                                    <a @click.prevent="decrementQty(product)"
                                                       class="text-primary text-decoration-none"
                                                       style="cursor: pointer;">
                                                        <app-icon name="minus-circle"/>
                                                    </a>
                                                    <input type="text" v-model="product.quantity" @input="changeQty(product)" class="form-control pr-5">
                                                    <a @click.prevent="incrementQty(product)"
                                                       class="text-primary text-decoration-none"
                                                       style="cursor: pointer;">
                                                        <app-icon name="plus-circle"/>
                                                    </a>
                                                </div>
                                            </td>
                                            <td class="px-1">
                                                <input type="text" v-model="product.price" @input="changeQty(product)" class="form-control pr-5">
                                            </td>
                                          <td class="px-1">
                                            <app-input v-model="product.tax_id" :list="taxList"
                                                       :placeholder="taxList && taxList.length > 1 ? $t('choose_tax') : $t('n_a_tax')"
                                                       list-value-field="name"
                                                       type="select"
                                                       @input="changeTax(product)"
                                            />
                                          </td>

<!--                                            <td class="px-1 text-right">{{-->
<!--                                                    numberWithCurrencySymbol(product.amount)-->
<!--                                                }}-->
<!--                                            </td>-->
                                            <td class="px-1 text-right">{{
                                                    numberWithCurrencySymbol(productDetailsCalculated[index]?.total_amount)
                                                }}
                                            </td>
                                            <td class="px-1 text-right">
                                                <a @click.prevent="removeProductRow(index, product.id)">
                                                    <app-icon name="x-circle" style="cursor: pointer;"/>
                                                </a>
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
                <div class="col-12 col-md-6 col-lg-9">
                    <!-- other information card -->
                    <div class="card border-0 card-with-shadow">
                        <div class="card-body">
                            <h5 class="text-muted mb-3 border-bottom pb-2">{{ $t('other_information') }}</h5>
                            <div class="note note-warning px-4 py-2 mb-4">
                                {{ $t('discount_will_be_applicable_on_subtotal_amount') }}
                            </div>
                            <div class="row mb-4">
                                <div class="col">
                                    <label>{{ $t('discount_type') }}</label>
                                    <app-input
                                        class="margin-right-8"
                                        id="discount_type"
                                        v-model="formData.discount_type"
                                        :list="discountTypeList"
                                        list-value-field="name"
                                        type="select"
                                    />
                                </div>
                                <div class="col">
                                    <label>{{ $t('discount') }}</label>
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
                                    <label>{{ $t('notes') }}</label>
                                    <app-input
                                        type="textarea"
                                        v-model="formData.notes"
                                        :error-message="$errorMessage(errors, 'notes')"
                                    />
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col">
                                    <label>{{ $t('terms') }}</label>
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
                <div class="col">
                    <!-- Payment summary card  -->
                    <div class="card border-0 card-with-shadow h-100">
                        <div class="card-body">
                            <h5 class="mb-3 pb-2 border-bottom"> {{ $t('payment_summary') }} </h5>
                            <div class="row">
                                <div class="col">
                                    <table class="w-100" style="color: var(--default-font-color);">
                                        <tbody>
                                        <tr>
                                            <td><span>{{ $t('sub_total') }}:</span></td>
                                            <td class="w-50 text-right">
                                                <span>{{ numberWithCurrencySymbol(totalSummation.subTotal) }} </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span class="label">(+) {{ $t('tax') }}:</span></td>
                                            <td class="text-right"><span>{{
                                                    numberWithCurrencySymbol(totalSummation.tax)
                                                }}</span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="label">(-) {{ $t('discount') }}:</span></td>
                                            <td class="text-right">
                                                <span> {{ numberWithCurrencySymbol(totalDiscountAmount) }}</span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="p-1"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="p-1"></td>
                                        </tr>
                                        <tr>
                                            <td><strong class="label" style="text-transform: uppercase;">{{
                                                    $t('total')
                                                }}:</strong></td>
                                            <td class="text-right"><strong>{{
                                                    numberWithCurrencySymbol(totalAmount)
                                                }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td><span class="label">{{ $t('received_amount') }}:</span></td>
                                            <td>
                                                <app-input inputClass="text-right" type="text"
                                                           v-model="formData.received_amount"
                                                           :error-message="$errorMessage(errors, 'received_amount')"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><span class="label">{{ $t('return_amount') }}:</span></td>
                                            <td class="text-right"><span>{{
                                                    numberWithCurrencySymbol(returnAmount)
                                                }}</span></td>
                                        </tr>
                                        <tr>
                                            <td><span class="label">{{ $t('due_amount') }}:</span></td>
                                            <td class="text-right"><span>{{
                                                    numberWithCurrencySymbol(deuAmount)
                                                }}</span></td>
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
                    <button class="btn btn-secondary" @click.prevent="resetData">{{
                            $t('cancel')
                        }}
                    </button>
                    <button class="btn btn-primary ml-2" @click.prevent="submitData">{{
                            $t('save')
                        }}
                    </button>
                </div>
            </div>
        </form>

        <client-add-edit-modal
            v-if="isClientModalActive"
            :table-id="'client-table'"
            @closeModal="closeClientModal"
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


import {SubmitFormMixins} from "../../../Mixins/billar/SubmitFormMixins";
import {axiosPost, urlGenerator} from "../../../Helpers/AxiosHelper";
import DateFunction from "../../../../core/helpers/date/DateFunction";
import {numberWithCurrencySymbol} from "../../../Helpers/Helpers";
import {formatDateForServer} from "../../../Helpers/DateTimeHelper";
import {INVOICES, INVOICES_LIST} from "../../../Config/BillarApiUrl";

export default {
    name: 'InvoiceCreateEdit',
    mixins: [SubmitFormMixins],
    props: {
        statusList: {},
        recurringCycle: {},
        taxList: {},
    },
    data() {
        return {
            numberWithCurrencySymbol,
            INVOICES,
            selectProductId: null,
            invoiceProductDeleteModal: false,
            deletedInvoiceProductContext: null,
            isClientModalActive: false,
            isProductModalActive: false,
            productData: [],
            productDetails: [],
            formData: {
                recurring: 2,
                invoice_number: null,
                date: DateFunction.getDateFormat(new Date(), 'YYYY-MM-DD'),
                due_date: DateFunction.getDateFormat(new Date(), 'YYYY-MM-DD'),
                discount_type: 'none',
                discount: null,
                received_amount: null,
                discount_amount: 0,
                packages:null,
               from_address:null,
               to_address:null,
               is_breakdown:false,
                packageList:[
                  {id: 1, value: this.$t('bubble')},
                  {id: 2, value: this.$t('corrugated')},
                  {id: 3, value: this.$t('packing')},
                  {id: 4, value: this.$t('foam')},
                ]
            },
            recurringList: [
                {id: 1, name: 'Yes'},
                {id: 2, name: 'No'}
            ],
            discountTypeList: [
                {id: 'none', name: this.$t('discount_type')},
                {id: 'fixed', name: this.$t('fixed')},
                {id: 'percentage', name: this.$t('percentage')}
            ],
            productList: {
                url: urlGenerator('all-products'),
                per_page: 10,
                modifire: (item) => {
                    this.productData.push(item)
                    return {id: item.id, value: item.name};
                },
                loader: 'app-pre-loader',
            },
            clientOptions: {
                url: urlGenerator('client-users'),
                per_page: 10,
                loader: "app-pre-loader",
                modifire: (value) => ({id: value.id, value: value.full_name}),
            },
        }
    },
    computed: {
        productDetailsCalculated() {
            if (!this.productDetails.length) return [];
            return this.productDetails.map(product => {
                const productTaxPercentage = this.taxList.find(tax => +tax.id === +product.tax_id);
                let productTaxValue = productTaxPercentage ? +product.price * (+productTaxPercentage.value / 100) : 0;
                const productTotalAmount = (+product.price + productTaxValue) * product.quantity;
                return {...product, total_amount: productTotalAmount};
            });
        },
        filteredProData() {
            return [...new Set(this.productData.map(item => item.id))].map(id => {
                return this.productData.find(item => item.id === id);
            });
        },
        totalSummation() {
            let tax = 0;
            // let subTotal = 0;
            let subTotal = this.productDetailsCalculated.reduce((a, v) => a + +v.total_amount, 0);
            this.productDetails.forEach((product) => {
                let taxObj = this.taxList ? this.taxList.find(tax => tax.id === Number(product.tax_id)) : {};
                let productTax = typeof taxObj === 'undefined' ? 0 : parseFloat(taxObj.value);
                tax += ((product.quantity * product.price * productTax) / 100);
                // subTotal += product.amount ? parseFloat((product.amount)) : 0;
            })
            return {tax, subTotal};
        },

        totalDiscountAmount() {
            if (this.formData.discount_type === 'fixed' && this.formData.discount > 0) {
                this.formData.discount_amount = this.formData.discount
                return parseFloat(this.formData.discount);
            } else if (this.formData.discount_type === 'percentage' && this.formData.discount > 0) {
                this.formData.discount_amount = Number((this.totalSummation.subTotal * this.formData.discount) / 100)
                return Number((this.totalSummation.subTotal * this.formData.discount) / 100);
            } else if (this.formData.discount_type === 'none') {
                return this.formData.discount = null;
            }
            return 0;
        },

        totalAmount() {
            return (this.totalSummation.subTotal - this.totalDiscountAmount);
        },

        returnAmount() {
            if (this.totalAmount > this.formData.received_amount) {
                return 0;
            }
            return (this.formData.received_amount - this.totalAmount);
        },
        deuAmount() {
            if (this.totalAmount < this.formData.received_amount) {
                return 0;
            }
            return (this.totalAmount - this.formData.received_amount);
        },
    },

    methods: {
        changeProduct() {
            if (this.selectProductId) {
                let productObject = this.filteredProData.find(item => item.id === this.selectProductId);
                const checkProduct = this.productDetails.find(item => item.product_id === this.selectProductId);
                if (checkProduct) {
                    checkProduct.quantity++
                } else {
                    this.productDetails.push({
                        product_id: productObject.id,
                        name: productObject.name,
                        quantity: 1,
                        price: productObject.unit_price,
                        tax_id: null,
                        amount: (1 * productObject.unit_price),
                        packages:null
                    });
                }
            }
        },
        changeTax(product) {
            if (product.tax_id) {
                // // updating the product detail obj with the new tax value
                // const productDetailObjToUpdate = this.productDetails.find(productDetailObj => productDetailObj.id.toString() === product.id.toString());
                // productDetailObjToUpdate.tax_id = product.tax_id;

                let taxObj = this.taxList.find(item => item.id === Number(product.tax_id));
                let par = ((product.quantity * product.price) * (taxObj.value / 100));
                product.amount = ((product.quantity * product.price) + par);
            } else {
                product.amount = (product.price * product.quantity);
            }
        },
        incrementQty(product) {
            this.productDetails.find(item => item.product_id === product.product_id).quantity++
            this.changeQty(product)
        },
        decrementQty(priduct) {
            if (priduct.quantity > 1) {
                this.productDetails.find(item => item.product_id === priduct.product_id).quantity--
                this.changeQty(priduct)
            }
        },
        changeQty(product) {
            product.amount = parseFloat((product.quantity * product.price));
        },

        afterError({data}) {

            this.errors = data.errors;
            if (this.productDetails.length < 1) {
                this.$toastr.e(this.$t('your_cart_is_empty'));
            }
        },
        submitData() {
            const formData = {
                ...this.formData,
                date: formatDateForServer(this.formData.date),
                due_date: formatDateForServer(this.formData.due_date),
                discount: this.formData.discount ? this.formData.discount : 0,
                sub_total: this.totalSummation.subTotal,
                total: this.totalAmount,
                received_amount: this.formData.received_amount ? (this.formData.received_amount - this.returnAmount) : 0,
                due_amount: this.deuAmount,
                products: this.productDetailsCalculated,
            }
            this.save(formData);
        },
        resetData() {
            window.location = urlGenerator(INVOICES_LIST);
        },
        afterSuccess({data}) {
            this.$toastr.s(data.message);
            setTimeout(() => {
                window.location = urlGenerator(INVOICES_LIST);
            });
        },
        afterSuccessFromGetEditData(response) {
            this.formData = response.data;
            this.productDetails = this.formData.invoice_details.map((item) => {
                return {
                    id: item.id,
                    product_id: item.product_id,
                    name: item.product?.name,
                    quantity: item.quantity,
                    price: item.price,
                    tax_id: item.tax ? item.tax_id : null,
                    amount: (item.quantity * item.price),

                }
            });
            this.preloader = false;
        },

        getInvoiceCount() {
            this.axiosGet(`invoice-number`).then((response) => {
                let invoice = response.data.id != undefined ? response.data.id : 0
                this.formData.invoice_number = Number(invoice) + Number(window.settings.invoice_starting_number)
            })
        },
        removeProductRow(index, id) {
            let formData = {};
            formData.sub_total = this.totalSummation.subTotal;
            formData.total = this.grandTotal;
            formData.due_amount = this.deuAmount;
            formData.received_amount = this.formData.received_amount;
            formData.invoice_detail_id = id;
            formData.id = this.formData.id;
            if (this.selectedUrl) {
                if (id !== undefined) {

                    this.deletedInvoiceProductContext = {
                        payload: {
                            'url': 'invoice-details-delete',
                            'data': formData
                        },
                        callBack: () => {
                            this.productDetails.splice(index, 1)
                        }
                    }
                    this.invoiceProductDeleteModal = true
                    //this.$emit('invoiceProductDelete', deleteContext)
                } else this.productDetails.splice(index, 1);
            } else this.productDetails.splice(index, 1);
        },

        confirmDeleteInvoiceProduct() {
            axiosPost(this.deletedInvoiceProductContext['payload'].url, this.deletedInvoiceProductContext['payload'].data)
                .then((response) => {
                    this.deletedInvoiceProductContext['callBack']();
                    this.$toastr.s(response.data.message);
                    this.deletedInvoiceProductContext = null;
                    this.closeInvoiceDeleteModal();
                })
        },
        closeInvoiceDeleteModal() {
            $(`#invoice-product-delete-confirm-modal`).modal('hide');
            this.invoiceProductDeleteModal = false;
        },

        openClientModal() {
            this.isClientModalActive = true;
        },
        closeClientModal() {
            this.isClientModalActive = false;
        },

        closeProductModal() {
            this.isProductModalActive = false
        }
    },

    mounted() {
        if (!this.selectedUrl) {
            this.editorShow = true;
            this.getInvoiceCount();
        }
    }


}
</script>

<style scoped lang="scss">
  .table{
    &__item{
      &--products{
        width: 30%;
      }
      &--quantity{
        width: 20%;
      }
      &--unit_price{
        width: 15%;
      }
      &--tax{
        width: 15%;
      }
      &--total_amount{
        width: 15%;
      }
      &--total_action{
        width: 5%;
      }
    }
  }
</style>
