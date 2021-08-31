<template>
  <div class="d-flex justify-content-between align-items-center flex-wrap">
    <div class="control d-flex align-items-center mb-4">
      Show
      <div class="select mx-2">
        <select class="custom-select custom-select-sm" v-model="length" @change="$emit('onSelect', length)">
          <option v-for="(records, index) in perPage" :key="index" :value="records">{{ records }}</option>
        </select>
      </div>
      entries
    </div>

    <div class="search mb-4">
      <div class="d-flex align-items-center">
        <label class="mb-0 mr-2" for="search">Search:</label>
        <input class="form-control form-control-sm" id="search" type="text" v-model="search" @input="$emit('onSearch', search)" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      search: "",
      length: 10,
      perPage: [10, 20, 50, 100]
    };
  }
};
</script>
