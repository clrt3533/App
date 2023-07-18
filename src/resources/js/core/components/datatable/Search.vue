<template>
  <div class="form-group form-group-with-search d-flex align-items-center">
    <span :key="'search'" class="form-control-feedback">
      <app-icon name="search" />
    </span>
    <input
      type="text"
      class="form-control"
      v-model="searchValue"
      :placeholder="$t('search')"
      @keydown.enter.prevent="getSearchValue"
    />
  </div>
</template>
  
<script>
import VueMobileDetection from "vue-mobile-detection";
import { useMobileDetection } from "vue3-mobile-detection";

export default {
  name: "AppSearch",
  data() {
    return {
      searchValue: "",
      deviceType: null,
      isMobile: false,
    };
  },
  watch: {
    searchValue: function (value) {
      console.log("Device: ", this.isMobile);
      if (value.length >= 3 && this.isMobile) {
        this.getSearchValue();
      }
    },
  },
  methods: {
    getSearchValue() {
      this.$emit("input", this.searchValue);
    },
  },
  mounted() {
    const { isMobile } = useMobileDetection();
    this.isMobile = isMobile();
  },
};
</script>
  