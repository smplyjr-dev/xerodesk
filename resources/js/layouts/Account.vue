<template>
  <div class="layout-admin" :class="{ 'is-open': isOpen }">
    <Sidebar :isOpen="isOpen" @toggleSidebar="isOpen = $event" />
    <div class="aside-backdrop" @click.self="isOpen = false"></div>

    <main>
      <section class="d-flex flex-column h-100 overflow-scroll">
        <Navbar :isOpen="isOpen" @toggleSidebar="isOpen = $event" />

        <div class="account-page">
          <div class="aside">
            <div class="card">
              <div class="card-header bg-primary text-white">
                <div class="flex-center-between">
                  <span>Menu</span>

                  <a class="link-unstyled text-white btn-toggler" ref="btn-toggler" href="javascript:void(0)" data-toggle="collapse" data-target="#account-navs" aria-expanded="false" aria-controls="account-navs">
                    <i class="fas fa-chevron-up"></i>
                  </a>
                </div>
              </div>
              <div class="collapse show" id="account-navs">
                <div class="card-body">
                  <h5 class="font-weight-semi">Account</h5>
                  <p class="text-muted">You can manage your personal details in this section.</p>

                  <div class="nav">
                    <router-link class="nav-link flex-center-between" to="/settings/account/photo">
                      <span>Profile Photo</span>
                      <i class="fas fa-chevron-right"></i>
                    </router-link>
                    <router-link class="nav-link flex-center-between" to="/settings/account/profile">
                      <span>Profile Information</span>
                      <i class="fas fa-chevron-right"></i>
                    </router-link>
                    <router-link class="nav-link flex-center-between" to="/settings/account/password">
                      <span>Password Update</span>
                      <i class="fas fa-chevron-right"></i>
                    </router-link>
                  </div>

                  <h5 class="font-weight-semi">Live Chat</h5>
                  <p class="text-muted">This settings is intended to help you boost your productivity.</p>

                  <div class="nav">
                    <router-link class="nav-link flex-center-between" to="/settings/account/replies">
                      <span>Saved Replies</span>
                      <i class="fas fa-chevron-right"></i>
                    </router-link>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="main">
            <div class="page-title" v-if="pageTitle">
              <h4 v-html="pageTitle"></h4>
            </div>

            <RouterView @toggleSidebar="isOpen = $event" @setTitle="pageTitle = $event" />

            <footer class="footer mt-auto py-3 px-4">
              <p class="text-muted text-sm mb-0">Copyright &copy; {{ currentYear }} Xerodesk. All rights reserved.</p>
            </footer>
          </div>
        </div>
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
  name: "Account",
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
      pageTitle: "",
      parent: null
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
    },
    setParent(newValue) {
      const parent = newValue.path.split("/")[3];

      this.parent = parent;
    }
  },
  mounted() {
    this.$setTogglerEvent();
    this.setupListeners();
    this.setParent(this.$route);
  },
  watch: {
    $route(newValue) {
      this.setParent(newValue);
    }
  },
  destroyed() {
    window.pusher.unsubscribe("session");
    window.pusher.unsubscribe("message");
  }
};
</script>

<style lang="scss">
@import "@Styles/layouts/admin.scss";
</style>
