<template>
    <div class="content-wrapper">
        <div class="d-flex align-items-center justify-content-between">
            <app-breadcrumb :page-title="$t('invoices')"/>
            <div class="mb-4">

                <a href="javascript:void(0)" type="button" v-if="$can('create_invoices')"
                   class="btn btn-info btn-with-shadow pl-2"
                   @click="exportAllInvoice">
                    <app-icon name="download" class="size-20 mr-2"/>
                    {{ $t('export_all') }}
                </a>
                <a :href="AppFunction.getAppUrl('invoices/create/view')"
                   v-if="$can('create_invoices') && isMailSettingExist"
                   class="btn btn-success btn-with-shadow">
                    <app-icon name="plus" class="size-20 mr-2"/>
                    {{ $t('new_invoice') }}
                </a>
                <template v-else>
                    <a v-if="$can('create_invoices')" href="javascript:void(0)" @click="showAlertMessage"
                       class="btn btn-success btn-with-shadow">
                        <app-icon name="plus" class="size-20 mr-2"/>
                        {{ $t('new_invoice') }}
                    </a>
                </template>

            </div>
        </div>
        <div class="d-flex justify-content-between">
            <div class="w-100 mb-primary">
                <div class="text-white bg-primary shadow rounded d-flex align-items-center p-4">
                    <div class="ml-3">
                        <h5 class="mb-0">{{ $t('total_amount') }}</h5>

                        <div class="flex-shrink-0">
                            {{ numberWithCurrencySymbol(invoiceSummation.total_amount) }}
                        </div>

                    </div>
                </div>
            </div>

            <div class="w-100 mb-primary mx-4">
                <div class="text-white bg-success shadow rounded d-flex align-items-center p-4">
                    <div class="ml-3">
                        <h5 class="mb-0">{{ $t('total_paid') }}</h5>
                        <div class="flex-shrink-0">
                            {{ numberWithCurrencySymbol(invoiceSummation.paid_amount) }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-100 mb-primary">
                <div class="text-white bg-warning shadow rounded d-flex align-items-center p-4">
                    <div class="ml-3">
                        <h5 class="mb-0">{{ $t('total_due') }}</h5>
                        <div class="flex-shrink-0">
                            {{ numberWithCurrencySymbol(invoiceSummation.due_amount) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <app-table :id="tableId" :options="options" @action="getListAction"/>


        <!-- Payment Add/Edit Modal -->
        <payment-add-edit-modal
            v-if="isPaymentAddEditModalActive"
            :table-id="tableId"
            :invoice-id="invoiceId"
            @closeModal="closePaymentAddEditModal"
        />

        <invoice-payment-modal
            v-if="isInvoicePaymentModalActive"
            :table-id="tableId"
            :invoice-id="invoiceId"
            :selected-url="selectUrl"
            @closeModal="closeInvoicePaymentModal"
        />

        <!-- Client Delete Modal -->
        <app-delete-modal
            v-if="confirmationModalActive"
            modal-id="delete-confirm-modal"
            :message="$t('are_you_sure_to_delete_this_invoice')"
            @confirmed="confirmDelete"
            @cancelled="cancelledDelete"
        />

        <!-- Client Delete Modal -->


        <client-add-edit-modal
            v-if="clientModalActive"
            :table-id="tableId"
            @closeModal="closeClientModal"
        />

    </div>
</template>

<script>
import InvoiceTableMixin from '../../../Mixins/InvoiceTableMixin';
import {axiosGet, urlGenerator} from "../../../Helpers/AxiosHelper";
import {MAIL_CHECK_URL, TENANT_MAIL_CHECK_URL} from "../../../Config/ApiUrl";

export default {
    name: 'Index',
    mixins: [InvoiceTableMixin],
    data() {
        return {
            TENANT_MAIL_CHECK_URL,
            MAIL_CHECK_URL,
            isMailSettingExist: false
        }
    },
    methods: {
        exportAllInvoice() {
            window.location.replace(urlGenerator('invoice-export'))
        },
        checkMailSettings() {
            this.preloader = true;
            const url = this.alias === 'tenant' ? TENANT_MAIL_CHECK_URL : MAIL_CHECK_URL
            axiosGet(url).then(response => {
                this.isMailSettingExist = !!response.data;
            })
        },
        showAlertMessage() {
            this.$toastr.e(this.$t('no_delivery_settings_found'));
        }
    },
    mounted() {
        this.checkMailSettings();
    }
}
</script>
