<template>
  <div class="reply-to-wrapper" v-if="reply_to">
    <div class="reply-to-meta">
      <p class="replying-to">Replying to:</p>

      <div class="close" @click="removeReply()">
        <InlineSvg name="template/mdi-close.svg" color="#000" size="10px" />
      </div>
    </div>

    <div class="reply-to-body">
      <div class="quote"></div>
      <div class="content" v-if="message.message" v-html="message.message"></div>
      <div class="content" v-else>{{ `${message.attachments[0].name}.${message.attachments[0].extension}` }}</div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  props: ["reply_to"],
  name: "ReplyTo",
  computed: {
    ...mapState("messages", ["messages"]),

    message() {
      return this.messages.find(m => m.id == this.reply_to);
    }
  },
  methods: {
    removeReply() {
      this.$store.state.messages.reply_to_shadow = null;
    }
  }
};
</script>
