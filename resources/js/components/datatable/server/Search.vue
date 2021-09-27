<template>
  <div class="search mb-4">
    <div class="d-flex align-items-center">
      <label class="mb-0 mr-2" for="search">Search:</label>
      <input class="form-control form-control-sm" id="search" type="text" v-model="search" @input="$emit('onSearch', search)" />
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      search: ""
    };
  }
};
</script>
