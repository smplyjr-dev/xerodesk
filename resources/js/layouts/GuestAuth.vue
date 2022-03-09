<template>
  <div class="layout-guest layout-guest--auth">
    <RouterView />
  </div>
</template>

<script>
export default {
  name: "GuestAuth",
  metaInfo: () => ({
    title: "Live Support", // set the title on each page, this is just a fallback
    titleTemplate: `Xerodesk - %s`
  })
};
</script>
