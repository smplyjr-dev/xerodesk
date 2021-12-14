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
      <button class="btn btn-primary" @click="updateSession" data-toggle="popover" data-placement="top" data-content="Update Ticket" data-trigger="hover">
        <InlineSvg name="template/mdi-sync.svg" size="1.25rem" />
      </button>
      <button class="btn btn-danger" @click="transferSession" data-toggle="popover" data-placement="top" data-content="Transfer Ticket" data-trigger="hover">
        <InlineSvg name="template/mdi-swap-vertical.svg" size="1.25rem" />
      </button>
      <button class="btn btn-info" @click="transcriptSession" data-toggle="popover" data-placement="top" data-content="Send Transcript" data-trigger="hover" :disabled="isSending">
        <InlineSvg name="template/mdi-email-fast-outline.svg" size="1.25rem" />
      </button>
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

    <TicketUpdateModal :session="session" />
    <TicketTransferModal :session="session" />
  </div>
</template>

<script>
import { mapState } from "vuex";
import TicketUpdateModal from "./TicketUpdateModal.vue";
import TicketTransferModal from "./TicketTransferModal.vue";

export default {
  props: ["data"],
  components: { TicketUpdateModal, TicketTransferModal },
  data: () => ({
    isSending: false
  }),
  computed: {
    ...mapState("auth", ["user"]),
    ...mapState("messages", ["messages"]),

    profilePicture() {
      if (process.env.NODE_ENV == "development") {
        return `${this.$APP_URL}/images/generic-profile.png`;
      } else {
        return `https://ui-avatars.com/api/?size=300&name=${this.client.name}`;
      }
    },
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
    forbiddenNotif() {
      this.$store.dispatch("notifications/addNotification", {
        variant: "bg-danger",
        icon: "fa-times",
        title: "Forbidden!",
        body: "This ticket is not currently assigned to you."
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
      if (this.session.user_id == this.user.id || this.user.role == "Super") {
        $("#transfer-ticket-modal").modal("show");
      } else {
        this.forbiddenNotif();
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
    }
  },
  mounted() {
    $('[data-toggle="popover"]').popover();
  }
};
</script>
