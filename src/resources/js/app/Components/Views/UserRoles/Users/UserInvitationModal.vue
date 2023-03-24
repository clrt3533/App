<template>
    <modal
        :modal-id="userAndRoles.users.inviteModalId"
        :title="modalTitle"
        :preloader="preloader"
        :modal-scroll="false"
        @submit="submit"
        @closeModal="closeModal">
        <template slot="body">
            <app-overlay-loader v-if="preloader"/>
            <form ref="form"
                  :data-url='hasData ? `admin/auth/users/attach-roles/${userAndRoles.rowData.id}` : `/admin/auth/users/invite-user`'
                  :class="{'loading-opacity': preloader}">
                <div class="form-group row align-items-center" v-if="!hasData">
                    <label for="userEmail" class="col-sm-3 mb-0">
                        {{ $t('email') }}
                    </label>
                    <app-input id="userEmail"
                               class="col-sm-9"
                               type="email"
                               v-model="user.email"
                               :placeholder="$t('enter_user_email')"
                               :error-message="$errorMessage(errors, 'email')"
                               :required="true"/>
                </div>
                <div class="form-group row align-items-center mb-0">
                    <label for="roles" class="col-sm-3 mb-0">
                        {{ $t('role') }}
                    </label>
                    <app-input id="roles"
                               class="col-sm-9"
                               type="multi-select"
                               :placeholder="$t('select_one')"
                               :list="roleLists"
                               list-value-field="name"
                               :isAnimatedDropdown="true"
                               :error-message="$errorMessage(errors, 'roles')"
                               v-model="roles"
                               :required="true"/>
                </div>
            </form>
        </template>
    </modal>
</template>

<script>
import {FormMixin} from '../../../../../core/mixins/form/FormMixin';
import {ModalMixin} from '../../../../Mixins/ModalMixin';
import {UserAndRoleMixin} from '../Mixins/UserAndRoleMixin';
import * as actions from '../../../../Config/ApiUrl';
import {SYSTEMROLES} from "../../../../Config/ApiUrl";
import {optional} from "../../../../../../../package/installer/src/resources/js/Helpers/Helpers";


export default {
    name: "UserInvitationModal",
    mixins: [FormMixin, ModalMixin, UserAndRoleMixin],
    data() {
        return {
            user: {},
            errors: {},
            roles: [],
            roleLists: [],
            hasData: false,
            modalTitle: this.$t('invite_users'),
        }
    },
    created() {
        if (!_.isEmpty(this.userAndRoles.rowData)) {
            this.hasData = true;
            this.modalTitle = this.$t('manage_role'),
                this.user.email = this.userAndRoles.rowData.email;
            this.roles = this.userAndRoles.rowData.roles.map(function (roles) {
                return roles.id;
            });
        }
        this.getRoles();
    },
    methods: {

        submit() {
            this.user.roles = this.roles;
            this.save(this.user);
        },

        afterError(response) {
            this.message = '';
            this.errors = optional(response.data, 'errors') || {};
            if (response?.status != 422)
                this.$toastr.e(response.data.message || response.statusText)
        },
        afterFinalResponse() {
            this.preloader = false;
        },
        afterSuccess(res) {
            this.$toastr.s(res.data.message);
            this.reLoadTable();
            this.closeModal();
        },
        getRoles() {
            let url = actions.SYSTEMROLES;

            this.preloader = this.hasData ? true : false;

            this.axiosGet(url).then(response => {

                this.roleLists = response.data;

            }).catch(({response}) => {

            }).finally(() => {
                this.preloader = false;
            });
        }
    }
}
</script>