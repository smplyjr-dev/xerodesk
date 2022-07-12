<template>
  <div class="h-100 flex-center-center">
    <div class="mx-auto w-50 text-center text-white">
      <img loading="lazy" :src="`${$APP_URL}/images/404.svg`" alt="404" width="250px" />
      <h3 class="font-weight-normal mt-4 mb-2">Oops! Nothing was found.</h3>
      <p>The page you are looking for might have been removed had its name changed or is temporarily unavailable.</p>
      <router-link class="link-unstyled" style="border-bottom: 1px solid #fff; border-bottom-style: dashed" to="/"> Return to homepage </router-link>
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
