<template>
  <div class="message-reply border-top">
    <!-- Locking / Accepting -->
    <template v-if="$isNull(session.user_id)">
      <div class="p-3">
        <button class="btn btn-brand-2 btn-block text-white" @click="lockSession" :disabled="isLocking">
          <span v-if="isLocking">Please wait... <i class="fa fa-spin fa-circle-o-notch" aria-hidden="true"></i></span>
          <span v-else>Accept &amp; Lock Client</span>
        </button>
      </div>
    </template>

    <!-- Transfer Session to different Agent -->
    <template v-else-if="session.user_id != user.id">
      <template v-if="user.permissions.pluck('slug').includes('transfer_ticket')">
        <form class="form-inline pull-right p-2" @submit.prevent="transferClient()">
          <label class="my-1 mr-2" for="to-agent">Transfer to:</label>

          <select class="custom-select my-1 mr-sm-2" id="to-agent" v-model.number="agent" @focus="$store.dispatch('auth/fetchUsers')">
            <option :value="null">-- Select Agent --</option>
            <option :value="u.id" v-for="u in users" :key="u.id">
              {{ `${u.bio.last_name}, ${u.bio.first_name}` }}
            </option>
          </select>

          <button type="submit" class="btn btn-primary my-1" :disabled="!agent">Transfer</button>
        </form>
      </template>

      <template v-else>
        <div class="alert alert-warning m-0"><strong>Notice!</strong> This client is not currently assigned to you.</div>
      </template>
    </template>

    <!-- Reply Form -->
    <template v-else>
      <div class="d-flex flex-column">
        <TicketReplyEmoji v-if="picker" @selectEmoji="selectEmoji" />
        <TicketReplyAttachment v-if="attachments.length" :attachments="attachments" @removeAttachment="removeAttachment" />
        <TicketReplyTo v-if="reply_to" :reply_to="reply_to" />

        <div class="d-flex align-items-center px-3 py-2">
          <button type="button" class="btn btn-default px-0" @click="picker = !picker">
            <InlineSvg name="template/mdi-emoticon-happy-outline.svg" color="#d0d0d0" size="23px" />
          </button>

          <input type="file" ref="file" class="d-none" @change="onFileChange" :accept="accept" multiple />
          <!-- <input type="text" class="form-control session-input" placeholder="Type a message..." v-model="message" @keyup.enter="submitChat()" /> -->
          <TicketReplyEditor v-model="message" :emoji="emoji" @onEnter="submitChat()" />

          <button type="button" class="btn btn-default px-0" data-toggle="modal" data-target="#attm-modal">
            <InlineSvg name="template/mdi-paperclip.svg" color="#d0d0d0" size="23px" />
          </button>

          <button type="button" class="btn btn-default px-0" @click="submitChat()">
            <InlineSvg name="template/mdi-send.svg" color="#f15e4a" size="23px" />
          </button>
        </div>
      </div>
    </template>

    <TicketReplyAttachmentModal :attachments="attachments" @emitAttachments="attachments = $event" />
  </div>
</template>

<script>
import { mapState, mapGetters } from "vuex";
import { nanoid } from "nanoid";
import TicketReplyEmoji from "../reply/TicketReplyEmoji.vue";
import TicketReplyAttachment from "../reply/TicketReplyAttachment.vue";
import TicketReplyAttachmentModal from "../reply/TicketReplyAttachmentModal.vue";
import TicketReplyTo from "../reply/TicketReplyTo.vue";
import TicketReplyEditor from "./TicketReplyEditor.vue";

export default {
  name: "TicketReply",
  components: { TicketReplyEmoji, TicketReplyAttachment, TicketReplyAttachmentModal, TicketReplyTo, TicketReplyEditor },
  props: ["data"],
  data: () => ({
    isLocking: false,
    accept: "text/csv,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,image/gif,text/html,image/vnd.microsoft.icon,image/jpeg,image/png,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.rar,text/plain,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/zip,application/x-7z-compressed",
    extensions: ["csv", "doc", "docx", "gif", "htm", "html", "ico", "jpeg", "jpg", "png", "pdf", "pptx", "rar", "txt", "xls", "xlsx", "zip", "7z"],
    emoji: "",
    picker: false,
    agent: null,
    message: "",
    attachments: [],
    reply_to: null
  }),
  computed: {
    ...mapState("auth", ["users", "user"]),
    ...mapState("clients", ["clients"]),
    ...mapState("messages", ["reply_to_shadow"]),
    ...mapGetters("auth", ["permissions"]),

    session() {
      return this.data.session;
    }
  },
  methods: {
    selectEmoji(e) {
      this.picker = false;
      this.emoji = e;
    },
    removeAttachment(e) {
      this.attachments = e;
    },
    async lockSession() {
      this.isLocking = true;

      let session = await axios.put(`/session/${this.session.session}`, {
        ...this.session,
        user_id: this.user.id
      });
      this.session.user_id = session.data.user_id;

      let message = await axios.post(`/message`, {
        hash: nanoid(),
        sender: "session",
        message: `<p>Thank you for waiting. <br /> You are now connected to agent ${this.user.bio.first_name} ${this.user.bio.last_name}.</p>`,
        client_id: this.data.client.id,
        session: this.session.session
      });
      this.$store.commit("messages/PUSH_MESSAGE", message.data.data);

      this.isLocking = false;
    },
    async transferClient() {
      let { data } = await axios.put(`/session/${this.session.session}/transfer`, {
        old_user_id: this.session.user_id,
        new_user_id: this.agent
      });

      if (data.status == "success") {
        // display a notification
        this.$store.dispatch("notifications/addNotification", {
          variant: "bg-success",
          icon: "fa-check",
          title: "Success!",
          body: data.message
        });
      } else {
        // display a notification
        this.$store.dispatch("notifications/addNotification", {
          variant: "bg-danger",
          icon: "fa-times",
          title: "Invalid.",
          body: data.message
        });
      }

      await this.$store.dispatch("clients/fetchClientsFromSessions");
    },
    async onFileChange(e) {
      [...e.target.files].forEach(file => {
        this.attachments.push({
          file,
          ext: file.name.split(".").pop()
        });
      });
    },
    async handleFile() {
      let attachments = this.$store.state.messages.message_attachments;

      await Promise.all(
        attachments.map(async attachment => {
          let form = new FormData();
          let file = attachment.file;
          let headers = { "Content-Type": "multipart/form-data" };
          let message = { hash: nanoid(), sender: "admin", message: "" };
          if (this.$store.state.messages.reply_to) message.reply_to = this.$store.state.messages.reply_to;

          if (file.size < 20000000 && this.extensions.includes(attachment.ext)) {
            form.append("id", this.data.client.id);
            form.append("extension", file.name.split(".").pop());
            form.append("session", localStorage.getItem("LCS_Session"));
            form.append("name", file.name.split(".").slice(0, -1).join(".")); // prettier-ignore
            form.append("file", file);

            // push the message immediately for real-time experience
            this.$store.commit("messages/PUSH_MESSAGE", {
              ...message,
              created_at: this.$dayjs("format", new Date(), "YYYY-MM-DD HH:mm:ss"),
              attachments: []
            });

            // then save the attachments in background
            await axios
              .post(`/message`, {
                ...message,
                client_id: this.data.client.id,
                session: localStorage.getItem("LCS_Session")
              })
              .then(response => {
                this.$store.commit("messages/UPDATE_MESSAGE", response.data.data);

                axios.post(`/message/${response.data.data.hash}/attachment`, form, headers).then(result => {
                  // this.$store.commit("messages/INSERT_ATTACHMENT", {
                  //   hash: response.data.data.hash,
                  //   attachment: result.data
                  // });
                });
              });
          }
        })
      );
    },
    async submitChat() {
      let is_allowed = true;

      // check permission
      ["reply_to_ticket"].forEach(allowed_permission => {
        if (!this.permissions.includes(allowed_permission)) {
          // display a notification
          this.$store.dispatch("notifications/addNotification", {
            variant: "bg-danger",
            icon: "fa-times",
            title: "Invalid.",
            body: "Sorry! But you don't have the permission to reply to a conversation."
          });

          is_allowed = false;
        }
      });

      if (is_allowed) {
        this.$store.state.messages.message = this.message;
        this.$store.state.messages.message_attachments = this.attachments;
        this.$store.state.messages.reply_to = this.reply_to;

        this.message = "";
        this.attachments = [];
        this.$store.state.messages.reply_to_shadow = null;

        // if message is not empty
        if (this.$store.state.messages.message) {
          // message details
          let message = { hash: nanoid(), sender: "admin", message: this.$store.state.messages.message };
          if (this.$store.state.messages.reply_to) message.reply_to = this.$store.state.messages.reply_to;

          // push the message to the chat box
          this.$store.commit("messages/PUSH_MESSAGE", {
            ...message,
            created_at: this.$dayjs("format", new Date(), "YYYY-MM-DD HH:mm:ss"),
            attachments: []
          });

          // then save the message in background
          await axios
            .post(`/message`, {
              ...message,
              client_id: this.data.client.id,
              session: localStorage.getItem("LCS_Session"),
              user_id: this.user.id
            })
            .then(({ data }) => {
              this.$store.commit("messages/UPDATE_MESSAGE", data.data);
            });
        }

        // if there are attachment, process it
        if (this.$store.state.messages.message_attachments) this.handleFile();
      }
    }
  },
  watch: {
    reply_to_shadow(newVal) {
      this.reply_to = newVal;
    }
  }
};
</script>
