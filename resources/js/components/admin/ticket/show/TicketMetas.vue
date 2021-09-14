<template>
  <div class="ticket-metas">
    <div class="picture-style-a">
      <img class="client-img" :src="`https://ui-avatars.com/api/?size=256&font-size=0.35&name=${client.name || 'No Name'}`" alt="Profile Picture" loading="lazy" />

      <div class="client-name">
        <h5 class="font-weight-bold mb-0">{{ client.name || "No Name" }}</h5>
        <span class="font-weight-semi mb-0 text-sm">{{ company.name }}</span>
      </div>
    </div>

    <ul class="list-style-a mb-0">
      <li>
        <p class="cd-title">Client ID</p>
        <p class="cd-info ellipsis">#{{ `${client.id}`.padStart(6, 0) }}</p>
      </li>
      <li>
        <p class="cd-title">Client Token</p>
        <p class="cd-info ellipsis">{{ client.token }}</p>
      </li>
      <li>
        <p class="cd-title">Client Email</p>
        <p class="cd-info ellipsis">{{ client.email || "No Email" }}</p>
      </li>
      <li>
        <p class="cd-title">Client Phone</p>
        <p class="cd-info ellipsis">{{ client.phone || "No Phone" }}</p>
      </li>
      <li>
        <p class="cd-title">Ticket History</p>
        <p class="cd-info ellipsis" v-if="!history.length">No history found</p>
        <div class="cd-info" v-else>
          <ul class="timeline">
            <li v-for="h in history" :key="h.id">
              <div class="time">{{ $dayjs("format", h.created_at, "MM/DD/YYYY h:mm A") }}</div>
              <div class="content" v-html="h.message"></div>
            </li>
          </ul>
        </div>
      </li>
    </ul>

    <div id="update-ticket" class="modal fade">
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
                  <select class="custom-select" v-model="session.status">
                    <option :value="null">-- Select Status --</option>
                    <option v-for="s in status" :key="s.id" :value="s.id">{{ s.name }}</option>
                  </select>
                  <span class="text-danger text-xs" v-show="sessionError.includes('status')">The session status field is required.</span>
                </li>
              </ul>
            </template>
            <template v-else>
              <form-alert variant="warning">
                <p>This session is not assigned to you.</p>
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

    <button class="btn btn-primary btn-block mt-2" @click="checkSession">
      Update Session
    </button>
  </div>
</template>

<script>
import { nanoid } from "nanoid";
import { mapState } from "vuex";
import { tickets } from "@Scripts/observable";
import TicketTag from "@Components/admin/ticket/show/TicketTag.vue";

export default {
  components: { TicketTag },
  props: ["data"],
  data: () => ({
    priority: tickets.priority,
    status: tickets.status,
    sessionError: [],
    isUpdating: false
  }),
  computed: {
    ...mapState("auth", ["user"]),
    ...mapState("messages", ["messages"]),

    session() {
      return this.data.session;
    },
    client() {
      return this.data.client;
    },
    company() {
      return this.data.company;
    },
    history() {
      return this.messages.filter(m => m.sender == "session").reverse();
    }
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
          status: this.session.status,
          token: nanoid()
        });

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
    checkSession() {
      if (this.session.user_id == this.user.id) {
        $("#update-ticket").modal("show");
      } else {
        this.$store.dispatch("notifications/addNotification", {
          variant: "bg-danger",
          icon: "fa-times",
          title: "Invalid!",
          body: `This session is not currently assigned to you.`
        });
      }
    }
  }
};
</script>
