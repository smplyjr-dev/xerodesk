<template>
  <div class="container-fluid">
    <div class="page-title">
      <h4 class="mb-2">Dashboard</h4>
    </div>

    <!-- <p>This module is still in progress. Please, come back later.</p> -->

    <div class="row">
      <TicketWidget title="Open Tickets" :loading="loading.sessions" :length="open.length" />
      <TicketWidget title="Pending Tickets" :loading="loading.sessions" :length="pending.length" />
      <TicketWidget title="Resolved Tickets" :loading="loading.sessions" :length="resolved.length" />
      <TicketWidget title="Closed Tickets" :loading="loading.sessions" :length="closed.length" />
      <TicketWidget title="Unassigned Tickets" :loading="loading.sessions" :length="unassigned.length" />
    </div>

    <div class="row">
      <div class="col-md-8 mb-4">
        <div class="card card-1 card-body">
          <skeleton :show="loading.clients" w="100%">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Exercitationem, nam.
          </skeleton>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card card-1 card-body">
          <p class="text-secondary font-weight-bold">
            <skeleton :show="loading.clients" w="75%">
              Recently Added Clients
            </skeleton>
          </p>
          <ul class="list-unstyled mb-0" v-if="loading.clients">
            <li v-for="c in 3" :key="c.id" class="mt-2">
              <skeleton :show="loading.clients" w="50%"></skeleton>
            </li>
          </ul>
          <ul class="list-unstyled mb-0" v-else>
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
    sessions: [],
    loading: {
      clients: false,
      sessions: false
    }
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
      this.loading.sessions = true;

      let { data } = await axios.get(`/session`);
      // await this.$delay(3000);
      this.sessions = data;

      this.loading.sessions = false;
    },
    async fetchRecentClients() {
      this.loading.clients = true;

      let { data } = await axios.get(`/portal/client/recent`);
      // await this.$delay(3000);
      this.clients = data;

      this.loading.clients = false;
    }
  },
  created() {
    this.fetchTickets();
    this.fetchRecentClients();
  }
};
</script>
