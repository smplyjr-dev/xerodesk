<template>
  <nav class="mt-4">
    <button class="btn btn-primary" v-if="pagination.prevPageUrl" @click="$emit('prev')">Prev</button>
    <button class="btn btn-primary" v-else :disabled="true" style="cursor: not-allowed;">Prev</button>

    <button class="btn btn-primary" v-if="pagination.nextPageUrl" @click="$emit('next')">Next</button>
    <button class="btn btn-primary" v-else :disabled="true" style="cursor: not-allowed;">Next</button>
  </nav>
</template>

<script>
export default {
  props: ["pagination"]
};
</script>
