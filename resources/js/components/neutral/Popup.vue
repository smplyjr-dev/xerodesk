<template>
  <popper
    :visible-arrow="arrow"
    trigger="click"
    :options="{
      placement: placement,
      modifiers: {
        offset: {
          offset: offset
        }
      }
    }"
    root-class="popper"
    ref="popper"
  >
    <div class="popper-content">
      <slot name="content" />
    </div>

    <slot name="reference" slot="reference" />
  </popper>
</template>

<script>
import Popper from "vue-popperjs";

export default {
  props: {
    arrow: {
      type: Boolean,
      default: true
    },
    offset: {
      type: String,
      default: "140px,10px"
    },
    placement: {
      type: String,
      default: "top"
    },
    toggle: {
      type: String,
      default: "hide"
    }
  },
  name: "Popup",
  components: { Popper },
  watch: {
    toggle(value1) {
      if (value1 == "hide") this.$refs["popper"].doClose();
    }
  }
};
</script>
