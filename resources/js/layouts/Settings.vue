<template>
  <div class="layout layout-admin" :class="{ 'is-open': isOpen }">
    <Sidebar :isOpen="isOpen" @toggle-sidebar="isOpen = $event" />

    <div class="aside-backdrop" @click.self="isOpen = false"></div>

    <main>
      <section class="d-flex flex-column h-100 overflow-scroll">
        <Navbar :isOpen="isOpen" @toggle-sidebar="isOpen = $event" />

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
            <div class="col-md-3 mb-4">
              <div class="card card-body p-0">
                <ul class="setting-meta">
                  <router-link to="/settings/companies" :class="{ active: parent == 'companies' }">Companies</router-link>
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
    setParent(newValue) {
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
  },
  mounted() {
    this.setParent(this.$route);
  },
  watch: {
    $route(newValue) {
      this.setParent(newValue);
    }
  }
};
</script>

<style lang="scss">
@import "@Styles/admin.scss";
</style>
