<template>
  <div class="ticket-metas">
    <div class="picture-style-a">
      <img class="client-img" :src="`${company.url}/${client.picture}`" alt="Client Picture" loading="lazy" />

      <div class="client-name">
        <h5 class="font-weight-bold mb-0">{{ client.name }}</h5>
        <span class="font-weight-semi mb-0 text-sm">{{ company.name }}</span>
      </div>
    </div>

    <ul class="list-style-a">
      <li>
        <p class="cd-title">Client ID</p>
        <p class="cd-info">#{{ `${client.id}`.padStart(6, 0) }}</p>
      </li>
      <li>
        <p class="cd-title">Client Email</p>
        <p class="cd-info">{{ client.email }}</p>
      </li>
      <li>
        <p class="cd-title">Client Phone</p>
        <p class="cd-info">{{ client.phone || "N/A" }}</p>
      </li>
    </ul>

    <template v-if="session.user_id == user.id">
      <ul class="list-style-a">
        <li>
          <p class="cd-title">Session Tags</p>
          <TicketTag :session="session" />
        </li>
        <li>
          <p class="cd-title">Session Title <span class="text-danger">*</span></p>
          <input type="text" class="form-control" v-model="session.title" />
          <span class="text-danger text-xs" v-show="sessionError.includes('title')">The session title field is required.</span>
        </li>
        <li>
          <p class="cd-title">Session Module <span class="text-danger">*</span></p>
          <select class="custom-select" v-model="session.module">
            <option :value="null">-- Select Module --</option>
            <option v-for="m in modules" :key="m">{{ m }}</option>
          </select>
          <span class="text-danger text-xs" v-show="sessionError.includes('module')">The session module field is required.</span>
        </li>
        <li>
          <p class="cd-title">Session Priority <span class="text-danger">*</span></p>
          <select class="custom-select" v-model="session.priority">
            <option :value="null">-- Select Priority --</option>
            <option v-for="p in priority" :key="p.id" :value="p.id">{{ p.name }}</option>
          </select>
          <span class="text-danger text-xs" v-show="sessionError.includes('priority')">The session priority field is required.</span>
        </li>
        <li>
          <p class="cd-title">Session Status <span class="text-danger">*</span></p>
          <select class="custom-select" v-model="session.status">
            <option :value="null">-- Select Status --</option>
            <option v-for="s in status" :key="s.id" :value="s.id">{{ s.name }}</option>
          </select>
          <span class="text-danger text-xs" v-show="sessionError.includes('status')">The session status field is required.</span>
        </li>
      </ul>
      <button class="btn btn-primary btn-block" @click="deleteSession" :disabled="isUpdating || session.user_id != user.id">
        <span v-if="isUpdating">
          Please wait...
          <i class="fa fa-spin fa-circle-o-notch" aria-hidden="true"></i>
        </span>

        <span v-else>Update Session</span>
      </button>
    </template>
    <template v-else>
      <form-alert variant="warning">
        <p>This session is not assigned to you.</p>
      </form-alert>
    </template>
  </div>
</template>

<script>
import { nanoid } from "nanoid";
import { mapState } from "vuex";
import { modules, tickets } from "@Scripts/observable";
import TicketTag from "@Components/admin/ticket/show/TicketTag.vue";

export default {
  components: { TicketTag },
  props: ["data"],
  data: () => ({
    modules: modules,
    priority: tickets.priority,
    status: tickets.status,

    sessionError: [],
    isUpdating: false
  }),
  computed: {
    ...mapState("auth", ["user"]),

    session() {
      return this.data.session;
    },
    client() {
      return this.data.client;
    },
    company() {
      return this.data.company;
    }
  },
  methods: {
    async updateSession() {
      this.isUpdating = true;
      this.sessionError = [];

      if (this.$isEmpty(this.session.title)) this.sessionError.push("title");
      if (this.$isEmpty(this.session.module)) this.sessionError.push("module");
      if (this.$isNull(this.session.priority)) this.sessionError.push("priority");
      if (this.$isNull(this.session.status)) this.sessionError.push("status");

      if (this.$isEmpty(this.sessionError)) {
        await axios.put(`/session/${this.session.session}`, {
          user_id: this.user.id,
          title: this.session.title,
          module: this.session.module,
          priority: this.session.priority,
          status: this.session.status,
          token: nanoid()
        });

        // show notification
        this.$store.dispatch("notifications/addNotification", {
          variant: "bg-success",
          icon: "fa-check",
          title: "Success!",
          body: `The session has been successfully updated.`
        });
      }

      this.isUpdating = false;
    },
    async deleteSession() {
      let { data } = await axios.delete(`/session/${this.session.session}`);
      console.log(data);
    }
  }
};
</script>
