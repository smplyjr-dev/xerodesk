<template>
  <div class="container-fluid px-4">
    <div class="row">
      <div class="col">
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Magnam, ea. Itaque, laboriosam ex dignissimos est voluptates repellat minima, deleniti dicta, numquam officiis tempora velit nihil.</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  layout: "Settings",
  name: "AccountPassword",
  metaInfo: () => ({ title: "Account / Password" }),
  middleware: ["auth"]
};
</script>
