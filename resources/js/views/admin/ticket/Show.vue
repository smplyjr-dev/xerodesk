<template>
  <div class="card card-1 card-ticket">
    <TicketHeader :data="{ isComponentReady, session, client, company }" @toggle="isMetaOpen = !isMetaOpen" />

    <div class="card-body" :class="{ isMetaOpen }">
      <div class="h-100 w-100 flex-center" v-if="!isComponentReady">
        <Spinner height="5rem" width="5rem" />
      </div>

      <template v-else>
        <TicketContents :data="{ session, client, company }" />
        <TicketMetas :data="{ session, client, company }" />
      </template>
    </div>
  </div>
</template>

<script>
import TicketHeader from "@Components/admin/ticket/show/TicketHeader.vue";
import TicketContents from "@Components/admin/ticket/show/TicketContents.vue";
import TicketMetas from "@Components/admin/ticket/show/TicketMetas.vue";
import { mapState } from "vuex";

export default {
  components: { TicketHeader, TicketContents, TicketMetas },
  layout: "Admin",
  name: "Ticket",
  metaInfo: () => ({ title: "Ticket" }),
  middleware: ["auth", "permission:view_ticket"],
  data: () => ({
    client: {},
    company: {},
    isMetaOpen: true,
    isComponentReady: false
  }),
  computed: {
    ...mapState("auth", ["users"]),
    ...mapState("sessions", ["session"])
  },
  methods: {
    async fetchSession() {
      this.$store.state.sessions.session = {};
      this.client = {};
      this.company = {};
      this.$store.commit("messages/SET_MESSAGES", []);

      let { data } = await axios.get(`/portal/session/${this.$route.params.session}`);

      this.$store.state.sessions.session = data;
      this.client = data.client;
      this.company = data.client.company;
      this.$store.commit("messages/SET_MESSAGES", data.messages);
    },
    setupListeners() {
      const self = this;

      // pusher init
      const PUSHER = new Pusher(process.env.MIX_PUSHER_APP_KEY, {
        cluster: process.env.MIX_PUSHER_APP_CLUSTER,
        encrypted: true
      });

      // channel to subscribe
      const CH_MESSAGE = PUSHER.subscribe("message");

      CH_MESSAGE.bind(`message.from.client`, async data => {
        let { message, session } = data;

        if ("session" in self.session && self.session.session == session) {
          // mart it as read
          axios.put(`/portal/session/${session}/seen`);

          self.$store.commit("messages/PUSH_MESSAGE", { ...message, attachments: [] });
        }
      });

      CH_MESSAGE.bind(`attachment.created`, async data => {
        let { attachment, client, message, session } = data;

        self.$store.commit("messages/INSERT_ATTACHMENT", {
          ...message,
          attachment
        });
      });
    }
  },
  async created() {
    this.isComponentReady = false;

    await this.fetchSession();
    if (this.$isEmpty(this.users)) this.$store.dispatch("auth/fetchUsers");
    this.setupListeners();

    // set the session to localStorage
    localStorage.setItem("LCS_Session", this.session.session);

    // mart it as read
    axios.put(`/portal/session/${this.session.session}/seen`);

    this.isComponentReady = true;
  }
};
</script>

<style lang="scss">
@import "@Styles/ticket.scss";
</style>
