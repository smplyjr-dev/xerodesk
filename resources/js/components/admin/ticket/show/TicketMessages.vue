<template>
  <div class="ticket-messages">
    <div class="message-bubble" v-for="(message, $mIndex) in messages" :key="$mIndex" :class="messageFrom(message)">
      <div class="message-content">
        <div class="message-group">
          <TicketReplyingTo v-if="message.reply_to" :message="message" />
          <div class="message-dtls" v-if="message.message">
            <div class="message-dtls--reply" v-if="message.sender != 'session'">
              <button type="button" class="action" @click="onReply(message)">
                <InlineSvg name="template/mdi-arrow-left-top.svg" color="#000" size="1rem" />
              </button>
            </div>
            <div class="message-dtls--content" v-html="message.message"></div>
          </div>
          <div class="message-attm" v-if="!$isEmpty(message.attachments)">
            <div class="message-attm--reply" v-if="message.sender != 'session'">
              <button type="button" class="action" @click="onReply(message)">
                <InlineSvg name="template/mdi-arrow-left-top.svg" color="#000" size="1rem" />
              </button>
              <button type="button" class="action" @click="onDownload(message)" v-show="!$isEmpty(message.attachments)">
                <InlineSvg name="template/mdi-download.svg" color="#000" size="1rem" />
              </button>
            </div>
            <div class="message-attm--item" v-for="attachment in message.attachments" :key="attachment.id">
              <div class="message-attm--item-image" v-if="['ico', 'jpeg', 'jpg', 'png'].includes(attachment.extension)" @click="enlargeAtt(attachment)">
                <img :src="`${$APP_URL}/storage/uploads/clients/${data.client.token}/${data.session.session}/${attachment.name}.${attachment.extension}`" />
              </div>
              <div class="message-attm--item-file" v-else>
                <div class="message-attm--item-icon" @click="downloadAtt(attachment)">
                  <InlineSvg :name="`heroicons/${getIcon(attachment.extension)}.svg`" color="#000" size="25px" />
                </div>
                <div class="message-attm--item-name" @click="downloadAtt(attachment)">
                  <span>{{ `${attachment.name}.${attachment.extension}` }}</span>
                </div>
              </div>
            </div>
          </div>
          <div class="message-date" v-if="shouldShow(message, $mIndex)">
            <span>{{ $dayjs("format", message.created_at, "MM/DD/YYYY - hh:mm A") }}</span>
            <span v-if="['admin', 'client'].includes(message.sender)">
              &#8226;
              <template v-if="message.sender == 'admin'">{{ getSender(message) }}</template>
              <template v-else>{{ data.client.name }}</template>
            </span>
          </div>
          <Spinner v-if="$isEmpty(message.message) && $isEmpty(message.attachments)" />
        </div>
      </div>
    </div>

    <transition name="fade">
      <div class="large-attm" v-if="enlargeToggle" @click.self="enlargeToggle = false">
        <img :src="enlargeUrl" />
      </div>
    </transition>

    <div id="scroll-to"></div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import TicketReplyingTo from "@Components/admin/ticket/show/TicketReplyingTo.vue";

export default {
  components: { TicketReplyingTo },
  props: ["data"],
  data: () => ({
    enlargeToggle: false,
    enlargeUrl: null
  }),
  computed: {
    ...mapState("auth", ["users"]),
    ...mapState("messages", ["messages"])
  },
  methods: {
    shouldShow(message, index) {
      let next = this.messages[index + 1];

      if (message.sender == "session") return true; // for session message
      if (next == undefined) return true; // for last message

      if (message.sender == next.sender) {
        let curr_date = new Date(message.created_at);
        let next_date = new Date(next.created_at);
        let diff_date = next_date - curr_date;
        let minutes = Math.floor(diff_date / 1000 / 60);

        if (minutes < 1) return false;
        else return true;
      }

      return true;
    },
    messageFrom(message) {
      return {
        "from-admin": message.sender == "admin",
        "from-client": message.sender == "client",
        "from-session": message.sender == "session"
      };
    },
    getIcon(ext) {
      // documents
      if (["doc", "docx", "htm", "html", "pdf", "txt", "pptx"].includes(ext)) return "document-text";

      // spread sheet
      if (["csv", "xls", "xlsx"].includes(ext)) return "document-report";

      // images
      if (["gif", "ico", "jpeg", "jpg", "png"].includes(ext)) return "photograph";

      // compressed
      if (["rar", "zip", "7z"].includes(ext)) return "folder";
    },
    getSender(message) {
      let user = this.users.find(u => u.id == message.user_id);

      if (user) return `${user.bio.first_name} ${user.bio.last_name}`;
      return;
    },
    downloadAtt(att) {
      let url = `${this.$APP_URL}/storage/uploads/clients/${this.data.client.token}/${this.data.session.session}/${att.name}.${att.extension}`;
      let link = document.createElement("a");

      // if you don't know the name or want to use the webserver default set name = ''
      link.setAttribute("download", "");
      link.setAttribute("target", "_blank");
      link.href = url;

      document.body.appendChild(link);
      link.click();
      link.remove();
    },
    onReply(message) {
      this.$store.state.messages.reply_to_shadow = message.id;
    },
    onDownload(message) {
      this.downloadAtt(message.attachments[0]);
    },
    enlargeAtt(att) {
      this.enlargeUrl = `${this.$APP_URL}/storage/uploads/clients/${this.data.client.token}/${this.data.session.session}/${att.name}.${att.extension}`;
      this.enlargeToggle = true;
    }
  },
  watch: {
    messages: {
      deep: true,
      handler: function() {
        setTimeout(() => {
          let scrollTo = this.$el.querySelector("#scroll-to");
          scrollTo.scrollIntoView({ behavior: "smooth" });
        }, 100);
      }
    }
  }
};
</script>
