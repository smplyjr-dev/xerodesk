<template>
  <div class="col-sm mb-4">
    <div class="card card-body h-100 d-flex flex-column justify-content-between">
      <p class="text-secondary font-weight-bold">
        <skeleton :show="loading" w="75%">{{ name }} Tickets</skeleton>
      </p>
      <h4 class="mb-0">
        <skeleton :show="loading" r="100%">
          <router-link class="link-unstyled" :to="`/tickets?status=${this.title}`">{{ length }}</router-link>
        </skeleton>
      </h4>
    </div>
  </div>
</template>

<script>
export default {
  props: ["title", "length", "loading"],
  computed: {
    name() {
      let titles = ["Unassigned", "Open", "Pending", "Resolved", "Closed"];
      return titles[this.title];
    }
  }
};
</script>
