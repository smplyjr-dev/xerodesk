<template>
  <div class="col-sm mb-4">
    <div class="card card-1 card-body h-100 d-flex flex-column justify-content-between">
      <p class="text-secondary font-weight-bold">{{ title }}</p>
      <h4 class="mb-0">{{ length }}</h4>
    </div>
  </div>
</template>

<script>
export default {
  props: ["title", "length"]
};
</script>
