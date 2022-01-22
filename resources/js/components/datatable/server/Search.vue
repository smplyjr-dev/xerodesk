<template>
  <div class="dts-search">
    <label class="mb-0 mr-2" for="search">Search:</label>
    <input class="form-control form-control-sm" id="search" type="text" v-model="search" @input="$emit('onSearch', search)" />
  </div>
</template>

<script>
export default {
  props: ["tableData"],
  data() {
    return {
      search: ""
    };
  },
  mounted() {
    this.search = this.tableData.search;
  }
};
</script>
