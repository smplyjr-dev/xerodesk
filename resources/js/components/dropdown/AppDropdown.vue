<template>
  <div class="cd" :class="{ disabled: disabled }" v-on-clickaway="away">
    <div class="d-flex align-items-center" @click="toggle">
      <slot name="value">--</slot>
      <InlineSvg v-show="carret" name="template/mdi-chevron-down.svg" size="1rem" class="ml-1" :style="`transform: rotate(${sharedState.active ? 180 : 0}deg)`" />
    </div>

    <slot />
  </div>
</template>

<script>
import { mixin as clickaway } from "vue-clickaway";

export default {
  props: {
    carret: {
      type: Boolean,
      required: false,
      default: true
    },
    disabled: {
      type: Boolean,
      required: false,
      default: false
    },
    position: {
      type: String,
      required: false,
      default: "left"
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
        active: false,
        position: null
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
  },
  created() {
    this.sharedState.position = this.position;
  }
};
</script>
