<template>
  <div class="server-datatable">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
      <length @onSelect="handleOnSelect" />
    </div>

    <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
      <tbody class="position-relative text-sm">
        <transition name="fade">
          <!-- Update Record Notice -->
          <div class="updater" v-if="isUpdated">
            <div class="updater-content shadow" @click="getDatatable(), (isUpdated = !isUpdated)"><i class="fa fa-sync-alt mr-1" aria-hidden="true"></i> A new ticket has been found.</div>
          </div>
        </transition>

        <tr class="text-center" v-if="isLoading || !isReady">
          <td colspan="8">
            <div class="spinner-border text-lg my-4" style="height: 5rem; width: 5rem"></div>
          </td>
        </tr>

        <tr class="text-sm" v-else v-for="p in paginated" :key="p.id">
          <!-- Mobile View -->
          <td class="dt-mobile">
            <div class="d-flex">
              <div class="w-100 ml-2">
                <div class="dt-mobile-item">
                  <div class="title">SLA:</div>
                  <div class="content">
                    <TicketSla v-show="![3, 4].includes(p.status)" :p="p" :sla="sorted_slas" />
                  </div>
                </div>
                <div class="dt-mobile-item">
                  <div class="title">Client:</div>
                  <div class="content d-flex">
                    <span>{{ p.client || "No Name" }} </span>
                    <span class="ticket-counter--mobile" v-if="getUnreadCount(p) > 0" v-html="getUnreadCount(p)"></span>
                  </div>
                </div>
                <div class="dt-mobile-item">
                  <div class="title">Session:</div>
                  <div class="content">
                    <router-link :to="`/tickets/${p.session}`">{{ p.title || p.session }}</router-link>
                  </div>
                </div>
                <div class="dt-mobile-item">
                  <div class="title">Priority:</div>
                  <div class="content">
                    <app-dropdown @away="filter_priorities = ''">
                      <template v-slot:value>{{ !p.priority ? "Not Set" : `${priority.find((pr) => pr.id == p.priority).name}` }}</template>

                      <app-dropdown-content>
                        <template v-slot:content>
                          <app-dropdown-item v-for="fp in filtered_priority" :key="fp.id" @select="(p.priority = fp.id), updateFields(p, 'priority', p.priority)">
                            {{ fp.name }}
                          </app-dropdown-item>
                        </template>
                      </app-dropdown-content>
                    </app-dropdown>
                  </div>
                </div>
                <div class="dt-mobile-item">
                  <div class="title">Group:</div>
                  <div class="content">
                    <app-dropdown @away="filter_groups = ''">
                      <template v-slot:value>{{ !p.group_id ? "Not Set" : groups.find((g) => g.id == p.group_id).name }}</template>

                      <app-dropdown-content>
                        <template v-slot:content>
                          <app-dropdown-item v-for="fg in filtered_groups" :key="fg.id" @select="(p.group_id = fg.id), updateFields(p, 'group_id', p.group_id)">
                            {{ `${fg.name}` }}
                          </app-dropdown-item>
                        </template>
                      </app-dropdown-content>
                    </app-dropdown>
                  </div>
                </div>
                <div class="dt-mobile-item">
                  <div class="title">Agent:</div>
                  <div class="content">
                    <template v-if="p.agent_id">{{ `${users.find((u) => u.id == p.agent_id).bio.first_name} ${users.find((u) => u.id == p.agent_id).bio.last_name}` }}</template>
                    <template v-else>
                      <app-dropdown @away="filter_agents = ''" :disabled="ifNotAllowed() || ifAssignedAlready(p)">
                        <template v-slot:value>{{ !p.agent_id ? "Not Set" : `${users.find((u) => u.id == p.agent_id).bio.first_name} ${users.find((u) => u.id == p.agent_id).bio.last_name}` }}</template>

                        <app-dropdown-content>
                          <input slot="filter" type="text" class="form-control" placeholder="Enter a keyword..." v-model="filter_agents" />

                          <template v-slot:content>
                            <span class="px-2" v-show="!filtered_agents.length">No record found</span>

                            <app-dropdown-item v-for="u in filtered_agents" :key="u.id" @select="(p.agent_id = u.id), updateAgent(p)">
                              {{ `${u.bio.first_name} ${u.bio.last_name}` }}
                            </app-dropdown-item>
                          </template>
                        </app-dropdown-content>
                      </app-dropdown>
                    </template>
                  </div>
                </div>
                <div class="dt-mobile-item">
                  <div class="title">Status:</div>
                  <div class="content">
                    <template v-if="!p.agent_id">Unassigned</template>
                    <template v-else>
                      <app-dropdown @away="filter_statuses = ''" :disabled="isClosedAlready(p.status)">
                        <template v-slot:value>{{ `${status.find((s) => s.id == p.status).name}` }}</template>

                        <app-dropdown-content>
                          <template v-slot:content>
                            <app-dropdown-item v-for="fs in filtered_statuses" :key="fs.id" @select="(p.status = fs.id), updateFields(p, 'status', p.status)">
                              {{ fs.name }}
                            </app-dropdown-item>
                          </template>
                        </app-dropdown-content>
                      </app-dropdown>
                    </template>
                  </div>
                </div>
                <div class="dt-mobile-item">
                  <div class="title">Timestamp:</div>
                  <div class="content">
                    {{ $dayjs("format", p.created_at, "MM/DD/YYYY") }} <br />
                    {{ $dayjs("format", p.created_at, "hh:mm A") }}
                  </div>
                </div>
              </div>
            </div>
          </td>

          <!-- SLA -->
          <td class="pr-0">
            <TicketSla v-show="![3, 4].includes(p.status)" :p="p" :sla="sorted_slas" />
          </td>

          <!-- Client -->
          <td>
            <div class="d-flex align-items-center">
              <div class="position-relative">
                <img class="object-cover mr-2 rounded-circle" src="/images/generic-profile.png" @error="$onImgError($event, 1)" alt="Profile Picture" height="32px" width="32px" />
                <span class="ticket-counter" v-if="getUnreadCount(p) > 0" v-html="getUnreadCount(p)"></span>
              </div>
              <span>{{ p.client | capitalize }}</span>
            </div>
          </td>

          <!-- Session -->
          <td>
            <router-link :to="`/tickets/${p.session}`">{{ p.session }}</router-link>
          </td>

          <!-- Priority -->
          <td>
            <app-dropdown @away="filter_priorities = ''">
              <template v-slot:value>{{ !p.priority ? "Not Set" : `${priority.find((pr) => pr.id == p.priority).name}` }}</template>

              <app-dropdown-content>
                <template v-slot:content>
                  <app-dropdown-item v-for="fp in filtered_priority" :key="fp.id" @select="(p.priority = fp.id), updateFields(p, 'priority', p.priority)">
                    {{ fp.name }}
                  </app-dropdown-item>
                </template>
              </app-dropdown-content>
            </app-dropdown>
          </td>

          <!-- Groups -->
          <td>
            <app-dropdown @away="filter_groups = ''">
              <template v-slot:value>{{ !p.group_id ? "Not Set" : groups.find((g) => g.id == p.group_id).name }}</template>

              <app-dropdown-content>
                <template v-slot:content>
                  <app-dropdown-item v-for="fg in filtered_groups" :key="fg.id" @select="(p.group_id = fg.id), updateFields(p, 'group_id', p.group_id)">
                    {{ `${fg.name}` }}
                  </app-dropdown-item>
                </template>
              </app-dropdown-content>
            </app-dropdown>
          </td>

          <!-- Agent -->
          <td>
            <template v-if="p.agent_id">{{ `${users.find((u) => u.id == p.agent_id).bio.first_name} ${users.find((u) => u.id == p.agent_id).bio.last_name}` }}</template>
            <template v-else>
              <app-dropdown @away="filter_agents = ''" :disabled="ifNotAllowed() || ifAssignedAlready(p)">
                <template v-slot:value>{{ !p.agent_id ? "Not Set" : `${users.find((u) => u.id == p.agent_id).bio.first_name} ${users.find((u) => u.id == p.agent_id).bio.last_name}` }}</template>

                <app-dropdown-content>
                  <input slot="filter" type="text" class="form-control" placeholder="Enter a keyword..." v-model="filter_agents" />

                  <template v-slot:content>
                    <span class="px-2" v-show="!filtered_agents.length">No record found</span>

                    <app-dropdown-item v-for="u in filtered_agents" :key="u.id" @select="(p.agent_id = u.id), updateAgent(p)">
                      {{ `${u.bio.first_name} ${u.bio.last_name}` }}
                    </app-dropdown-item>
                  </template>
                </app-dropdown-content>
              </app-dropdown>
            </template>
          </td>

          <!-- Status -->
          <td>
            <template v-if="!p.agent_id">Unassigned</template>
            <template v-else>
              <app-dropdown @away="filter_statuses = ''" :disabled="isClosedAlready(p.status)" position="right">
                <template v-slot:value>{{ `${status.find((s) => s.id == p.status).name}` }}</template>

                <app-dropdown-content>
                  <template v-slot:content>
                    <app-dropdown-item v-for="fs in filtered_statuses" :key="fs.id" @select="(p.status = fs.id), updateFields(p, 'status', p.status)">
                      {{ fs.name }}
                    </app-dropdown-item>
                  </template>
                </app-dropdown-content>
              </app-dropdown>
            </template>
          </td>

          <!-- See More -->
          <td class="pl-0">
            <TicketPopup :p="p" />
          </td>
        </tr>

        <tr v-if="!isLoading && !paginated.length && isReady">
          <td colspan="8">
            <div class="w-100 my-3 flex-center flex-column">No result found.</div>
          </td>
        </tr>
      </tbody>
    </datatable>

    <div class="d-flex justify-content-between align-items-center flex-wrap">
      <entries :pagination="pagination" />
      <pagination :pagination="pagination" @prev="getDatatable(pagination.prevPageUrl)" @next="getDatatable(pagination.nextPageUrl)" />
    </div>
  </div>
</template>

<script>
import { nanoid } from "nanoid";
import { mapState } from "vuex";
import { tickets } from "@Scripts/observable";
import { Length, Search, Datatable, Entries, Pagination, Mixin } from "@SDT";
import TicketSla from "./TicketSla.vue";
import TicketPopup from "./TicketPopup.vue";

export default {
  props: ["isReady"],
  mixins: [Mixin],
  components: { Length, Search, Datatable, Entries, Pagination, TicketSla, TicketPopup },
  data() {
    let sortOrders = {};
    let types = ["string", "number", "date"];
    let columns = [
      { sortable: 0, hide: 0, type: types[0], width: "100%", name: "info", label: "Ticket Details" },
      { sortable: 0, hide: 0, type: types[0], width: "0%", name: "sla", label: "SLA" },
      { sortable: 1, hide: 0, type: types[0], width: "16.66%", name: "client", label: "Client" },
      { sortable: 1, hide: 0, type: types[0], width: "16.66%", name: "session", label: "Session" },
      { sortable: 1, hide: 0, type: types[0], width: "16.66%", name: "priority", label: "Priority" },
      { sortable: 1, hide: 0, type: types[1], width: "16.66%", name: "groups", label: "Groups" },
      { sortable: 1, hide: 0, type: types[0], width: "16.66%", name: "agent", label: "Agent" },
      { sortable: 1, hide: 0, type: types[1], width: "16.66%", name: "status", label: "Status" },
      { sortable: 0, hide: 0, type: types[1], width: "0%", name: "see_more", label: "" }
      // { sortable: 1, hide: 0, type: types[0], width: "14.3%", name: "title", label: "Title" },
      // { sortable: 1, hide: 0, type: types[2], width: "12.5%", name: "created_at", label: "Timestamp" }
    ];
    columns.forEach((column) => {
      sortOrders[column.name] = -1;
    });
    return {
      // for datatable only
      columns: columns,
      sortKey: null,
      sortOrders: sortOrders,
      tableData: {
        column: 7 // column where sortation will happen
      },

      // custom data
      isLoading: false,
      isUpdated: false,
      priority: tickets.priority,
      status: tickets.status,

      // dropdown filters
      filter_priorities: "",
      filter_groups: "",
      filter_agents: "",
      filter_statuses: "",

      // ticket details modal
      ticket: null
    };
  },
  computed: {
    ...mapState("auth", ["user", "users"]),
    ...mapState("groups", ["groups"]),
    ...mapState("slas", ["slas"]),

    sorted_slas() {
      return this.slas
        .sort((a, b) => {
          return a.range - b.range;
        })
        .map((s) => ({
          ...s,
          range: s.range * 60
        }));
    },
    filtered_priority() {
      let search = this.filter_priorities;
      let filtered = this.priority.filter((p) => {
        let name = p.name.toLowerCase();

        return name.includes(search.toLowerCase());
      });

      return filtered;
    },
    filtered_groups() {
      let search = this.filter_groups;
      let filtered = this.groups.filter((g) => {
        let name = g.name.toLowerCase();

        return name.includes(search.toLowerCase());
      });

      return filtered;
    },
    filtered_agents() {
      let search = this.filter_agents;
      let filtered = this.users.filter((u) => {
        let name = `${u.bio.first_name} ${u.bio.last_name}`.toLowerCase();

        return name.includes(search.toLowerCase());
      });

      return filtered;
    },
    filtered_statuses() {
      let search = this.filter_statuses;
      let filtered = this.status.filter((s) => {
        let name = s.name.toLowerCase();

        return name.includes(search.toLowerCase());
      });

      return filtered;
    }
  },
  methods: {
    getDatatable(url = `/portal/session/datatable`) {
      // this.isLoading = true;
      this.tableData.draw++;

      axios
        .get(url, { params: this.tableData })
        .then((response) => {
          let data = response.data;
          if (this.tableData.draw == data.draw) {
            this.paginated = data.data.data;
            this.configPagination(data.data);
            // this.isLoading = false;
          }
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    setupListeners() {
      // channel to subscribe
      const CH_SESSION = window.pusher.subscribe("session");
      const CH_MESSAGE = window.pusher.subscribe("message");

      CH_SESSION.bind(`session.created`, async (data) => {
        this.isUpdated = true;
      });

      CH_MESSAGE.bind(`message.from.client`, async (data) => {
        let { message, session } = data;

        // push the message to its session
        this.paginated = this.paginated.map((p) => {
          if (p.session == session) {
            let updated = p;
            updated.messages.push(message);

            return updated;
          }

          return p;
        });
      });
    },
    updateFields(session, field, val) {
      let data = {};

      if (field == "priority") data = { priority: val };
      if (field == "group_id") data = { group_id: val };
      if (field == "user_id") data = { user_id: val };

      // for request for status update
      if (field != "status") return axios.put(`/portal/session/${session.session}/field`, data);

      // for request other than status update
      return axios.put(`/portal/session/${session.session}/status`, {
        status: val,
        hash: nanoid(),
        sender: "session",
        message: `<p>The status of the ticket has been updated to <strong>${tickets.status.find((s) => s.id == val).name}</strong>.</p>`
      });
    },
    updateAgent(s) {
      let agent = this.users.find((u) => u.id == s.agent_id);

      axios.put(`/portal/session/${s.session}/lock`, {
        ...s,
        user_id: agent.id
      });

      axios.post(`/portal/message`, {
        hash: nanoid(),
        sender: "session",
        message: `<p>The session has been assigned to <strong>${agent.bio.first_name} ${agent.bio.last_name}</strong>.</p>`,
        // message: `<p>Thank you for waiting. <br /> You are now connected to agent <strong>${agent.bio.first_name} ${agent.bio.last_name}</strong>.</p>`,
        client_id: s.id,
        session: s.session,
        user_id: this.user.id
      });
    },
    setTableData() {
      let defaultData = {
        draw: this.tableData.draw,
        length: this.tableData.length,
        search: this.tableData.search,
        column: this.tableData.column,
        dir: this.tableData.dir
      };

      if (this.$isEmpty(this.$route.query)) {
        this.tableData = defaultData;
      } else {
        this.tableData = {
          ...defaultData,
          ...this.$route.query
        };
      }
    },
    isClosedAlready(status) {
      if (status == 4) return true;
      return false;
    },
    ifNotAllowed() {
      return !this.user.permissions.pluck("slug").includes("assign_ticket");
    },
    ifAssignedAlready(ticket) {
      // if the ticket is assigned already
      // you must do the transfer inside the ticket
      if (ticket.agent_id) return true;
    },
    getUnreadCount(p) {
      return p.messages.filter((m) => m.sender == "client" && m.is_read == false).length;
    }
  },
  created() {
    this.setupListeners();
    this.setTableData();
  },
  watch: {
    $route() {
      this.setTableData();
      this.getDatatable();
    }
  },
  destroyed() {
    window.pusher.unsubscribe("session");
    window.pusher.unsubscribe("message");
  }
};
</script>
