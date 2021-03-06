<template>
  <div class="layout-admin" :class="{ 'is-open': isOpen }">
    <Sidebar :isOpen="isOpen" @toggleSidebar="isOpen = $event" />
    <div class="aside-backdrop" @click.self="isOpen = false"></div>

    <main>
      <section class="d-flex flex-column h-100 overflow-scroll">
        <Navbar :isOpen="isOpen" @toggleSidebar="isOpen = $event" />

        <div class="page-title" v-if="pageTitle">
          <h4 v-html="pageTitle"></h4>
        </div>

        <div class="container-fluid px-4">
          <div class="row">
            <div class="col-md-3">
              <div class="nav flex-column nav-pills">
                <router-link class="nav-link" to="/mail"><i class="fas fa-fw fa-inbox mr-2"></i> Inbox</router-link>
                <router-link class="nav-link" to="/mail/sent"><i class="fas fa-fw fa-paper-plane mr-2"></i> Sent</router-link>
                <router-link class="nav-link" to="/mail/draft"><i class="fas fa-fw fa-file mr-2"></i> Draft</router-link>
              </div>
            </div>
            <div class="col-md-9">
              <RouterView @toggleSidebar="isOpen = $event" @setTitle="pageTitle = $event" />
            </div>
          </div>
        </div>

        <footer class="footer mt-auto py-3 px-4">
          <p class="text-muted text-sm mb-0">Copyright &copy; {{ currentYear }} Xerodesk. All rights reserved.</p>
        </footer>
      </section>
    </main>

    <Notification />
  </div>
</template>

<script>
import { mapState } from "vuex";
import Notification from "@Components/neutral/Notification.vue";
import Navbar from "@Components/admin/Navbar.vue";
import Sidebar from "@Components/admin/Sidebar.vue";

export default {
  name: "Admin",
  components: { Notification, Navbar, Sidebar },
  metaInfo: () => ({
    title: "Live Support", // set the title on each page, this is just a fallback
    titleTemplate: `Xerodesk - %s`
  }),
  data() {
    let now = new Date();

    return {
      isOpen: false,
      currentYear: now.getFullYear(),
      pageTitle: ""
    };
  },
  computed: {
    ...mapState("auth", ["user"]),
    ...mapState("sessions", ["session"])
  },
  methods: {
    setupListeners() {
      // channel to subscribe
      const CH_SESSION = window.pusher.subscribe("session");
      const CH_MESSAGE = window.pusher.subscribe("message");

      CH_SESSION.bind(`session.transferred.from.${this.user.id}`, async (data) => {
        this.$store.dispatch("notifications/addNotification", {
          variant: "bg-success",
          icon: "fa-check-circle",
          title: "Success!",
          body: "You have successfully transferred this ticket."
        });

        // update the session from store
        this.$store.state.sessions.session.user_id = this.new_user_id;
      });

      CH_SESSION.bind(`session.transferred.to.${this.user.id}`, async (data) => {
        this.$store.dispatch("notifications/addNotification", {
          variant: "bg-info",
          icon: "fa-exclamation-circle",
          title: "Notice!",
          body: "A ticket has been transferred to you."
        });

        // update the session from store
        if (this.$store.state.sessions.session.session == data.session) {
          this.$store.state.sessions.session.user_id = data.new_user_id;
        }
      });

      CH_MESSAGE.bind(`message.from.client`, async (data) => {
        let { message, session } = data;

        if ("session" in this.session && this.session.session == session) {
          // mart it as read
          axios.put(`/portal/session/${session}/seen`);

          this.$store.commit("messages/PUSH_MESSAGE", { ...message, attachments: [] });
        }
      });

      CH_MESSAGE.bind(`attachment.created`, async (data) => {
        let { attachment, client, message, session } = data;

        this.$store.commit("messages/INSERT_ATTACHMENT", {
          ...message,
          attachment
        });
      });
    }
  },
  mounted() {
    this.setupListeners();
  },
  destroyed() {
    window.pusher.unsubscribe("session");
    window.pusher.unsubscribe("message");
  }
};
</script>

<style lang="scss" scoped>
@import "@Styles/layouts/admin.scss";

.router-link-exact-active {
  color: #ffffff;
  background-color: #2c85b9;
}
</style>
