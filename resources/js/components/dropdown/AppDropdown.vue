<template>
  <div v-on-clickaway="away" class="cd" :class="{ disabled: disabled }">
    <div class="d-flex align-items-center" @click="toggle">
      <span class="mb-0">
        <slot name="value">--</slot>
      </span>

      <InlineSvg name="template/mdi-chevron-down.svg" size="1rem" class="ml-1" :style="`transform: rotate(${sharedState.active ? 180 : 0}deg)`" />
    </div>

    <slot />
  </div>
</template>

<script>
import { mixin as clickaway } from "vue-clickaway";

export default {
  props: {
    disabled: {
      type: Boolean,
      default: false,
      required: false
    }
  },
  name: "AppDropdown",
  mixins: [clickaway],
  provide() {
    return {
      sharedState: this.sharedState
    };
  },
  data() {
    return {
      sharedState: {
        active: false
      }
    };
  },
  methods: {
    toggle() {
      if (this.disabled === false) {
        this.sharedState.active = !this.sharedState.active;
      }
    },
    away() {
      if (this.disabled === false) {
        this.sharedState.active = false;
        this.$emit("away");
      }
    }
  }
};
</script>
