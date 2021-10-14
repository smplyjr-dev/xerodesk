<template>
  <div class="col-sm mb-4">
    <div class="card card-1 card-body h-100 d-flex flex-column justify-content-between">
      <p class="text-secondary font-weight-bold">
        <skeleton :show="loading" w="75%">{{ title }}</skeleton>
      </p>
      <h4 class="mb-0">
        <skeleton :show="loading" r="100%">{{ length }}</skeleton>
      </h4>
    </div>
  </div>
</template>

<script>
export default {
  props: ["title", "length", "loading"]
};
</script>
