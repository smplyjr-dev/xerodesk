<template>
  <transition name="cdc">
    <div v-if="active" class="cdc">
      <div class="filter" v-show="showFilter"><slot name="filter" /></div>
      <div class="content" v-show="showContent"><slot name="content" /></div>
    </div>
  </transition>
</template>

<script>
export default {
  name: "AppDropdownContent",
  inject: ["sharedState"],
  data: () => ({
    showFilter: false,
    showContent: false
  }),
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
    setShowSlots() {
      this.showFilter = this.$slots.filter?.[0];
      this.showContent = this.$slots.content?.[0];
    }
  },
  mounted() {
    this.setShowSlots();
  }
};
</script>
