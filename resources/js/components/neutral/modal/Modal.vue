<template>
  <div :id="mId" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" :class="mClass">
      <div class="modal-content">
        <slot />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    mId: {
      type: String,
      default: null,
      required: true
    },
    mClass: {
      type: String,
      default: null,
      required: false
    }
  },
  name: "Modal"
};
</script>
