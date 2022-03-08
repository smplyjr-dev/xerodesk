<template>
  <form-alert class="m-3" variant="info" v-if="session.status == 4">
    <p class="m-0">This is now a <strong>read-only</strong> ticket.</p>
  </form-alert>

  <div class="ticket-reply" v-else>
    <!-- Locking / Accepting -->
    <template v-if="$isNull(session.user_id) || isLocking">
      <div class="p-3">
        <button class="btn btn-brand-2 btn-block text-white" @click="lockSession" :disabled="isLocking">
          <span v-if="isLocking">Please wait... <i class="fa fa-spin fa-circle-notch" aria-hidden="true"></i></span>
          <span v-else>Accept &amp; Lock Client</span>
        </button>
      </div>
    </template>

    <!-- Show Notice -->
    <template v-else-if="session.user_id != user.id">
      <div class="p-3">
        <div class="alert alert-warning m-0"><strong>Notice!</strong> This client is not currently assigned to you. <a @click="grabSession" href="javascript:void(0)">Grab the ticket</a>.</div>
      </div>
    </template>

    <!-- Reply Form -->
    <template v-else>
      <div class="d-flex flex-column">
        <TicketReplyAttachment v-if="attachments.length" :attachments="attachments" @removeAttachment="removeAttachment" />
        <TicketReplyTo v-if="reply_to" :reply_to="reply_to" />

        <div class="d-flex align-items-center px-3 py-2">
          <emoji-picker @onSelect="emoji = $event">
            <button type="button" class="btn btn-default px-0">
              <InlineSvg name="svg/mdi/emoticon-happy-outline.svg" color="#d0d0d0" size="23px" />
            </button>
          </emoji-picker>

          <input type="file" ref="file" class="d-none" @change="onFileChange" :accept="accept" multiple />
          <TicketReplyEditor v-model="message" :emoji="emoji" @onEnter="submitChat()" />

          <button type="button" class="btn btn-default px-0" data-toggle="modal" data-target="#attm-modal">
            <InlineSvg name="svg/mdi/paperclip.svg" color="#d0d0d0" size="23px" />
          </button>

          <button type="button" class="btn btn-default px-0" @click="submitChat()">
            <InlineSvg name="svg/mdi/send.svg" color="#f15e4a" size="23px" />
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
import { tickets } from "@Scripts/observable";
import TicketReplyAttachment from "../reply/TicketReplyAttachment.vue";
import TicketReplyAttachmentModal from "../reply/TicketReplyAttachmentModal.vue";
import TicketReplyTo from "./TicketReplyTo.vue";
import TicketReplyEditor from "./TicketReplyEditor.vue";
import EmojiPicker from "@Components/neutral/EmojiPicker.vue";

export default {
  name: "TicketReply",
  components: { TicketReplyAttachment, TicketReplyAttachmentModal, TicketReplyTo, TicketReplyEditor, EmojiPicker },
  data: () => ({
    isGrabbing: false,
    isLocking: false,
    accept: "text/csv,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,image/gif,text/html,image/vnd.microsoft.icon,image/jpeg,image/png,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.rar,text/plain,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/zip,application/x-7z-compressed",
    extensions: ["csv", "doc", "docx", "gif", "htm", "html", "ico", "jpeg", "jpg", "png", "pdf", "pptx", "rar", "txt", "xls", "xlsx", "zip", "7z"],
    emoji: "",
    agent: null,
    message: "",
    attachments: [],
    reply_to: null
  }),
  computed: {
    ...mapState("auth", ["users", "user"]),
    ...mapState("messages", ["reply_to_shadow"]),
    ...mapGetters("auth", ["permissions"]),

    session: {
      get() {
        return this.$store.state.sessions.session;
      },
      set(newValue) {
        this.$store.state.sessions.session = newValue;
      }
    }
  },
  methods: {
    removeAttachment(e) {
      this.attachments = e;
    },
    async grabSession() {
      this.isGrabbing = true;

      let { data } = await axios.put(`/portal/session/${this.session.session}/grab`);

      // update the local copy of the session
      this.session.user = data;

      await axios.post(`/portal/message`, {
        hash: nanoid(),
        sender: "session",
        message: `<p>The session has been re-assigned to <strong>${this.session.user.bio.first_name} ${this.session.user.bio.last_name}</strong>.</p>`,
        client_id: this.session.client.id,
        session: this.session.session,
        user_id: this.user.id
      });

      this.isGrabbing = false;
    },
    async lockSession() {
      this.isLocking = true;

      let { data } = await axios.put(`/portal/session/${this.session.session}/lock`, {
        user_id: this.user.id,
        logger: "lock"
      });

      // update the local copy of the session
      this.session.status = 1;
      this.session.user = data;

      await axios.post(`/portal/message`, {
        hash: nanoid(),
        sender: "session",
        message: `<p>The session has been assigned to <strong>${this.session.user.bio.first_name} ${this.session.user.bio.last_name}</strong>.</p>`,
        client_id: this.session.client.id,
        session: this.session.session,
        user_id: this.user.id
      });

      this.isLocking = false;
    },
    async onFileChange(e) {
      [...e.target.files].forEach((file) => {
        this.attachments.push({
          file,
          ext: file.name.split(".").pop()
        });
      });
    },
    async handleFile() {
      let attachments = this.$store.state.messages.message_attachments;

      await Promise.all(
        attachments.map(async (attachment) => {
          let form = new FormData();
          let file = attachment.file;
          let headers = { "Content-Type": "multipart/form-data" };
          let message = { hash: nanoid(), sender: "admin", message: "" };
          if (this.$store.state.messages.reply_to) message.reply_to = this.$store.state.messages.reply_to;

          if (file.size < 2000000 && this.extensions.includes(attachment.ext.toLowerCase())) {
            form.append("id", this.session.client.id);
            form.append("extension", file.name.split(".").pop());
            form.append("session", this.$route.params.session);
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
              .post(`/portal/message`, {
                ...message,
                client_id: this.session.client.id,
                session: this.$route.params.session,
                user_id: this.user.id
              })
              .then((response) => {
                this.$store.commit("messages/UPDATE_MESSAGE", response.data.data);

                axios.post(`/portal/message/${response.data.data.hash}/attachment`, form, headers).then((result) => {
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
      ["reply_to_ticket"].forEach((allowed_permission) => {
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
            .post(`/portal/message`, {
              ...message,
              client_id: this.session.client.id,
              session: this.$route.params.session,
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
