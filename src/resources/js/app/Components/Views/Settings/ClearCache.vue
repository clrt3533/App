<template>
	<button type="button" class="btn btn-success" @click.prevent="clearCash">{{ $t('clear_cache') }}</button>
</template>

<script>

import {axiosGet} from "../../../Helpers/AxiosHelper";

export default {
	name: "ClearCache",
	data() {
		return {}
	},
	methods: {
		clearCash() {
			axiosGet('cache/clear').then(response => {
				this.$toastr.s(response.data.message);
				window.location.reload();
			})
		}
	}
}
</script>
