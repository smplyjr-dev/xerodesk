<template>
  <div class="date-picker">
    <input type="text" readonly v-if="$isEmpty(value)" class="form-control cursor-pointer" :class="inputClass" :placeholder="placeholder" @click="onFocus()" :value="value" />
    <input type="text" readonly v-if="!$isEmpty(value)" class="form-control cursor-pointer" :class="inputClass" :placeholder="placeholder" @click="onFocus()" :value="$dayjs('format', value, format)" />

    <transition name="fade">
      <div class="date-backdrop" v-if="isOpen" @click="onBlur($event)">
        <div class="date-content shadow" v-if="isOpen">
          <div class="date-meta">
            <div class="day">{{ selectedDay }}</div>

            <div class="month d-flex justify-content-between">
              <span class="arrow arrow-prev cursor-pointer px-2" @click="onPick('month', 'dec')">
                <InlineSvg name="svg/chevron/left.svg" color="#ffffff" height="17px" />
              </span>

              <span class="mt-1">{{ months[selectedMonth] }}</span>

              <span class="arrow arrow-next cursor-pointer px-2" @click="onPick('month', 'inc')">
                <InlineSvg name="svg/chevron/right.svg" color="#ffffff" height="17px" />
              </span>
            </div>

            <div class="date py-1">{{ selectedDate }}</div>

            <div class="year d-flex justify-content-between">
              <span class="arrow arrow-prev cursor-pointer px-2" @click="onPick('year', 'dec')">
                <InlineSvg name="svg/chevron/left.svg" color="#ffffff" height="17px" />
              </span>

              <span class="mt-1">{{ selectedYear }}</span>

              <span class="arrow arrow-next cursor-pointer px-2" @click="onPick('year', 'inc')">
                <InlineSvg name="svg/chevron/right.svg" color="#ffffff" height="17px" />
              </span>
            </div>
          </div>

          <div class="date-body">
            <div class="days font-weight-semi text-muted">
              <span v-for="d in days" :key="d">{{ d[0] }}</span>
            </div>

            <hr class="m-0" />

            <div class="days">
              <div class="day" v-for="r in rowPadding()" :key="r + 50"></div>
              <div class="day" v-for="d in getDaysInMonth(selectedYear, selectedMonth)" :key="d" :class="markSelected(d)">
                <span class="cursor-pointer" @click="onPick('date', d)">{{ d }}</span>
              </div>
            </div>

            <hr />

            <div class="d-flex justify-content-end">
              <button type="button" class="btn btn-success btn-sm mr-1" @click="isOpen = false">Cancel</button>
              <button type="button" class="btn btn-success btn-sm ml-1" @click="emitDate()">Set</button>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  name: "ADatePicker",
  props: {
    inputClass: {
      type: String
    },
    format: {
      type: String,
      default: "MM/DD/YYYY"
    },
    placeholder: {
      type: String
    },
    value: {
      type: String
    },
    emitFormat: {
      type: String,
      default: "MM/DD/YYYY"
    }
  },
  computed: {
    selectedDay() {
      let day = this.selected.getDay();
      return this.days[day];
    },
    selectedDate: {
      get() {
        return new Date(this.selected).getDate();
      },
      set(newVal) {
        let newDate = new Date(this.selected);
        newDate.setDate(newVal);

        this.selected = newDate;
      }
    },
    selectedMonth: {
      get() {
        return new Date(this.selected).getMonth();
      },
      set(newVal) {
        let newDate = new Date(this.selected);
        newDate.setMonth(newVal);

        this.selected = newDate;
      }
    },
    selectedYear: {
      get() {
        return new Date(this.selected).getFullYear();
      },
      set(newVal) {
        let newDate = new Date(this.selected);
        newDate.setFullYear(newVal);

        this.selected = newDate;
      }
    }
  },
  data() {
    return {
      isOpen: false,
      selected: new Date(),
      temporary: new Date(),
      days: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
      months: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]
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

    markSelected(d) {
      let date = this.temporary.getDate();
      let month = this.temporary.getMonth();
      let year = this.temporary.getFullYear();

      return { active: date == d && month == this.selectedMonth && year == this.selectedYear };
    },

    rowPadding() {
      let startWeekDay = this.getWeekDay(1, this.selectedMonth, this.selectedYear);
      return startWeekDay;
    },
    getWeekDay(date, month, year) {
      return new Date(year, month, date).getDay();
    },
    getDaysInMonth(year, month) {
      return [31, this.isLeapYear(year) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][month];
    },
    isLeapYear(year) {
      return year % 100 === 0 ? (year % 400 === 0 ? true : false) : year % 4 === 0;
    },

    async onPick(type, val) {
      if (type == "month") {
        if (val == "dec") {
          if (this.selectedMonth != 0) this.selectedMonth = this.selectedMonth - 1;
          else {
            this.selectedMonth = 11;
            this.selectedYear = this.selectedYear - 1;
          }
        }

        if (val == "inc") {
          if (this.selectedMonth != 11) this.selectedMonth = this.selectedMonth + 1;
          else {
            this.selectedMonth = 0;
            this.selectedYear = this.selectedYear + 1;
          }
        }
      }

      if (type == "date") {
        this.selectedDate = val;
        this.selected = new Date(`${this.selectedYear}/${this.selectedMonth + 1}/${this.selectedDate}`);
        this.temporary = this.selected;
      }

      if (type == "year") {
        if (val == "dec") this.selectedYear = this.selectedYear - 1;
        if (val == "inc") this.selectedYear = this.selectedYear + 1;
      }
    },
    onFocus() {
      this.isOpen = true;

      if (this.$isEmpty(this.value)) {
        this.selected = new Date();
        this.temporary = new Date();
      } else {
        let date = Date.parse(this.value.replace(/ /g, "T"));
        this.selected = new Date(date);
        this.temporary = new Date(date);
      }
    },
    onBlur(e) {
      if (e.target.className == "date-backdrop") this.isOpen = false;
    },
    emitDate() {
      let newDate = this.$isEmpty(this.value) ? new Date() : new Date(this.value.replace(/ /g, "T"));
      newDate.setMonth(this.selectedMonth);
      newDate.setDate(this.selectedDate);
      newDate.setFullYear(this.selectedYear);

      let formatted = this.$dayjs('format', newDate, this.emitFormat);
      this.$emit("input", formatted);
      this.isOpen = false;
    }
  },
  created() {
    this.bindKeyEvent();
  }
};
</script>
