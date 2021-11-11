<template>
  <div id="update-ticket-modal" class="modal fade">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Update Session</h5>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Title <span class="text-danger">*</span></label>
            <input type="text" class="form-control" v-model="session.title" />
            <span class="text-danger text-xs" v-show="sessionError.includes('title')">The title field is required.</span>
          </div>
          <div class="form-group">
            <label>Priority <span class="text-danger">*</span></label>
            <select class="custom-select" v-model="session.priority">
              <option :value="null">-- Select Priority --</option>
              <option v-for="p in priority" :key="p.id" :value="p.id">{{ p.name }}</option>
            </select>
            <span class="text-danger text-xs" v-show="sessionError.includes('priority')">The priority field is required.</span>
          </div>
          <div class="form-group">
            <label>Service Category <span class="text-danger">*</span></label>
            <select class="custom-select" v-model="session.category">
              <option :value="null">-- Select Category --</option>
              <option v-for="c in category" :key="c.id">{{ c.name }}</option>
            </select>
            <span class="text-danger text-xs" v-show="sessionError.includes('category')">The category field is required.</span>
          </div>
          <div class="form-group">
            <label>Resolution Code <span class="text-danger">*</span></label>
            <select class="custom-select" v-model="session.resolution_code">
              <option :value="null">-- Select Resolution Code --</option>
              <option v-for="r in resolution" :key="r.id">{{ r.name }}</option>
            </select>
            <span class="text-danger text-xs" v-show="sessionError.includes('resolution_code')">The resolution code field is required.</span>
          </div>
          <div class="form-group">
            <label>Solution <span class="text-danger">*</span></label>
            <input type="text" class="form-control" v-model="session.solution" />
            <span class="text-danger text-xs" v-show="sessionError.includes('solution')">The solution field is required.</span>
          </div>
          <div class="form-group">
            <label>Tags</label>
            <TicketTag :session="session" />
          </div>
          <div class="form-group mb-0">
            <label>Status <span class="text-danger">*</span></label>
            <select class="custom-select" v-model="newStatus" :disabled="isDisable">
              <option :value="null">-- Select Status --</option>
              <option v-for="s in status" :key="s.id" :value="s.id">{{ s.name }}</option>
            </select>
            <span class="text-danger text-xs" v-show="sessionError.includes('status')">The status field is required.</span>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" @click="updateSession" :disabled="isUpdating">
            <span v-if="isUpdating">
              Please wait...
              <i class="fa fa-spin fa-circle-notch" aria-hidden="true"></i>
            </span>

            <span v-else>Update</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import TicketTag from "./TicketTag.vue";
import { tickets } from "@Scripts/observable";
import { nanoid } from "nanoid";

export default {
  props: ["session"],
  components: { TicketTag },
  data: () => ({
    category: tickets.category,
    priority: tickets.priority,
    resolution: tickets.resolution,
    status: tickets.status,
    sessionError: [],
    isUpdating: false,
    isDisable: false,
    newStatus: null
  }),
  computed: {
    ...mapState("auth", ["user"])
  },
  methods: {
    async updateSession() {
      this.isUpdating = true;
      this.sessionError = [];

      if (this.$isEmpty(this.session.title)) this.sessionError.push("title");
      if (this.$isNull(this.session.category)) this.sessionError.push("category");
      if (this.$isNull(this.session.priority)) this.sessionError.push("priority");
      if (this.$isNull(this.session.resolution_code)) this.sessionError.push("resolution_code");
      if (this.$isNull(this.session.solution)) this.sessionError.push("solution");
      if (this.$isNull(this.session.status)) this.sessionError.push("status");

      if (this.$isEmpty(this.sessionError)) {
        await axios.put(`/portal/session/${this.session.session}`, {
          user_id: this.user.id,
          title: this.session.title,
          category: this.session.category,
          priority: this.session.priority,
          resolution_code: this.session.resolution_code,
          solution: this.session.solution,
          token: nanoid()
        });

        await this.processMessage();
        this.session.status = this.newStatus;
        $("#update-ticket-modal").modal("hide");

        // show notification
        this.$store.dispatch("notifications/addNotification", {
          variant: "bg-success",
          icon: "fa-check",
          title: "Success!",
          body: `The session has been successfully updated.`
        });
      }

      this.isUpdating = false;
    },
    async processMessage() {
      if (this.session.status != this.newStatus) {
        let message = {
          status: this.newStatus,
          hash: nanoid(),
          sender: "session",
          message: `<p>The status of the ticket has been updated to <strong>${tickets.status.find(s => s.id == this.newStatus).name}</strong>.</p>`
        };

        await axios.put(`/portal/session/${this.session.session}/status`, message);
        this.$store.commit("messages/PUSH_MESSAGE", message);
        if (this.newStatus == 4) this.isDisable = true;
      }
    }
  },
  mounted() {
    this.newStatus = this.session.status;
    if (this.session.status == 4) this.isDisable = true;
  }
};
</script>
