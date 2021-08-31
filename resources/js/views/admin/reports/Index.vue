<template>
  <div class="container-fluid">
    <div class="page-title">
      <h4 class="mb-2">Reports</h4>
    </div>

    <p>This module is still in progress. Please, come back later.</p>
  </div>
</template>

<script>
export default {
  layout: "Admin",
  name: "Dashboard",
  metaInfo: () => ({
    title: "Admin Dashboard"
  }),
  middleware: "auth"
};
</script>
