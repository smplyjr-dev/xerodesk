<template>
  <div class="ticket-metas">
    <div class="picture-style-a">
      <img class="client-img" :src="`https://ui-avatars.com/api/?size=256&font-size=0.35&name=${client.name || 'No Name'}`" alt="Profile Picture" loading="lazy" />

      <div class="client-name">
        <h5 class="font-weight-bold mb-0">{{ client.name || "No Name" }}</h5>
        <span class="font-weight-semi mb-0 text-sm">{{ company.name }}</span>
      </div>
    </div>

    <ul class="list-style-a">
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

    <button class="btn btn-primary btn-block" @click="updateSession">Update Ticket</button>
    <button class="btn btn-danger btn-block" @click="transferSession">Transfer Ticket</button>

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
    updateSession() {
      if (this.session.user_id == this.user.id) {
        $("#update-ticket-modal").modal("show");
      } else {
        this.$store.dispatch("notifications/addNotification", {
          variant: "bg-danger",
          icon: "fa-times",
          title: "Invalid!",
          body: `This ticket is not currently assigned to you.`
        });
      }
    },
    transferSession() {
      if (this.session.user_id == this.user.id) {
        $("#transfer-ticket-modal").modal("show");
      } else {
        this.$store.dispatch("notifications/addNotification", {
          variant: "bg-danger",
          icon: "fa-times",
          title: "Invalid!",
          body: `This ticket is not currently assigned to you.`
        });
      }
    }
  }
};
</script>
