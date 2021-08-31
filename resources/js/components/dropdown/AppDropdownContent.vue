<template>
  <transition name="cdc">
    <div v-if="active" class="cdc">
      <slot />
    </div>
  </transition>
</template>

<script>
export default {
  name: "AppDropdownContent",
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
  }
};
</script>
