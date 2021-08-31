<template>
  <div class="lds-dual-ring" :style="{ height, width }">
    <div class="lds-border" :style="{ height, width }"></div>
  </div>
</template>

<script>
export default {
  props: {
    height: {
      type: String,
      default: "25px"
    },
    width: {
      type: String,
      default: "25px"
    }
  },
  name: "Spinner"
};
</script>

<style scoped>
.lds-dual-ring {
  display: inline-block;
}

.lds-dual-ring .lds-border {
  display: block;
  border-radius: 50%;
  border: 5px solid #fff;
  border-color: #424242 transparent #424242 transparent;
  animation: lds-dual-ring 1.2s linear infinite;
}

@keyframes lds-dual-ring {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>
