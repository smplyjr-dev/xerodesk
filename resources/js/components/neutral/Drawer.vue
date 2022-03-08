<template>
  <transition name="fade">
    <div class="drawer-backdrop" v-show="toggle" @click.self="$emit('toggleDrawer', false)">
      <transition :name="position">
        <div class="drawer-container" :class="`drawer-container--${position}`" v-show="toggle">
          <div class="drawer-close" @click="$emit('toggleDrawer', false)">
            <InlineSvg name="svg/mdi/close.svg" color="#c6c6c6" size="100%" />
          </div>

          <slot />
        </div>
      </transition>
    </div>
  </transition>
</template>

<script>
export default {
  props: {
    toggle: {
      type: Boolean,
      default: false
    },
    position: {
      type: String,
      default: "right"
    }
  },
  name: "Drawer"
};
</script>
