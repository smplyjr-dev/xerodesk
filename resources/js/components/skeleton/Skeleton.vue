<template>
  <div v-if="show" class="skeleton" :style="{ width: w, height: h, 'border-radius': r }"></div>
  <span v-else>
    <slot />
  </span>
</template>

<script>
export default {
  props: {
    show: {
      type: Boolean,
      required: true,
      default: true
    },
    w: {
      default: "1.75rem",
      required: false,
      type: String
    },
    h: {
      default: "1.75rem",
      required: false,
      type: String
    },
    r: {
      default: "4px",
      required: false,
      type: String
    }
  },
  name: "Skeleton",
  computed: {
    passed() {
      return !!this.$slots.default[0].text.length;
    }
  }
};
</script>

<style lang="scss" scoped>
.skeleton {
  background: #dfdfdf;
  position: relative;
  overflow: hidden;

  &::before {
    content: "";
    display: block;
    position: absolute;
    left: -150px;
    top: 0;
    height: 100%;
    width: 150px;
    background: linear-gradient(to right, transparent 0%, #e8e8e8 50%, transparent 100%);
    animation: load 1s cubic-bezier(0.4, 0, 0.2, 1) infinite;
  }
}

@keyframes load {
  from {
    left: -150px;
  }
  to {
    left: 100%;
  }
}
</style>
