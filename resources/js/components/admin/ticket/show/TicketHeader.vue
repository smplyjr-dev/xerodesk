<template>
  <div class="card-header">
    <div class="flex-center">
      <router-link to="/tickets" class="btn btn-default p-0 mr-2">
        <InlineSvg name="svg/mdi/chevron-left.svg" color="#464646" size="1.25rem" />
      </router-link>

      <div class="card-title mb-0">{{ $store.state.sessions.session.session }}</div>
    </div>

    <button type="button" class="btn btn-default p-0" @click="$emit('toggle')">
      <InlineSvg name="svg/mdi/dots-vertical.svg" color="#464646" size="1.25rem" />
    </button>
  </div>
</template>
