<template>
  <transition name="cdc">
    <div v-if="active" class="cdc" :class="position" :style="`min-width: ${minWidth}`">
      <div class="filter" v-show="showFilter"><slot name="filter" /></div>
      <div class="content" v-show="showContent"><slot name="content" /></div>
    </div>
  </transition>
</template>

<script>
export default {
  props: {
    minWidth: {
      type: String,
      required: false,
      default: "200px"
    }
  },
  name: "DropdownContent",
  inject: ["sharedState"],
  data: () => ({
    showFilter: false,
    showContent: false
  }),
  computed: {
    active: {
      get: function () {
        return this.sharedState.active;
      },
      set: function (newVal) {
        this.sharedState.active = newVal;
      }
    },
    position: {
      get: function () {
        return this.sharedState.position;
      },
      set: function (newVal) {
        this.sharedState.position = newVal;
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
