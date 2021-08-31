<template>
  <div class="container-fluid">
    <div class="page-title">
      <h4 class="mb-2">Manage Tickets</h4>
      <button type="button" class="btn btn-primary mb-2" data-toggle="collapse" data-target="#refine">Refine Search</button>
    </div>

    <RefineTickets @onSearch="handleSearch" />

    <div class="card card-1">
      <div class="card-body">
        <TableTickets :isReady="isReady" />
      </div>
    </div>
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
    setupListeners() {
      const self = this;

      // pusher init
      const PUSHER = new Pusher(process.env.MIX_PUSHER_APP_KEY, {
        cluster: process.env.MIX_PUSHER_APP_CLUSTER,
        encrypted: true
      });

      // channels listener
      const CH_SESSION = PUSHER.subscribe("session");

      CH_SESSION.bind(`session.created`, async data => {
        await self.$store.dispatch("clients/fetchClientsFromSessions");
      });

      CH_SESSION.bind(`session.ended`, async data => {
        await self.$store.dispatch("clients/fetchClientsFromSessions");
      });

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
    },
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
    // make the sidebar open for this page
    this.$emit("toggle-sidebar", true);

    // setup pusher's listeners
    // this.setupListeners();

    await this.$store.dispatch("auth/fetchUsers");
    await this.$store.dispatch("groups/fetchGroups");
    await this.$store.dispatch("slas/fetchSlas");

    this.isReady = true;
  }
};
</script>
