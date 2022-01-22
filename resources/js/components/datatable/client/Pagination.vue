<template>
  <div class="flex-center-between flex-wrap" v-if="!client">
    <div class="dtc-entries">{{ pagination.from }} - {{ pagination.to }} of {{ pagination.total }}</div>

    <div class="dtc-pagination">
      <button class="btn btn-primary" v-if="pagination.prevPageUrl" @click="$emit('prev')">Prev</button>
      <button class="btn btn-primary" v-else :disabled="true" style="cursor: not-allowed;">Prev</button>

      <button class="btn btn-primary" v-if="pagination.nextPageUrl" @click="$emit('next')">Next</button>
      <button class="btn btn-primary" v-else :disabled="true" style="cursor: not-allowed;">Next</button>
    </div>
  </div>

  <div class="flex-center-between flex-wrap" v-else>
    <div class="dtc-entries">
      Showing
      {{ pagination.from }} - {{ pagination.to }} of {{ filtered.length }}
      <span v-if="filtered.length < pagination.total"> &#40;filtered from {{ pagination.total }} total entries&#41; </span>
      entries
    </div>

    <div class="dtc-pagination">
      <button class="btn btn-primary" v-if="pagination.prevPage" @click="$emit('prev')">Prev</button>
      <button class="btn btn-primary" v-else :disabled="true" style="cursor: not-allowed;">Prev</button>

      <button class="btn btn-primary" v-if="pagination.nextPage" @click="$emit('next')">Next</button>
      <button class="btn btn-primary" v-else :disabled="true" style="cursor: not-allowed;">Next</button>
    </div>
  </div>
</template>

<script>
export default {
  props: ["pagination", "client", "filtered"]
};
</script>
