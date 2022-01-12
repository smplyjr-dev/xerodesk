<template>
  <div class="card card-1 card-ticket m-4">
    <TicketHeader @toggle="isMetaOpen = !isMetaOpen" />

    <div class="card-body" :class="{ isMetaOpen }">
      <div class="h-100 w-100 flex-center" v-if="!isComponentReady">
        <Spinner height="5rem" width="5rem" />
      </div>

      <template v-else>
        <TicketContents />
        <TicketMetas />
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
  name: "Ticket",
  components: { TicketHeader, TicketContents, TicketMetas },
  layout: "Ticket",
  metaInfo: () => ({ title: "Ticket" }),
  middleware: ["auth", "permission:view_ticket"],
  data: () => ({
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
      this.$store.commit("messages/SET_MESSAGES", []);

      let include = "include=client.company,messages.attachments,taggables,user.bio";
      let { data } = await axios.get(`/portal/session/${this.$route.params.session}?${include}`);

      this.$store.state.sessions.session = data;
      this.$store.commit("messages/SET_MESSAGES", data.messages);
    }
  },
  async created() {
    this.isComponentReady = false;

    await this.fetchSession();
    if (this.$isEmpty(this.users)) this.$store.dispatch("auth/fetchUsers");

    // mart it as read
    axios.put(`/portal/session/${this.session.session}/seen`);

    this.isComponentReady = true;
  }
};
</script>

<style lang="scss">
@import "@Styles/pages/ticket.scss";
</style>
