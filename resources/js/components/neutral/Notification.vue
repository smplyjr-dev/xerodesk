<template>
  <div class="notif-wrapper">
    <transition-group class="notif-group" name="staggered" tag="div" move-class="staggered-move">
      <div class="notif shadow-lg" :class="n.variant" v-for="n in sortedNotifications" :key="n.id">
        <div class="notif-progress" :style="{ backgroundColor: backgroundShade(n.variant), animationDuration: animationDuration(n.duration) }"></div>

        <div class="notif-header">
          <div class="notif-title">
            <i class="fa mr-2" :class="n.icon"></i>
            <p class="font-weight-semi mb-0" v-html="n.title"></p>
          </div>
          <button type="button" class="close" @click="$store.commit('notifications/REMOVE_NOTIFICATION', n.id)">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="notif-body">
          <p class="mb-0" v-html="n.body"></p>
        </div>
      </div>
    </transition-group>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  name: "Notification",
  data: () => ({
    shade: {
      "bg-primary": "#2a6aff",
      "bg-secondary": "#484848",
      "bg-success": "#58a500",
      "bg-info": "#0a3b9e",
      "bg-warning": "#d6a100",
      "bg-danger": "#d63c3c",
      "bg-light": "#cecece",
      "bg-dark": "#1b252e"
    }
  }),
  computed: {
    ...mapGetters("notifications", ["sortedNotifications"])
  },
  methods: {
    animationDuration(duration) {
      return `${duration}ms`;
    },

    backgroundShade(backgroundColor) {
      return this.shade[backgroundColor];
    }
  }
};
</script>
