<template>
  <div class="layout layout-admin" :class="{ 'is-open': isOpen }">
    <Sidebar :isOpen="isOpen" @toggle-sidebar="isOpen = $event" />

    <div class="aside-backdrop" @click.self="isOpen = false"></div>

    <main>
      <section class="d-flex flex-column h-100 overflow-scroll">
        <Navbar :isOpen="isOpen" @toggle-sidebar="isOpen = $event" />

        <transition name="fade" mode="out-in">
          <div class="container-fluid px-4 mt-4">
            <div class="row">
              <div class="col-12">
                <div class="page-title">
                  <div>
                    <h5 class="mb-2">{{ meta.title }}</h5>
                    <p class="text-secondary">{{ meta.description }}</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <div class="card card-body p-0">
                  <ul class="setting-meta">
                    <router-link to="/settings/company" :class="{ active: parent == 'company' }">Company</router-link>
                    <router-link to="/settings/groups" :class="{ active: parent == 'groups' }">Groups</router-link>
                    <router-link to="/settings/roles" :class="{ active: parent == 'roles' }">Roles</router-link>
                    <router-link to="/settings/slas" :class="{ active: parent == 'slas' }">Service-level Agreements</router-link>
                    <router-link to="/settings/users" :class="{ active: parent == 'users' }">Users</router-link>
                  </ul>
                </div>
              </div>
              <RouterView @meta="meta = $event" @toggle-sidebar="isOpen = $event" />
            </div>
          </div>
        </transition>

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
import SettingMeta from "@Components/admin/settings/SettingMeta.vue";

export default {
  name: "Admin",
  components: { Notification, Navbar, Sidebar, SettingMeta },
  metaInfo: () => ({
    title: "Live Support", // set the title on each page, this is just a fallback
    titleTemplate: `XMCIT - %s`
  }),
  data() {
    let now = new Date();

    return {
      isOpen: false,
      currentYear: now.getFullYear(),
      parent: "users",
      meta: {}
    };
  },
  computed: {
    ...mapState("auth", ["user"]),
    ...mapState("sessions", ["session"])
  },
  methods: {
    setupListeners() {
      // pusher init
      const PUSHER = new Pusher(process.env.MIX_PUSHER_APP_KEY, {
        cluster: process.env.MIX_PUSHER_APP_CLUSTER,
        encrypted: true
      });

      // channel to subscribe
      const CH_SESSION = PUSHER.subscribe("session");
      const CH_MESSAGE = PUSHER.subscribe("message");

      CH_SESSION.bind(`session.transferred.from.${this.user.id}`, async data => {
        this.$store.dispatch("notifications/addNotification", {
          variant: "bg-success",
          icon: "fa-check",
          title: "Success!",
          body: "You have successfully transferred this ticket."
        });

        // update the session from store
        this.$store.state.sessions.session.user_id = this.new_user_id;
      });

      CH_SESSION.bind(`session.transferred.to.${this.user.id}`, async data => {
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

      CH_MESSAGE.bind(`message.from.client`, async data => {
        let { message, session } = data;

        if ("session" in this.session && this.session.session == session) {
          // mart it as read
          axios.put(`/portal/session/${session}/seen`);

          this.$store.commit("messages/PUSH_MESSAGE", { ...message, attachments: [] });
        }
      });

      CH_MESSAGE.bind(`attachment.created`, async data => {
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
  watch: {
    $route(newValue) {
      const parent = newValue.path.split("/")[2];

      switch (parent) {
        case "slas":
          this.meta.title = "Service-level Agreement";
          this.meta.description = "Set the level of your SLA's in here.";
          break;

        case "company":
          this.meta.title = "Company";
          this.meta.description = "You may edit your company details in here.";
          break;

        case "groups":
          this.meta.title = "Groups";
          this.meta.description = "You can organize your users by attaching them to a group in here.";
          break;

        case "roles":
          this.meta.title = "Roles";
          this.meta.description = "Want to assign some role to a user? You can do that in here.";
          break;

        case "users":
          this.meta.title = "Users";
          this.meta.description = "Below are the list of your users. You can either create a user, update an existing one or resend a verification link.";
          break;
      }

      this.parent = parent;
    }
  }
};
</script>

<style lang="scss">
@import "@Styles/admin.scss";
</style>
