<template>
    <div class="content-wrapper">
        <div class="d-flex align-items-center justify-content-between">
            <app-breadcrumb :page-title="$t('estimates')"/>
            <div class="mb-4">
                <a :href="AppFunction.getAppUrl('estimates/create/view')"
                   v-if="$can('create_estimates') && isMailSettingExist"
                   class="btn btn-success btn-with-shadow">
                    <app-icon name="plus" class="size-20 mr-2"/>
                    {{ $t('add_estimates') }}
                </a>
                <template v-else>
                    <a v-if="$can('create_estimates')" href="javascript:void(0)" @click="showAlertMessage"
                       class="btn btn-success btn-with-shadow">
                        <app-icon name="plus" class="size-20 mr-2"/>
                        {{ $t('add_estimates') }}
                    </a>
                </template>

            </div>
        </div>


        <app-table :id="tableId" :options="options" @action="getListAction"/>

        <!-- Estimate Delete Modal -->
        <app-delete-modal
            v-if="confirmationModalActive"
            modal-id="delete-confirm-modal"
            :message="$t('are_you_sure_to_delete_this_estimates')"
            @confirmed="confirmDelete"
            @cancelled="cancelledDelete"
        />
        <!-- Estimate Delete Modal -->

        <app-confirmation-modal v-if="isShowCloneModal"
                                icon="paperclip"
                                :title="$t('clone_invoice')"
                                :sub-title="$t('invoice_clone_note')"
                                message="Are you sure?"
                                modal-class="primary"
                                modal-id="delete-modal"
                                @confirmed="confirmed"
                                @cancelled="cancelled"/>

    </div>
</template>

<script>
import {urlGenerator, axiosGet} from "../../../Helpers/AxiosHelper";
import EstimateTableMixin from "../../../Mixins/EstimateTableMixin";
import {TENANT_MAIL_CHECK_URL, MAIL_CHECK_URL} from "../../../Config/ApiUrl";

export default {
    name: 'Index',
    mixins: [EstimateTableMixin],
    data() {
        return {
            isShowCloneModal: false,
            TENANT_MAIL_CHECK_URL,
            MAIL_CHECK_URL,
            isMailSettingExist: false,
            estimateId: null
        }
    },
    methods: {
        exportAllInvoice() {
            window.location.replace(urlGenerator('invoice-export'))
        },
        confirmed() {
            this.isShowCloneModal = false
            axiosGet(`clone-as-invoice/${this.estimateId}`).then((response) => {
                this.$toastr.s(response.data.message);
                this.$hub.$emit('reload-' + this.tableId);
            })
        },
        cancelled() {
            this.isShowCloneModal = false
            this.estimateId = null
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