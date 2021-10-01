<template>
  <div class="container-fluid">
    <div class="page-title">
      <h4 class="mb-2">Dashboard</h4>
    </div>

    <!-- <p>This module is still in progress. Please, come back later.</p> -->

    <div class="row">
      <TicketWidget title="Open Tickets" :length="open.length" />
      <TicketWidget title="Pending Tickets" :length="pending.length" />
      <TicketWidget title="Resolved Tickets" :length="resolved.length" />
      <TicketWidget title="Closed Tickets" :length="closed.length" />
      <TicketWidget title="Unassigned Tickets" :length="unassigned.length" />
    </div>

    <div class="row">
      <div class="col-md-8 mb-4">
        <div class="card card-1 card-body h-100 d-flex flex-column justify-content-between">
          Lorem ipsum dolor sit amet consectetur adipisicing elit. Exercitationem, nam.
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card card-1 card-body h-100 d-flex flex-column justify-content-between">
          <p class="text-secondary font-weight-bold">Recently Added Clients</p>
          <ul class="list-unstyled mb-0">
            <li v-for="c in clients" :key="c.id">
              <a href="javascript:void(0)">
                {{ c.name }}
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import TicketWidget from "@Components/admin/dashboard/TicketWidget.vue";

export default {
  layout: "Admin",
  name: "Dashboard",
  metaInfo: () => ({ title: "Dashboard" }),
  components: { TicketWidget },
  middleware: ["auth"],
  data: () => ({
    clients: [],
    sessions: []
  }),
  computed: {
    open() {
      return this.sessions.filter(s => s.status == 1);
    },
    pending() {
      return this.sessions.filter(s => s.status == 2);
    },
    resolved() {
      return this.sessions.filter(s => s.status == 3);
    },
    closed() {
      return this.sessions.filter(s => s.status == 4);
    },
    unassigned() {
      return this.sessions.filter(s => s.user_id == null);
    }
  },
  methods: {
    async fetchTickets() {
      let { data } = await axios.get(`/session`);

      this.sessions = data;
    },
    async fetchRecentClients() {
      let { data } = await axios.get(`/portal/client/recent`);

      this.clients = data;
    }
  },
  created() {
    this.fetchTickets();
    this.fetchRecentClients();
  }
};
</script>
