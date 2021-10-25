<template>
  <div class="card-header">
    <div class="flex-center">
      <router-link to="/tickets" class="btn btn-default p-0 mr-2">
        <InlineSvg name="template/mdi-chevron-left.svg" color="#464646" size="1.25rem" />
      </router-link>

      <div class="card-title mb-0">{{ data.session.session }}</div>
    </div>

    <button type="button" class="btn btn-default p-0" @click="$emit('toggle')">
      <InlineSvg name="template/mdi-dots-vertical.svg" color="#464646" size="1.25rem" />
    </button>
  </div>
</template>

<script>
export default {
  props: ["data"]
};
</script>
