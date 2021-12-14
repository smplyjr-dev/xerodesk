<template>
  <div class="">
    <div class="container-fluid px-4 py-3" style="background-color: #fafafa; border-bottom: 1px solid #e6e7e9;">
      <div class="flex-center-between">
        <button class="btn rounded-pill btn-primary" @click="isRefineOpen = true">
          <div class="d-flex align-items-center">
            <span class="d-none d-md-block mr-1">Refine Search</span>
            <i class="fa fa-fw fa-search"></i>
          </div>
        </button>

        <button class="btn rounded-pill btn-success" @click="isExportOpen = true">
          <div class="d-flex align-items-center">
            <span class="d-none d-md-block mr-1">Export</span>
            <i class="fa fa-fw fa-cloud-download-alt"></i>
          </div>
        </button>
      </div>

      <RefineTickets :isOpen="isRefineOpen" @toggleDrawer="isRefineOpen = $event" @onSearch="handleSearch" />
      <ExportTickets :isOpen="isExportOpen" @toggleDrawer="isExportOpen = $event" />
    </div>

    <div class="container-fluid px-4 mt-4">
      <TableTickets :isReady="isReady" />
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import RefineTickets from "@Components/admin/ticket/RefineTickets.vue";
import TableTickets from "@Components/admin/ticket/TableTickets.vue";
import ExportTickets from "@Components/admin/ticket/ExportTickets.vue";

export default {
  layout: "Admin",
  name: "Tickets",
  components: { RefineTickets, TableTickets, ExportTickets },
  metaInfo: () => ({ title: "Tickets" }),
  middleware: ["auth", "permission:view_tickets"],
  data: () => ({
    isReady: false,
    isRefineOpen: false,
    isExportOpen: false
  }),
  computed: {
    ...mapState("auth", ["user"])
  },
  methods: {
    handleSearch(e) {
      this.isOpen = false;

      let build = {};

      for (const key in e) {
        if (e.hasOwnProperty(key)) {
          const val = e[key];

          if (!this.$isNull(val)) {
            build[key] = val;
          }
        }
      }

      if (this.$isEmpty(build)) {
        this.$router.push(`/tickets`).catch(err => {});
      } else {
        this.$router
          .push({
            path: `/tickets`,
            query: build
          })
          .catch(err => {});
      }
    }
  },
  async created() {
    await this.$store.dispatch("auth/fetchUsers");
    await this.$store.dispatch("groups/fetchGroups");
    await this.$store.dispatch("slas/fetchSlas");

    this.isReady = true;
  }
};
</script>
