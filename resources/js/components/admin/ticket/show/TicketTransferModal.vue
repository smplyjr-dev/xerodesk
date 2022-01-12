<template>
  <div id="transfer-ticket-modal" class="modal fade">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Transfer Session</h5>

          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form @submit.prevent="transferClient()">
          <div class="modal-body">
            <div class="form-group">
              <label for="xrf_from">Transfer from</label>
              <input id="xrf_from" type="text" class="form-control" :placeholder="current" disabled />
            </div>
            <div class="flex-center">
              <InlineSvg name="heroicons/switch-vertical.svg" size="1.5rem" color="#686868" />
            </div>
            <div class="form-group">
              <label for="xrf_to">Transfer to</label>
              <input id="xrf_to" type="text" class="form-control" autocomplete="off" v-model="agent" @focus="$store.dispatch('auth/fetchUsers')" @keyup="onKeyup" />
            </div>
            <ul class="datalist" v-if="list && filtered.length">
              <li v-for="f in filtered" :key="f.id" @click="onSelect(f)">
                {{ `${f.bio.first_name} ${f.bio.last_name}` }}
              </li>
            </ul>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Transfer</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { nanoid } from "nanoid";
import { mapState } from "vuex";

export default {
  data: () => ({
    agent: null,
    list: false,
    selected: null,
    transfering: false
  }),
  computed: {
    ...mapState("auth", ["users", "user"]),
    ...mapState("sessions", ["session"]),

    filtered() {
      if (!this.agent) return [];

      // remove current user from the choices
      let sorted = this.users.filter((u) => u.id != this.user.id);

      // show the users base on what the agent typed
      sorted = sorted.filter((user) => {
        let name = `${user.bio.first_name} ${user.bio.last_name}`;
        return !name.toLowerCase().indexOf(this.agent.toLowerCase());
      });

      return sorted;
    },
    current() {
      if (this.session.user) return `${this.session.user.bio.first_name} ${this.session.user.bio.last_name}`;
    }
  },
  methods: {
    onKeyup() {
      this.list = true;
    },
    onSelect(user) {
      this.agent = `${user.bio.first_name} ${user.bio.last_name}`;
      this.selected = user;
      this.list = false;
    },
    async transferClient() {
      this.transfering = true;

      try {
        // save the message
        let { data } = await axios.post(`/portal/message`, {
          hash: nanoid(),
          sender: "session",
          message: `<p>The session has been re-assigned to <strong>${this.selected.bio.first_name} ${this.selected.bio.last_name}</strong>.</p>`,
          client_id: this.session.client_id,
          session: this.session.session,
          user_id: this.user.id
        });

        // update the session user_id
        await axios.put(`/portal/session/${this.session.session}/transfer`, {
          old_user_id: this.session.user_id,
          new_user_id: this.selected.id
        });

        // push the message immediately for real-time experience
        this.$store.commit("messages/PUSH_MESSAGE", data.data);

        // hide the modal
        $("#transfer-ticket-modal").modal("hide");

        // update the old user
        this.session.user = this.selected;
        this.agent = null;
        this.selected = null;
      } catch (error) {
        // display a notification
        this.$store.dispatch("notifications/addNotification", {
          variant: "bg-danger",
          icon: "fa-times",
          title: "Invalid.",
          body: "Something went wrong. Please contact your system administrator."
        });
      }

      this.transfering = false;
    }
  }
};
</script>
