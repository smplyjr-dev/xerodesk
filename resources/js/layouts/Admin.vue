<template>
  <div class="layout layout-admin" :class="{ 'is-open': isOpen }">
    <AdminHeader :isOpen="isOpen" @toggle-sidebar="isOpen = $event" />

    <main>
      <AdminSidebar :isOpen="isOpen" @toggle-sidebar="isOpen = $event" />

      <section class="d-flex flex-column h-100">
        <transition name="fade" mode="out-in">
          <RouterView @toggle-sidebar="isOpen = $event" />
        </transition>

        <footer class="footer mt-auto pt-4 pb-3">
          <p class="text-muted text-sm mb-0">
            Copyright &copy; 2020 Xerodesk. All rights reserved.
          </p>
        </footer>
      </section>
    </main>

    <Notification />
  </div>
</template>

<script>
import { mapState } from "vuex";
import Notification from "@Components/neutral/Notification.vue";
import AdminHeader from "@Components/admin/Header.vue";
import AdminSidebar from "@Components/admin/Sidebar.vue";

export default {
  name: "Admin",
  components: { Notification, AdminHeader, AdminSidebar },
  metaInfo: () => ({
    title: "Live Support", // set the title on each page, this is just a fallback
    titleTemplate: `Xerodesk - %s`
  }),
  data: () => ({
    isOpen: false
  }),
  computed: {
    ...mapState("auth", ["user"])
  },
  methods: {
    setupListeners() {
      const self = this;

      // pusher init
      const PUSHER = new Pusher(process.env.MIX_PUSHER_APP_KEY, {
        cluster: process.env.MIX_PUSHER_APP_CLUSTER,
        encrypted: true
      });

      // channels listener
      const CH_SESSION = PUSHER.subscribe("session");

      CH_SESSION.bind(`session.transfered.from.${this.user.id}`, async data => {
        await self.$store.dispatch("clients/fetchClientsFromSessions");

        this.$store.dispatch("notifications/addNotification", {
          variant: "bg-info",
          icon: "fa-exclamation-circle",
          title: "Notice!",
          body: "One of your client has been transfered to different agent."
        });
      });

      CH_SESSION.bind(`session.transfered.to.${this.user.id}`, async data => {
        await self.$store.dispatch("clients/fetchClientsFromSessions");

        this.$store.dispatch("notifications/addNotification", {
          variant: "bg-info",
          icon: "fa-exclamation-circle",
          title: "Notice!",
          body: "One of the client has been transfered to you."
        });
      });
    }
  },
  mounted() {
    this.setupListeners();
  }
};
</script>

<style lang="scss">
@import "@Styles/admin.scss";
</style>
