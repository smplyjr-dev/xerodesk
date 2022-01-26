<template>
  <div class="h-100 flex-center">
    <div class="text-center">
      <img loading="lazy" :src="`${$APP_URL}/images/404.svg`" alt="404" width="250px" />
      <h3 class="font-weight-normal my-4">Sorry! Page not found.</h3>
      <router-link class="btn btn-primary" to="/">Go back.</router-link>
    </div>
  </div>
</template>

<script>
export default {
  layout: "GuestAuth",
  name: "NotFound",
  metaInfo: () => ({ title: "Page Not Found" })
};
</script>
