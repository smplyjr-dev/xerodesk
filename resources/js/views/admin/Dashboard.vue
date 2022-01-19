<template>
  <div class="container-fluid px-4 mt-4">
    <div class="page-title">
      <h4 class="mb-2">Dashboard</h4>
    </div>

    <div class="row">
      <TicketWidget title="Open" :loading="loading.sessions" :length="filterBy('status', 1)" />
      <TicketWidget title="Pending" :loading="loading.sessions" :length="filterBy('status', 2)" />
      <TicketWidget title="Resolved" :loading="loading.sessions" :length="filterBy('status', 3)" />
      <TicketWidget title="Closed" :loading="loading.sessions" :length="filterBy('status', 4)" />
      <TicketWidget title="Unassigned" :loading="loading.sessions" :length="filterBy('user_id', null)" />
    </div>

    <div class="row">
      <div class="col-md-8 mb-4">
        <div class="card card-1 card-body">
          <skeleton :show="loading.clients" w="100%">Lorem ipsum dolor sit amet consectetur adipisicing elit. Exercitationem, nam.</skeleton>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card card-1 card-body">
          <p class="text-secondary font-weight-bold">
            <skeleton :show="loading.clients" w="75%">Recently Added Clients</skeleton>
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
  methods: {
    filterBy(param, stat) {
      return this.sessions.filter((s) => s[param] == stat).length;
    },
    async fetchTickets() {
      this.loading.sessions = true;

      let { data } = await axios.get(`/portal/session`).takeAtLeast(300);
      this.sessions = data.data;

      this.loading.sessions = false;
    },
    async fetchRecentClients() {
      this.loading.clients = true;

      let { data } = await axios.get(`/portal/client/recent`).takeAtLeast(400);
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
