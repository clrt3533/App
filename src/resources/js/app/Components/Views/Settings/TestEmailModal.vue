<template>
	<modal :modal-id="modalId"
	       :title="$t('send_test_email')"
	       :preloader="preloader"
	       :submit-button="$t('send')"
	       @submit="submit"
	       @closeModal="closeModal">
		<template slot="body">
			<form class="mb-0" :class="{'loading-opacity': preloader}" ref="form" data-url="test-mail/send">
				<div class="form-group">
					<label for="email">{{ $t('email_address') }}</label>
					<app-input id="email"
					           v-model="formData.email"
					           :error-message="$errorMessage(errors, 'email')"
					           :placeholder="$t('email_address')"
					           type="email"/>
				
				</div>
				<div class="form-group">
					<label for="subject">{{ $t('subject') }}</label>
					<app-input id="subject"
					           v-model="formData.subject"
					           :error-message="$errorMessage(errors, 'subject')"
					           :placeholder="$t('subject')"
					           type="text"/>
				
				</div>
				
				<div class="form-group">
					<label for="message">{{ $t('message') }}</label>
					<app-input id="message"
					           v-model="formData.message"
					           :error-message="$errorMessage(errors, 'message')"
					           :placeholder="$t('message')"
					           type="textarea"/>
				
				</div>
			</form>
		</template>
	</modal>
</template>

<script>

import {SubmitFormMixins} from "../../../Mixins/billar/SubmitFormMixins";

export default {
	name: "TestEmailModal",
	mixins:[SubmitFormMixins],
	data(){
		return{
			modalId:'test-modal',
			formData:{}
		}
	},
	methods:{
		submit(){
			this.save(this.formData)
		}
	}
}
</script>

<style scoped>

</style>