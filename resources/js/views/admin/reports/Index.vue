<template>
  <div class="container-fluid px-4">
    <p>This module is still in progress. Please, come back later.</p>
  </div>
</template>

<script>
export default {
  layout: "Admin",
  name: "Dashboard",
  metaInfo: () => ({ title: "Admin Dashboard" }),
  middleware: "auth",
  created() {
    this.$emit("setTitle", "Reports");
  }
};
</script>
