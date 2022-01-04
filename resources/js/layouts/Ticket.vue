<template>
  <div class="layout layout-admin" :class="{ 'is-open': isOpen }">
    <Sidebar :isOpen="isOpen" @toggle-sidebar="isOpen = $event" />

    <div class="aside-backdrop" @click.self="isOpen = false"></div>

    <main>
      <section class="d-flex flex-column h-100 overflow-scroll">
        <Navbar :isOpen="isOpen" @toggle-sidebar="isOpen = $event" />
        <RouterView @toggle-sidebar="isOpen = $event" />
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
    title: "Live Chat", // set the title on each page, this is just a fallback
    titleTemplate: `FiliChat - %s`
  }),
  data() {
    return {
      isOpen: false
    };
  },
  computed: {
    ...mapState("auth", ["user"]),

    session: {
      get() {
        return this.$store.state.sessions.session;
      },
      set(newValue) {
        this.$store.state.sessions.session = {
          ...this.$store.state.sessions.session,
          ...newValue
        };
      }
    }
  },
  methods: {
    setupListeners() {
      // channel to subscribe
      const CH_SESSION = window.pusher.subscribe("session");
      const CH_MESSAGE = window.pusher.subscribe("message");

      CH_SESSION.bind(`session.transferred.from.${this.user.id}`, async (data) => {
        this.$store.dispatch("notifications/addNotification", {
          variant: "bg-success",
          icon: "fa-check",
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

      CH_SESSION.bind(`session.update`, async (data) => {
        let { session } = data;
        if (this.session.session == session.session) this.session = { ...this.session, ...session };
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
