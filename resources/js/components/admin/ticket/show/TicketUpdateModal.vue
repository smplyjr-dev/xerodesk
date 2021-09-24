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
          <template v-if="session.user_id == user.id">
            <ul class="list-style-a my-0">
              <li>
                <p class="cd-title">Session Title <span class="text-danger">*</span></p>
                <input type="text" class="form-control" v-model="session.title" />
                <span class="text-danger text-xs" v-show="sessionError.includes('title')">The session title field is required.</span>
              </li>
              <li>
                <p class="cd-title">Session Priority <span class="text-danger">*</span></p>
                <select class="custom-select" v-model="session.priority">
                  <option :value="null">-- Select Priority --</option>
                  <option v-for="p in priority" :key="p.id" :value="p.id">{{ p.name }}</option>
                </select>
                <span class="text-danger text-xs" v-show="sessionError.includes('priority')">The session priority field is required.</span>
              </li>
              <li>
                <p class="cd-title">Session Tags</p>
                <TicketTag :session="session" />
              </li>
              <li>
                <p class="cd-title">Session Status <span class="text-danger">*</span></p>
                <select class="custom-select" v-model="newStatus" :disabled="isDisable">
                  <option :value="null">-- Select Status --</option>
                  <option v-for="s in status" :key="s.id" :value="s.id">{{ s.name }}</option>
                </select>
                <span class="text-danger text-xs" v-show="sessionError.includes('status')">The session status field is required.</span>
              </li>
            </ul>
          </template>
          <template v-else>
            <form-alert variant="warning">
              <p>This ticket is not assigned to you.</p>
            </form-alert>
          </template>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" @click="updateSession" :disabled="isUpdating || session.user_id != user.id">
            <span v-if="isUpdating">
              Please wait...
              <i class="fa fa-spin fa-circle-o-notch" aria-hidden="true"></i>
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
    priority: tickets.priority,
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
      if (this.$isNull(this.session.priority)) this.sessionError.push("priority");
      if (this.$isNull(this.session.status)) this.sessionError.push("status");

      if (this.$isEmpty(this.sessionError)) {
        await axios.put(`/session/${this.session.session}`, {
          user_id: this.user.id,
          title: this.session.title,
          priority: this.session.priority,
          token: nanoid()
        });

        await this.processMessage();
        this.session.status = this.newStatus;

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
