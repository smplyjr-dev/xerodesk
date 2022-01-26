<template>
  <a href="javascript:void(0)" class="cdci" @click="handleClick">
    <slot />
  </a>
</template>

<script>
export default {
  props: ["value"],
  name: "DropdownItem",
  inject: ["sharedState"],
  computed: {
    active: {
      get: function() {
        return this.sharedState.active;
      },
      set: function(newVal) {
        this.sharedState.active = newVal;
      }
    }
  },
  methods: {
    handleClick() {
      this.active = false;
      this.$emit("select", this.value);
    }
  }
};
</script>
