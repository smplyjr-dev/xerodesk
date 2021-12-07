<template>
  <div class="layout layout-neutral">
    <div class="container-fluid h-100">
      <RouterView />
    </div>
  </div>
</template>

<script>
export default {
  name: "Neutral",
  metaInfo: () => ({
    title: "Live Support", // set the title on each page, this is just a fallback
    titleTemplate: `XMCIT - %s`
  })
};
</script>
