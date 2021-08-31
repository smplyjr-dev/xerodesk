<template>
  <div class="message-rpy" v-if="replyingTo(message.reply_to).message" v-html="replyingTo(message.reply_to).message"></div>
  <div class="message-rpy" v-else v-html="`${replyingTo(message.reply_to).attachments[0].name}.${replyingTo(message.reply_to).attachments[0].extension}`"></div>
</template>

<script>
import { mapState } from "vuex";

export default {
  props: ["message"],
  name: "TicketReplyingTo",
  computed: {
    ...mapState("messages", ["messages"])
  },
  methods: {
    replyingTo(e) {
      return this.messages.find(s => s.id == e);
    }
  }
};
</script>
