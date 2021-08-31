<template>
  <div class="time-picker">
    <input type="text" readonly v-if="$isEmpty(value)" class="form-control cursor-pointer" :class="inputClass" :placeholder="placeholder" @click="isOpen = true" :value="value" />
    <input type="text" readonly v-if="!$isEmpty(value)" class="form-control cursor-pointer" :class="inputClass" :placeholder="placeholder" @click="isOpen = true" :value="$dayjs('format', value, format)" />

    <transition name="fade">
      <div class="time-backdrop" v-if="isOpen" @click="onBlur($event)">
        <div class="time-content shadow" v-if="isOpen">
          <div class="time-meta">
            <div class="time-meta-1">{{ title }}</div>
            <div class="time-meta-2">
              <div class="hours">
                <span class="arrow arrow-inc cursor-pointer" @click="onChange('h', 'inc')">
                  <InlineSvg name="svg/chevron/up.svg" />
                </span>

                <div class="text">{{ hours }}</div>

                <span class="arrow arrow-dec cursor-pointer" @click="onChange('h', 'dec')">
                  <InlineSvg name="svg/chevron/down.svg" />
                </span>
              </div>

              <div class="text amp mx-1">:</div>

              <div class="minutes">
                <span class="arrow arrow-inc cursor-pointer" @click="onChange('m', 'inc')">
                  <InlineSvg name="svg/chevron/up.svg" />
                </span>

                <div class="text">{{ minutes }}</div>

                <span class="arrow arrow-dec cursor-pointer" @click="onChange('m', 'dec')">
                  <InlineSvg name="svg/chevron/down.svg" />
                </span>
              </div>
            </div>
          </div>

          <div class="time-body">
            <div class="d-flex justify-content-end">
              <button type="button" class="btn btn-success btn-sm mr-1" @click="isOpen = false">Cancel</button>
              <button type="button" class="btn btn-success btn-sm ml-1" @click="emitTime()">Set</button>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  name: "ATimePicker",
  props: {
    inputClass: {
      type: String
    },
    format: {
      type: String,
      default: "h:i"
    },
    placeholder: {
      type: String
    },
    title: {
      type: String,
      default: "Time Picker"
    },
    value: {
      type: String
    }
  },
  computed: {
    hours: {
      get() {
        return this.$dayjs("format", this.localValue, "HH");
      },
      set(newValue) {
        let newTime = new Date(this.localValue);
        newTime.setHours(newValue);
        this.localValue = newTime;
      }
    },
    minutes: {
      get() {
        return this.$dayjs("format", this.localValue, "mm");
      },
      set(newValue) {
        let newTime = new Date(this.localValue);
        newTime.setMinutes(newValue);
        this.localValue = newTime;
      }
    }
  },
  data() {
    return {
      localValue: new Date(),
      isOpen: false
    };
  },
  methods: {
    bindKeyEvent() {
      const blurEvent = e => {
        if (e.key === "Tab" || e.key === "Escape") {
          this.isOpen = false;
        }
      };

      document.addEventListener("keydown", blurEvent);

      this.$once("hook:beforeDestroy", () => {
        document.removeEventListener("keydown", blurEvent);
      });
    },

    emitTime() {
      let formatted = this.$dayjs("format", this.localValue, "YYYY-MM-DD HH:mm:ss");
      this.$emit("input", formatted);
      this.isOpen = false;
    },
    onBlur(e) {
      if (e.target.className == "time-backdrop") this.isOpen = false;
    },
    onChange(type, operation) {
      if (type == "h") {
        let hours = this.localValue.getHours();

        if (operation == "inc") this.hours = hours + 1;
        if (operation == "dec") this.hours = hours - 1;
      }

      if (type == "m") {
        let minutes = this.localValue.getMinutes();

        if (operation == "inc") this.minutes = minutes + 1;
        if (operation == "dec") this.minutes = minutes - 1;
      }
    }
  },
  created() {
    this.bindKeyEvent();
  },
  watch: {
    value() {
      this.localValue = new Date(this.value);
    }
  }
};
</script>
