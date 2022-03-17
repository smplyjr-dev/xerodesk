<template>
  <div class="form-group flex-center-between">
    <div class="flex-grow-1">
      <p class="mb-0">{{ title }} <span class="text-danger">*</span></p>
      <p class="mb-0 text-muted">{{ sub_title }}</p>
    </div>
    <div class="color-picker">
      <div class="trigger" @click="isVisible = !isVisible" :style="{ background: value }">
        &nbsp;
        <button type="button" class="btn btn-toggler">
          <i class="fas fa-caret-down"></i>
        </button>
      </div>
      <Chrome @input="$emit('input', $event.hex)" :value="value" v-show="isVisible" />
    </div>
  </div>
</template>

<script>
import { mixin as clickaway } from "vue-clickaway";
import { Chrome } from "vue-color";

export default {
  mixins: [clickaway],
  props: ["title", "sub_title", "value"],
  components: { Chrome },
  data: () => ({
    isVisible: false
  }),
  methods: {
    away() {
      // this.isVisible = !this.isVisible;
    }
  }
};
</script>
