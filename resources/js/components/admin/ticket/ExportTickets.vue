<template>
  <transition name="fade">
    <div class="drawer-backdrop" v-show="isOpen" @click.self="$emit('toggleDrawer', false)">
      <transition name="right">
        <div class="drawer-container" v-show="isOpen">
          <div class="drawer-close" @click="$emit('toggleDrawer', false)">
            <InlineSvg name="template/mdi-close.svg" color="#c6c6c6" size="100%" />
          </div>

          <form @submit.prevent="submitExport">
            <h4 class="mb-4">Export Tickets</h4>

            <div class="card card-1 card-body">
              <div class="form-group row align-items-center">
                <label class="col-md-3 mb-0">Export As</label>
                <div class="col-md-9">
                  <div class="form-control border-0 px-0">
                    <div class="d-flex align-items-center">
                      <div class="custom-control custom-radio mr-4">
                        <input type="radio" class="custom-control-input" id="xlsx" value="xlsx" v-model="refine.type" />
                        <label class="custom-control-label" for="xlsx">Excel</label>
                      </div>
                      <div class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" id="csv" value="csv" v-model="refine.type" />
                        <label class="custom-control-label" for="csv">CSV</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group row align-items-center">
                <label class="col-md-3 mb-0">Filter By</label>
                <div class="col-md-9">
                  <!-- prettier-ignore -->
                  <date-picker
                    v-model="refine.range"
                    :clearable="false"
                    :disabled-date="disabledAfterToday"
                    :editable="false"
                    :shortcuts="range.shortcuts"
                    input-class="form-control"
                    range-separator=" to "
                    range
                    format="MM/DD/YYYY"
                    valueType="YYYY-MM-DD"
                  />
                </div>
              </div>
              <div class="form-group">
                <label class="mb-2">Column Fields</label>
                <div class="row">
                  <div class="col-md-6">
                    <div class="custom-control custom-checkbox mr-sm-2">
                      <input type="checkbox" class="custom-control-input" id="client" v-model="refine.fields.client" />
                      <label class="custom-control-label" for="client">Client</label>
                    </div>
                    <div class="custom-control custom-checkbox mr-sm-2">
                      <input type="checkbox" class="custom-control-input" id="title" v-model="refine.fields.title" />
                      <label class="custom-control-label" for="title">Title</label>
                    </div>
                    <div class="custom-control custom-checkbox mr-sm-2">
                      <input type="checkbox" class="custom-control-input" id="session" v-model="refine.fields.session" />
                      <label class="custom-control-label" for="session">Session</label>
                    </div>
                    <div class="custom-control custom-checkbox mr-sm-2">
                      <input type="checkbox" class="custom-control-input" id="priority" v-model="refine.fields.priority" />
                      <label class="custom-control-label" for="priority">Priority</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="custom-control custom-checkbox mr-sm-2">
                      <input type="checkbox" class="custom-control-input" id="group" v-model="refine.fields.group" />
                      <label class="custom-control-label" for="group">Group</label>
                    </div>
                    <div class="custom-control custom-checkbox mr-sm-2">
                      <input type="checkbox" class="custom-control-input" id="agent" v-model="refine.fields.agent" />
                      <label class="custom-control-label" for="agent">Agent</label>
                    </div>
                    <div class="custom-control custom-checkbox mr-sm-2">
                      <input type="checkbox" class="custom-control-input" id="status" v-model="refine.fields.status" />
                      <label class="custom-control-label" for="status">Status</label>
                    </div>
                    <div class="custom-control custom-checkbox mr-sm-2">
                      <input type="checkbox" class="custom-control-input" id="timestamp" v-model="refine.fields.timestamp" />
                      <label class="custom-control-label" for="timestamp">Timestamp</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group mb-0">
                <button class="btn btn-primary pull-right ml-1" type="submit">Export</button>
                <button class="btn btn-light pull-right ml-0" type="button" @click="resetExport()">Reset</button>
                <div class="clearfix"></div>
              </div>
            </div>
          </form>
        </div>
      </transition>
    </div>
  </transition>
</template>

<script>
import { tickets } from "@Scripts/observable";

export default {
  props: ["isOpen"],
  data() {
    return {
      priority: tickets.priority,
      status: tickets.status,
      refine: {
        type: "xlsx",
        range: [],
        fields: {
          client: true,
          title: true,
          session: true,
          priority: true,
          group: true,
          agent: true,
          status: true,
          timestamp: true
        }
      },

      // datepicker
      range: {
        shortcuts: [
          { text: "Today", onClick: () => this.onShortcut(0) },
          { text: "From yesterday", onClick: () => this.onShortcut(1) },
          { text: "Last 7 days", onClick: () => this.onShortcut(7) },
          { text: "Last 30 days", onClick: () => this.onShortcut(30) }
        ]
      }
    };
  },
  methods: {
    disabledAfterToday(date) {
      const today = new Date();
      today.setHours(0, 0, 0, 0);

      return date > today;
    },
    onShortcut(offset) {
      const start = new Date();
      start.setTime(start.getTime() - offset * 24 * 3600 * 1000);

      return [start, new Date()];
    },
    resetExport() {
      this.refine = {
        type: "xlsx",
        range: [],
        fields: {
          client: true,
          title: true,
          session: true,
          priority: true,
          group: true,
          agent: true,
          status: true,
          timestamp: true
        }
      };

      this.setRange();
    },
    submitExport() {
      axios({
        url: `/portal/session/export`,
        method: "GET",
        responseType: "blob",
        params: { refine: JSON.stringify(this.refine) }
      }).then(response => {
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement("a");

        link.href = url;
        link.setAttribute("download", `sessions.${this.refine.type}`);
        document.body.appendChild(link);
        link.click();
      });
    },
    setRange() {
      // set date range by 1 week
      let thisWeek = new Date();
      thisWeek = thisWeek.setDate(thisWeek.getDate() - 7);
      thisWeek = new Date(thisWeek);
      thisWeek = `${thisWeek.getFullYear()}-${String(thisWeek.getMonth() + 1).padStart(2, "0")}-${thisWeek.getDate()}`;

      let thisDate = new Date();
      thisDate = `${thisDate.getFullYear()}-${String(thisDate.getMonth() + 1).padStart(2, "0")}-${thisDate.getDate()}`;

      this.refine.range = [thisWeek, thisDate];
    }
  },
  mounted() {
    this.setRange();
  }
};
</script>
