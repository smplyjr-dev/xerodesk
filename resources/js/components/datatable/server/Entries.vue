<template>
  <div class="dts-entries">Showing {{ pagination.from }} - {{ pagination.to }} of {{ pagination.total }}</div>
</template>

<script>
export default {
  props: ["pagination"]
};
</script>
