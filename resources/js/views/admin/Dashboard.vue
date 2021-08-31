<template>
  <div class="container-fluid">
    <div class="page-title">
      <h4 class="mb-2">Dashboard</h4>
    </div>

    <div class="row">
      <div class="col-md mb-4">
        <div class="card card-1 card-body">
          <p class="text-secondary">Open</p>
          <h4 class="mb-0">{{ open.length }}</h4>
        </div>
      </div>
      <div class="col-md mb-4">
        <div class="card card-1 card-body">
          <p class="text-secondary">Pending</p>
          <h4 class="mb-0">{{ pending.length }}</h4>
        </div>
      </div>
      <div class="col-md mb-4">
        <div class="card card-1 card-body">
          <p class="text-secondary">Resolved</p>
          <h4 class="mb-0">{{ resolved.length }}</h4>
        </div>
      </div>
      <div class="col-md mb-4">
        <div class="card card-1 card-body">
          <p class="text-secondary">Closed</p>
          <h4 class="mb-0">{{ closed.length }}</h4>
        </div>
      </div>
      <div class="col-md mb-4">
        <div class="card card-1 card-body">
          <p class="text-secondary">Unassigned</p>
          <h4 class="mb-0">{{ unassigned.length }}</h4>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  layout: "Admin",
  name: "Dashboard",
  metaInfo: () => ({
    title: "Dashboard"
  }),
  middleware: "auth",
  data: () => ({
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
    }
  },
  created() {
    this.fetchTickets();
  }
};
</script>
