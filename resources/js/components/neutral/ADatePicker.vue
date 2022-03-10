<template>
  <!-- prettier-ignore -->
  <date-picker
    :clearable="clearable"
    :input-class="inputClass"
    :format="format"
    :value-type="valueType"
    @input="$emit('input', initial)"
    v-model="initial"
  />
</template>

<script>
import DatePicker from "vue2-datepicker";

// please see my submitted issue here
// https://github.com/mengxiong10/vue2-datepicker/issues/649
const { updateCalendars } = DatePicker.CalendarRange.methods;
DatePicker.CalendarRange.methods.updateCalendars = function (calendars, adjustIndex = 0) {
  updateCalendars.call(this, calendars, adjustIndex);
};

export default {
  name: "ADatePicker",
  props: {
    // props for vue2-datepicker
    clearable: {
      type: Boolean,
      default: false
    },
    inputClass: {
      type: String,
      default: "form-control"
    },
    format: {
      type: String,
      default: "MM/DD/YYYY"
    },
    valueType: {
      type: String,
      default: "YYYY-MM-DD"
    },

    // props for vue.js itself
    value: {
      type: String
    }
  },
  data: () => ({
    initial: null
  }),
  components: { DatePicker },
  mounted() {
    this.initial = this.value;
  },
  watch: {
    value(newValue) {
      this.initial = newValue;
    }
  }
};
</script>
