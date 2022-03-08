<template>
  <div class="container-fluid px-4 mt-4">
    <div class="pb-3" style="background-color: #fafafa; border-bottom: 1px solid #e6e7e9">
      <div class="flex-center-between">
        <div class="">
          <button class="btn rounded-pill btn-brand-1" @click="isRefineOpen = true">
            <div class="d-flex align-items-center">
              <span class="d-none d-md-block mr-1">Refine Search</span>
              <i class="fa fa-fw fa-filter"></i>
            </div>
          </button>

          <button class="btn rounded-pill btn-brand-1" data-toggle="modal" data-target="#search">
            <div class="d-flex align-items-center">
              <span class="d-none d-md-block mr-1">Search a Keyword</span>
              <i class="fa fa-fw fa-search"></i>
            </div>
          </button>
        </div>

        <button class="btn rounded-pill btn-success" @click="isExportOpen = true">
          <div class="d-flex align-items-center">
            <span class="d-none d-md-block mr-1">Export</span>
            <i class="fa fa-fw fa-cloud-download-alt"></i>
          </div>
        </button>
      </div>

      <drawer :toggle="isRefineOpen" @toggleDrawer="isRefineOpen = $event" position="left">
        <RefineTickets @onSearch="handleSearch" />
      </drawer>

      <drawer :toggle="isExportOpen" @toggleDrawer="isExportOpen = $event" position="right">
        <ExportTickets />
      </drawer>
    </div>

    <TableTickets :isReady="isReady" />
    <SearchKeywordModal />
  </div>
</template>

<script>
import { mapState } from "vuex";
import RefineTickets from "@Components/admin/ticket/RefineTickets.vue";
import TableTickets from "@Components/admin/ticket/TableTickets.vue";
import ExportTickets from "@Components/admin/ticket/ExportTickets.vue";
import SearchKeywordModal from "@Components/admin/ticket/SearchKeywordModal.vue";

export default {
  layout: "Admin",
  name: "Tickets",
  components: { RefineTickets, TableTickets, ExportTickets, SearchKeywordModal },
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
      this.isRefineOpen = false;

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
        this.$router.push(`/tickets`).catch((err) => {});
      } else {
        this.$router
          .push({
            path: `/tickets`,
            query: build
          })
          .catch((err) => {});
      }
    }
  },
  async created() {
    this.$emit("setTitle", "");
    await this.$store.dispatch("auth/fetchUsers");
    await this.$store.dispatch("groups/fetchGroups");
    await this.$store.dispatch("slas/fetchSlas");

    this.isReady = true;
  }
};
</script>
