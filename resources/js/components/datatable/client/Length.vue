<template>
  <div class="dts-length">
    <!-- Show -->
    <div class="select">
      <select class="custom-select custom-select-sm" v-model="length" @change="$emit('onSelect', length)">
        <option v-for="(records, index) in perPage" :key="index" :value="records">{{ records }}</option>
      </select>
    </div>
    <!-- entries -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      length: 10,
      perPage: [10, 20, 50, 100]
    };
  }
};
</script>
