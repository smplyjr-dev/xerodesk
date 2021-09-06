<template>
  <div :class="alertClass" role="alert">
    <div class="alert-icon">
      <i class="fa fa-fw" :class="alertIcon"></i>
    </div>
    <div class="alert-message">
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  name: "FormAlert",
  props: ["variant", "message"],
  computed: {
    alertClass() {
      return "form-alert variant-" + this.variant;
    },
    alertIcon() {
      switch (this.variant) {
        case "success":
          return "fa-check-circle";
          break;
        case "danger":
          return "fa-exclamation-circle";
          break;
        case "warning":
          return "fa-exclamation-circle";
          break;
        case "info":
          return "fa-info-circle";
          break;
        default:
          return "fa-info-circle";
          break;
      }
    }
  }
};
</script>
