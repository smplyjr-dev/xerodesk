<template>
  <div class="modal-header">
    <slot />

    <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="$emit('close')" v-if="close">
      <span aria-hidden="true">&times;</span>
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
