<template>
  <div class="layout-guest layout-guest--default">
    <div class="container-fluid h-100">
      <RouterView />
    </div>
  </div>
</template>

<script>
export default {
  name: "GuestDefault",
  metaInfo: () => ({
    title: "Live Support", // set the title on each page, this is just a fallback
    titleTemplate: `Xerodesk - %s`
  })
};
</script>
