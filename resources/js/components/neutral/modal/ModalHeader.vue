<template>
  <div class="modal-header">
    <slot />

    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="$emit('close')" v-if="close">
      <InlineSvg name="svg/mdi/close.svg" color="#fff" size="1rem" />
    </button>
  </div>
</template>

<script>
export default {
  props: {
    close: {
      type: Boolean,
      default: true,
      required: false
    }
  },
  name: "ModalHeader"
};
</script>
