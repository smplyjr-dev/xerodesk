<template>
  <div class="form-group flex-center-between">
    <div class="flex-grow-1">
      <p class="mb-0">{{ title }} <span class="text-danger">*</span></p>
      <p class="mb-0 text-muted">{{ sub_title }}</p>
    </div>
    <div class="color-picker">
      <dropdown :carret="false" :position="`right`" @away="isVisible = false">
        <template v-slot:value>
          <div class="trigger" :style="{ background: value }" @click="isVisible = !isVisible">
            &nbsp;
            <button type="button" class="btn">
              <i class="fas fa-caret-up" v-show="isVisible"></i>
              <i class="fas fa-caret-down" v-show="!isVisible"></i>
            </button>
          </div>
        </template>

        <dropdown-content>
          <template v-slot:content>
            <Chrome style="position: relative; top: 0; right: 0" @input="$emit('input', $event.hex)" :value="value" />
          </template>
        </dropdown-content>
      </dropdown>
    </div>
  </div>
</template>

<script>
import { Chrome } from "vue-color";

export default {
  props: ["title", "sub_title", "value"],
  components: { Chrome },
  data: () => ({
    isVisible: false
  }),
  methods: {
    handleAway(e) {
      console.log(e);
    }
  }
};
</script>
