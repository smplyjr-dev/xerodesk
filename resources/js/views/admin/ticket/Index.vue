<template>
  <div class="container-fluid">
    <div class="page-title">
      <h4 class="mb-2">Manage Tickets</h4>
      <button type="button" class="btn btn-primary mb-2" data-toggle="collapse" data-target="#refine">Refine Search</button>
    </div>

    <RefineTickets @onSearch="handleSearch" />
    <TableTickets :isReady="isReady" />
  </div>
</template>

<script>
import { mapState } from "vuex";
import RefineTickets from "@Components/admin/ticket/RefineTickets.vue";
import TableTickets from "@Components/admin/ticket/TableTickets.vue";

export default {
  layout: "Admin",
  name: "Tickets",
  components: { RefineTickets, TableTickets },
  metaInfo: () => ({
    title: "Tickets"
  }),
  middleware: ["auth", "permission:view_tickets"],
  data: () => ({
    isReady: false
  }),
  computed: {
    ...mapState("auth", ["user"])
  },
  methods: {
    handleSearch(e) {
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
        this.$router.push("/tickets").catch(err => {});
      } else {
        this.$router
          .push({
            path: "/tickets",
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
