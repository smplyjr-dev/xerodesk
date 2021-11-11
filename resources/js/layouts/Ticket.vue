<template>
  <div class="layout layout-admin" :class="{ 'is-open': isOpen }">
    <Sidebar :isOpen="isOpen" @toggle-sidebar="isOpen = $event" />

    <div class="aside-backdrop" @click.self="isOpen = false"></div>

    <main>
      <section class="d-flex flex-column h-100 overflow-scroll">
        <Navbar :isOpen="isOpen" @toggle-sidebar="isOpen = $event" />

        <transition name="fade" mode="out-in">
          <RouterView @toggle-sidebar="isOpen = $event" />
        </transition>
      </section>
    </main>

    <Notification />
  </div>
</template>

<script>
import Notification from "@Components/neutral/Notification.vue";
import Navbar from "@Components/admin/Navbar.vue";
import Sidebar from "@Components/admin/Sidebar.vue";

export default {
  name: "Admin",
  components: { Notification, Navbar, Sidebar },
  metaInfo: () => ({
    title: "Live Chat", // set the title on each page, this is just a fallback
    titleTemplate: `XMCIT - %s`
  }),
  data() {
    return {
      isOpen: false
    };
  },
  computed: {}
};
</script>
