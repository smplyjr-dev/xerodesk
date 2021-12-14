<template>
  <div class="container-fluid px-4 mt-4">
    <div class="page-title">
      <h4 class="mb-2">Client</h4>
      &nbsp;
    </div>

    <div class="card card-1">
      <div class="card-body">
        <router-link to="/clients">Clients</router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  components: {},
  layout: "Admin",
  name: "Client",
  metaInfo: () => ({ title: "Client" }),
  middleware: ["auth", "permission:view_client"],
  data: () => ({
    client: {}
  }),
  computed: {},
  methods: {},
  created() {}
};
</script>
