<template>
  <div class="chat-wrapper" :class="{ selected: $route.params.session }">
    <Messages @toggle="open = !open" :open="open" />
    <Meta @toggle="open = !open" :class="{ open: open }" />
  </div>
</template>

<script>
import { mapState } from "vuex";
import Messages from "@Components/admin/chat/Messages.vue";
import Meta from "@Components/admin/chat/Meta.vue";

export default {
  layout: "Ticket",
  name: "Chats",
  metaInfo: () => ({ title: "Live Chat" }),
  middleware: "auth",
  components: { Messages, Meta },
  data: () => ({
    open: false
  }),
  computed: {
    ...mapState("sessions", ["session"])
  },
  created() {
    this.$store.dispatch("sessions/fetchSession", this.$route.params.session);
  }
};
</script>

<style lang="scss">
@import "@Styles/pages/chat.scss";
</style>
