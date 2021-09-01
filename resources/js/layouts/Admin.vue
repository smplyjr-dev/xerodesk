<template>
  <div class="layout layout-admin" :class="{ 'is-open': isOpen }">
    <AdminHeader :isOpen="isOpen" @toggle-sidebar="isOpen = $event" />

    <main>
      <AdminSidebar :isOpen="isOpen" @toggle-sidebar="isOpen = $event" />

      <section class="d-flex flex-column h-100">
        <transition name="fade" mode="out-in">
          <!-- <div class="container-fluid mb-4">
            <div class="row">
              <div class="col">
                <button class="btn btn-sm btn-primary" @click="notify('primary')">Primary</button>
                <button class="btn btn-sm btn-secondary" @click="notify('secondary')">Secondary</button>
                <button class="btn btn-sm btn-success" @click="notify('success')">Success</button>
                <button class="btn btn-sm btn-danger" @click="notify('danger')">Danger</button>
                <button class="btn btn-sm btn-warning" @click="notify('warning')">Warning</button>
                <button class="btn btn-sm btn-info" @click="notify('info')">Info</button>
                <button class="btn btn-sm btn-dark" @click="notify('dark')">Dark</button>
              </div>
            </div>
          </div> -->

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
import Notification from "@Components/neutral/Notification.vue";
import AdminHeader from "@Components/admin/Header.vue";
import AdminSidebar from "@Components/admin/Sidebar.vue";

export default {
  name: "Admin",
  components: { Notification, AdminHeader, AdminSidebar },
  data: () => ({
    isOpen: false
  }),
  metaInfo: () => ({
    title: "Live Support", // set the title on each page, this is just a fallback
    titleTemplate: `Xerodesk - %s`
  }),
  methods: {
    /* notify(variant) {
      this.$store.dispatch("notifications/addNotification", {
        variant: `bg-${variant}`,
        icon: "fa-check",
        title: `${variant.toTitle()}!`,
        body: `This is a <strong>${variant.toTitle()}</strong> notification.`
      });
    } */
  },
  created() {
    // get all the companies
    // this.$store.dispatch("companies/fetchCompanies");

    // get all the roles
    // this.$store.dispatch("roles/fetchRoles");
  }
};
</script>

<style lang="scss">
@import "@Styles/admin.scss";
</style>
