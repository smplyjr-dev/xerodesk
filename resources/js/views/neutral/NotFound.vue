<template>
  <div class="h-100 flex-center">
    <div class="text-center">
      <img loading="lazy" :src="`${$APP_URL}/images/404.svg`" alt="404" width="300px" />
      <h3 class="font-weight-normal my-4">Sorry! The page you looking for cannot be found.</h3>
      <router-link class="btn btn-primary" to="/">Go back.</router-link>
    </div>
  </div>
</template>

<script>
export default {
  layout: "Neutral",
  name: "NotFound",
  metaInfo: () => ({ title: "Page Not Found" })
};
</script>
