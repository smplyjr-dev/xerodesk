<template>
  <div class="ticket-messages">
    <div class="message-bubble" v-for="(message, $mIndex) in messages" :key="$mIndex" :class="messageFrom(message)">
      <div class="message-content">
        <div class="message-actions" v-if="message.sender != 'session'">
          <button type="button" class="action" @click="onReply(message)">
            <InlineSvg name="template/mdi-arrow-left-top.svg" color="#000" size="1rem" />
          </button>
          <button type="button" class="action" @click="onDownload(message)" v-show="!$isEmpty(message.attachments)">
            <InlineSvg name="template/mdi-download.svg" color="#000" size="1rem" />
          </button>
        </div>
        <div class="message-group" v-tooltip:top="`${messageDetails(message)}`">
          <TicketReplyingTo v-if="message.reply_to" :message="message" />
          <div class="message-dtls" v-html="message.message"></div>
          <div class="message-clear"></div>
          <div class="message-attm" v-if="!$isEmpty(message.attachments)">
            <div class="message-attm-item" v-for="attachment in message.attachments" :key="attachment.id">
              <div class="message-attm-item-image" v-if="['ico', 'jpeg', 'jpg', 'png'].includes(attachment.extension)" @click="enlargeAtt(attachment)">
                <img :src="`${$APP_URL}/storage/uploads/clients/${data.client.token}/${data.session.session}/${attachment.name}.${attachment.extension}`" />
              </div>
              <div class="message-attm-item-file" v-else>
                <div class="message-attm-item-icon" @click="downloadAtt(attachment)">
                  <InlineSvg :name="`heroicons/${getIcon(attachment.extension)}.svg`" color="#000" size="25px" />
                </div>
                <div class="message-attm-item-name" @click="downloadAtt(attachment)">
                  <span>{{ `${attachment.name}.${attachment.extension}` }}</span>
                </div>
              </div>
            </div>
          </div>
          <div class="message-date">
            <span>{{ $dayjs("format", message.created_at, "MM/DD/YYYY - hh:mm A") }}</span>
            <span v-if="['admin', 'client'].includes(message.sender)">
              <span>&#8226;</span>
              <template v-if="message.sender == 'admin'">{{ getSender(message) }}</template>
              <template v-else>{{ data.client.name }}</template>
            </span>
          </div>
          <Spinner v-if="$isEmpty(message.message) && $isEmpty(message.attachments)" />
        </div>
      </div>
    </div>

    <TicketReply :data="data" />

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
import TicketReply from "@Components/admin/ticket/show/reply/TicketReply.vue";

export default {
  components: { TicketReplyingTo, TicketReply },
  props: ["data"],
  data: () => ({
    enlargeToggle: false,
    enlargeUrl: null
  }),
  computed: {
    ...mapState("auth", ["users"]),
    ...mapState("messages", ["messages"]),

    coupled() {
      let raw_messages = this.messages;
      let new_messages = [];

      raw_messages.forEach((m, $m) => {
        if ($m > 0) {
          // if coming from the same sender
          if (m.sender == raw_messages[$m].sender) {
            let date_prev = new Date(raw_messages[$m - 1].created_at);
            let date_curr = new Date(m.created_at);
            if (date_prev.getMinutes() == date_curr.getMinutes()) {
              new_messages[new_messages.length - 1].push(m);
            } else {
              new_messages.push([m]);
            }
          }
        } else {
          new_messages.push([m]);
        }
      });

      return new_messages;
    }
  },
  methods: {
    messageDetails(message) {
      let date = this.$dayjs("format", message.created_at, "MM/DD/YYYY");
      let time = this.$dayjs("format", message.created_at, "hh:mm A");
      let sender = message.sender == "client" ? this.data.client.name : "Admin";

      return `Sent Date: ${date} <br /> Sent Time: ${time} <br /> Sent By: ${sender}`;
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
