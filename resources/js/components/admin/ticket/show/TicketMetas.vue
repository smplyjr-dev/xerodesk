<template>
  <div class="ticket-metas">
    <div class="picture-style-a">
      <img class="client-img" :src="profilePicture" alt="Profile Picture" loading="lazy" />

      <div class="client-name">
        <h5 class="font-weight-bold mb-0">{{ client.name || "No Name" }}</h5>
        <span class="font-weight-semi mb-0 text-sm">{{ company.name }}</span>
      </div>
    </div>

    <div class="ticket-actions">
      <div class="btn btn-brand-1" @click="updateSession" data-toggle="popover" data-placement="top" data-content="Update Ticket" data-trigger="hover"><InlineSvg name="svg/mdi/sync.svg" size="1.25rem" /></div>
      <div class="btn btn-danger" @click="transferSession" data-toggle="popover" data-placement="top" data-content="Transfer Ticket" data-trigger="hover"><InlineSvg name="svg/mdi/swap-vertical.svg" size="1.25rem" /></div>
      <div class="btn btn-info" @click="transcriptSession" data-toggle="popover" data-placement="top" data-content="Send Transcript" data-trigger="hover" :class="{ disabled: isSending }"><InlineSvg name="svg/mdi/email-fast-outline.svg" size="1.25rem" /></div>
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
        <div class="cd-info">
          <ul class="timeline">
            <li>
              <a href="javascript:void(0)" @click="isLogVisible = !isLogVisible" data-toggle="modal" data-target="#ticket-log-modal">View Full Activity Log</a>
            </li>
            <li v-for="h in history" :key="h.id">
              <div class="user">{{ findUserById(h.user_id) }}</div>
              <div class="content" v-html="h.message"></div>
              <div class="time">{{ $dayjs("format", h.created_at, "MM/DD/YYYY h:mm A") }}</div>
            </li>
          </ul>
        </div>
      </li>
    </ul>

    <TicketLogModal v-if="isLogVisible" @onHidden="isLogVisible = $event" />
    <TicketUpdateModal />
    <TicketTransferModal />
  </div>
</template>

<script>
import { mapState } from "vuex";
import TicketLogModal from "./TicketLogModal.vue";
import TicketUpdateModal from "./TicketUpdateModal.vue";
import TicketTransferModal from "./TicketTransferModal.vue";

export default {
  props: ["data"],
  components: { TicketLogModal, TicketUpdateModal, TicketTransferModal },
  data: () => ({
    isSending: false,
    isLogVisible: false
  }),
  computed: {
    ...mapState("auth", ["users", "user"]),
    ...mapState("messages", ["messages"]),

    profilePicture() {
      if (this.$APP_ENV == "local") {
        return `${this.$APP_URL}/images/placeholder/profile.png`;
      } else {
        return `https://ui-avatars.com/api/?size=300&name=${this.client.name}`;
      }
    },
    session: {
      get() {
        return this.$store.state.sessions.session;
      },
      set(newValue) {
        this.$store.state.sessions.session = newValue;
      }
    },
    client() {
      return this.session.client;
    },
    company() {
      return this.client.company;
    },
    history() {
      return this.messages.filter((m) => m.sender == "session").reverse();
    }
  },
  methods: {
    forbiddenNotif(message = "This ticket is not currently assigned to you.") {
      this.$store.dispatch("notifications/addNotification", {
        variant: "bg-danger",
        icon: "fa-times",
        title: "Forbidden!",
        body: message
      });
    },
    updateSession() {
      if (this.session.user_id == this.user.id || this.user.role == "Super") {
        $("#update-ticket-modal").modal("show");
      } else {
        this.forbiddenNotif();
      }
    },
    transferSession() {
      if (this.session.status != 4) {
        if (this.session.user_id == this.user.id || this.user.role == "Super") {
          $("#transfer-ticket-modal").modal("show");
        } else {
          this.forbiddenNotif();
        }
      } else {
        this.forbiddenNotif("The ticket is already closed.");
      }
    },
    async transcriptSession() {
      if (![3, 4].includes(this.session.status)) {
        this.$store.dispatch("notifications/addNotification", {
          variant: "bg-danger",
          icon: "fa-times",
          title: "Invalid!",
          body: "Please make sure the ticket is either resolved or closed before sending a copy of transcript to the client.",
          duration: 5000
        });
      } else {
        if (this.session.user_id == this.user.id || this.user.role == "Super") {
          this.isSending = true;

          let { data } = await axios.get(`/portal/session/${this.session.session}/transcript`);

          this.$store.dispatch("notifications/addNotification", {
            variant: "bg-success",
            icon: "fa-check",
            title: "Success!",
            body: data.message
          });

          this.isSending = false;
        } else {
          this.forbiddenNotif();
        }
      }
    },
    findUserById(id) {
      let user = this.users.find((u) => u.id == id);

      if (user) return `${user.bio.first_name} ${user.bio.last_name}`;
      else return "Unrecorded";
    }
  },
  mounted() {
    $('[data-toggle="popover"]').popover();
  }
};
</script>
