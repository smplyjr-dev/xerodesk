<template>
  <span class="page-stats mt-4"> Showing {{ pagination.from }} - {{ pagination.to }} of {{ pagination.total }}</span>
</template>

<script>
export default {
  props: ["pagination"]
};
</script>
