<template>
  <div class="server-datatable">
    <search @onSelect="handleOnSelect" @onSearch="searchDatatable($event)" />

    <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
      <tbody class="text-sm">
        <transition name="fade">
          <!-- Update Record Notice -->
          <div class="updater" v-if="isUpdated" @click="getDatatable(), (isUpdated = !isUpdated)">
            <div class="updater-content shadow"><i class="fa fa-refresh mr-1" aria-hidden="true"></i> A new ticket has been found.</div>
          </div>
        </transition>

        <tr class="text-center" v-if="isLoading || !isReady">
          <td colspan="8">
            <div class="spinner-border text-lg my-4" style="height: 5rem; width: 5rem;"></div>
          </td>
        </tr>

        <tr class="text-sm" v-else v-for="p in paginated" :key="p.id">
          <!-- Mobile View -->
          <td class="dt-mobile">
            <div class="sla-mobile" :style="{ background: getSla(p) }"></div>
            <div class="dt-mobile-item">
              <div class="title">Name:</div>
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
                <app-dropdown @away="filter_priorities = ''" :disabled="isAssigned(p.agent_id)">
                  <template slot="value">{{ !p.priority ? "--" : `${priority.find(pr => pr.id == p.priority).name}` }}</template>

                  <app-dropdown-content>
                    <div class="p-2">
                      <input type="text" class="form-control" placeholder="Enter a keyword..." v-model="filter_priorities" />
                    </div>

                    <app-dropdown-item v-for="fp in filtered_priority" :key="fp.id" @select="(p.priority = fp.id), updateFields(p.session, 'priority', p.priority)">
                      {{ fp.name }}
                    </app-dropdown-item>
                  </app-dropdown-content>
                </app-dropdown>
              </div>
            </div>
            <div class="dt-mobile-item">
              <div class="title">Group:</div>
              <div class="content">
                <app-dropdown @away="filter_groups = ''" :disabled="isAssigned(p.agent_id)">
                  <template slot="value">{{ !p.group_id ? "--" : groups.find(g => g.id == p.group_id).name }}</template>

                  <app-dropdown-content>
                    <div class="p-2">
                      <input type="text" class="form-control" placeholder="Enter a keyword..." v-model="filter_groups" />
                    </div>

                    <app-dropdown-item v-for="fg in filtered_groups" :key="fg.id" @select="(p.group_id = fg.id), updateFields(p.session, 'group_id', p.group_id)">
                      {{ `${fg.name}` }}
                    </app-dropdown-item>
                  </app-dropdown-content>
                </app-dropdown>
              </div>
            </div>
            <div class="dt-mobile-item">
              <div class="title">Agent:</div>
              <div class="content">
                <app-dropdown @away="filter_agents = ''" :disabled="isAssigned(p.agent_id) || isAllowed()">
                  <template slot="value">{{ !p.agent_id ? "--" : `${users.find(u => u.id == p.agent_id).bio.first_name} ${users.find(u => u.id == p.agent_id).bio.last_name}` }}</template>

                  <app-dropdown-content>
                    <div class="p-2">
                      <input type="text" class="form-control" placeholder="Enter a keyword..." v-model="filter_agents" />
                    </div>

                    <app-dropdown-item v-for="u in filtered_agents" :key="u.id" @select="(p.agent_id = u.id), updateFields(p.session, 'user_id', p.agent_id)">
                      {{ `${u.bio.first_name} ${u.bio.last_name}` }}
                    </app-dropdown-item>
                  </app-dropdown-content>
                </app-dropdown>
              </div>
            </div>
            <div class="dt-mobile-item">
              <div class="title">Status:</div>
              <div class="content">
                <app-dropdown @away="filter_statuses = ''" :disabled="isAssigned(p.agent_id)">
                  <template slot="value">{{ `${status.find(s => s.id == p.status).name}` }}</template>

                  <app-dropdown-content>
                    <div class="p-2">
                      <input type="text" class="form-control" placeholder="Enter a keyword..." v-model="filter_statuses" />
                    </div>

                    <app-dropdown-item v-for="fs in filtered_statuses" :key="fs.id" @select="(p.status = fs.id), updateFields(p.session, 'status', p.status)">
                      {{ fs.name }}
                    </app-dropdown-item>
                  </app-dropdown-content>
                </app-dropdown>
              </div>
            </div>
            <div class="dt-mobile-item">
              <div class="title">Timestamp:</div>
              <div class="content">{{ $dayjs("format", p.created_at, "MM/DD/YYYY h:mm A") }}</div>
            </div>
          </td>

          <!-- Client -->
          <td>
            <div class="d-flex">
              <div class="ticket-color" :style="{ background: getSla(p) }"></div>
              <div class="d-flex w-100" style="position: relative;">
                <img class="object-cover mx-2" :src="`https://ui-avatars.com/api/?font-size=0.35&name=${p.client || 'No Name'}`" v-fallback="`/images/generic-profile.png`" alt="Profile Picture" height="100%" width="40px" />
                <div class="d-flex flex-column">
                  <span>{{ p.client || "No Name" }}</span>
                  <span class="text-muted text-xs">{{ p.client_email || "No Email" }}</span>
                  <span class="ticket-counter" v-if="getUnreadCount(p) > 0" v-html="getUnreadCount(p)"></span>
                </div>
              </div>
            </div>
          </td>

          <!-- Title -->
          <td>
            <router-link :to="`/tickets/${p.session}`">{{ p.title || p.session }}</router-link>
          </td>

          <!-- Priority -->
          <td>
            <app-dropdown @away="filter_priorities = ''" :disabled="isAssigned(p.agent_id)">
              <template slot="value">{{ !p.priority ? "--" : `${priority.find(pr => pr.id == p.priority).name}` }}</template>

              <app-dropdown-content>
                <div class="p-2">
                  <input type="text" class="form-control" placeholder="Enter a keyword..." v-model="filter_priorities" />
                </div>

                <app-dropdown-item v-for="fp in filtered_priority" :key="fp.id" @select="(p.priority = fp.id), updateFields(p.session, 'priority', p.priority)">
                  {{ fp.name }}
                </app-dropdown-item>
              </app-dropdown-content>
            </app-dropdown>
          </td>

          <!-- Groups -->
          <td>
            <app-dropdown @away="filter_groups = ''" :disabled="isAssigned(p.agent_id)">
              <template slot="value">{{ !p.group_id ? "--" : groups.find(g => g.id == p.group_id).name }}</template>

              <app-dropdown-content>
                <div class="p-2">
                  <input type="text" class="form-control" placeholder="Enter a keyword..." v-model="filter_groups" />
                </div>

                <app-dropdown-item v-for="fg in filtered_groups" :key="fg.id" @select="(p.group_id = fg.id), updateFields(p.session, 'group_id', p.group_id)">
                  {{ `${fg.name}` }}
                </app-dropdown-item>
              </app-dropdown-content>
            </app-dropdown>
          </td>

          <!-- Agent -->
          <td>
            <app-dropdown @away="filter_agents = ''" :disabled="isAssigned(p.agent_id) || isAllowed()">
              <template slot="value">{{ !p.agent_id ? "--" : `${users.find(u => u.id == p.agent_id).bio.first_name} ${users.find(u => u.id == p.agent_id).bio.last_name}` }}</template>

              <app-dropdown-content>
                <div class="p-2">
                  <input type="text" class="form-control" placeholder="Enter a keyword..." v-model="filter_agents" />
                </div>

                <app-dropdown-item v-for="u in filtered_agents" :key="u.id" @select="(p.agent_id = u.id), updateAgent(p)">
                  {{ `${u.bio.first_name} ${u.bio.last_name}` }}
                </app-dropdown-item>
              </app-dropdown-content>
            </app-dropdown>
          </td>

          <!-- Status -->
          <td>
            <app-dropdown @away="filter_statuses = ''" :disabled="isAssigned(p.agent_id)">
              <template slot="value">{{ `${status.find(s => s.id == p.status).name}` }}</template>

              <app-dropdown-content>
                <div class="p-2">
                  <input type="text" class="form-control" placeholder="Enter a keyword..." v-model="filter_statuses" />
                </div>

                <app-dropdown-item v-for="fs in filtered_statuses" :key="fs.id" @select="(p.status = fs.id), updateFields(p.session, 'status', p.status)">
                  {{ fs.name }}
                </app-dropdown-item>
              </app-dropdown-content>
            </app-dropdown>
          </td>

          <!-- Created At -->
          <td>{{ $dayjs("format", p.created_at, "MM/DD/YYYY h:mm A") }}</td>
        </tr>

        <tr v-if="!isLoading && !paginated.length && isReady">
          <td colspan="8">
            <div class="w-100 my-3 flex-center flex-column">No result found.</div>
          </td>
        </tr>
      </tbody>
    </datatable>

    <pagination :pagination="pagination" @prev="getDatatable(pagination.prevPageUrl)" @next="getDatatable(pagination.nextPageUrl)" />
  </div>
</template>

<script>
import { nanoid } from "nanoid";
import { mapState } from "vuex";
import { tickets } from "@Scripts/observable";
import { Search, Datatable, Pagination, Mixin } from "@SDT";

export default {
  props: ["isReady"],
  mixins: [Mixin],
  components: { Search, Datatable, Pagination },
  data() {
    let sortOrders = {};
    let types = ["string", "number", "date"];
    let columns = [
      { sortable: 0, hide: 0, type: types[0], width: "100%", name: "info", label: "Ticket Details" },
      { sortable: 1, hide: 0, type: types[0], width: "12.5%", name: "client", label: "Client" },
      { sortable: 1, hide: 0, type: types[0], width: "12.5%", name: "session", label: "Session" },
      { sortable: 1, hide: 0, type: types[0], width: "12.5%", name: "priority", label: "Priority" },
      { sortable: 1, hide: 0, type: types[1], width: "12.5%", name: "groups", label: "Groups" },
      { sortable: 1, hide: 0, type: types[0], width: "12.5%", name: "agent", label: "Agent" },
      { sortable: 1, hide: 0, type: types[1], width: "12.5%", name: "status", label: "Status" },
      { sortable: 1, hide: 0, type: types[2], width: "12.5%", name: "created_at", label: "Timestamp" }
    ];
    columns.forEach(column => {
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
      filter_statuses: ""
    };
  },
  computed: {
    ...mapState("auth", ["user", "users"]),
    ...mapState("groups", ["groups"]),
    ...mapState("slas", ["slas"]),

    sorted_slas() {
      return this.slas.sort(function(a, b) {
        return a.range - b.range;
      });
    },
    filtered_priority() {
      let search = this.filter_priorities;
      let filtered = this.priority.filter(p => {
        let name = p.name.toLowerCase();

        return name.includes(search.toLowerCase());
      });

      return filtered;
    },
    filtered_groups() {
      let search = this.filter_groups;
      let filtered = this.groups.filter(g => {
        let name = g.name.toLowerCase();

        return name.includes(search.toLowerCase());
      });

      return filtered;
    },
    filtered_agents() {
      let search = this.filter_agents;
      let filtered = this.users.filter(u => {
        let name = `${u.bio.first_name} ${u.bio.last_name}`.toLowerCase();

        return name.includes(search.toLowerCase());
      });

      return filtered;
    },
    filtered_statuses() {
      let search = this.filter_statuses;
      let filtered = this.status.filter(s => {
        let name = s.name.toLowerCase();

        return name.includes(search.toLowerCase());
      });

      return filtered;
    }
  },
  methods: {
    getDatatable(url = `/ticket/datatable`) {
      // this.isLoading = true;
      this.tableData.draw++;

      axios
        .get(url, { params: this.tableData })
        .then(response => {
          let data = response.data;
          if (this.tableData.draw == data.draw) {
            this.paginated = data.data.data;
            this.configPagination(data.data);
            // this.isLoading = false;
          }
        })
        .catch(errors => {
          console.log(errors);
        });
    },
    setupListeners() {
      const self = this;

      // pusher init
      const PUSHER = new Pusher(process.env.MIX_PUSHER_APP_KEY, {
        cluster: process.env.MIX_PUSHER_APP_CLUSTER,
        encrypted: true
      });

      // channel to subscribe
      const CH_SESSION = PUSHER.subscribe("session");
      const CH_MESSAGE = PUSHER.subscribe("message");

      CH_SESSION.bind(`session.created`, async data => {
        self.isUpdated = true;
      });

      CH_MESSAGE.bind(`message.from.client`, async data => {
        let { message, session } = data;

        // push the message to its session
        self.paginated = self.paginated.map(p => {
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
      if (field == "status") data = { status: val };
      if (field == "group_id") data = { group_id: val };
      if (field == "user_id") data = { user_id: val, type: "connected" };

      axios.put(`/session/${session}`, data);
    },
    updateAgent(s) {
      let agent = this.users.find(u => u.id == s.agent_id);

      axios.put(`/session/${s.session}`, {
        ...s,
        user_id: agent.id
      });

      axios.post(`/message`, {
        hash: nanoid(),
        sender: "session",
        message: `<p>Thank you for waiting. <br /> You are now connected to agent ${agent.bio.first_name} ${agent.bio.last_name}.</p>`,
        client_id: s.id,
        session: s.session
      });
    },
    getSla(p) {
      let date = new Date(p.created_at);
      let today = new Date();
      let days = today.getTime() - date.getTime();
      let difference = days / (1000 * 3600 * 24);
      let rounded = Math.round(difference);
      let choosen = null;

      // early return if the status is resolved or closed
      if ([3, 4].includes(p.status)) return "transparent";

      this.sorted_slas.every((s, $s) => {
        if (rounded <= s.range) {
          choosen = s.color;
          return false;
        } else {
          let last_sla = this.sorted_slas[this.sorted_slas.length - 1];

          if (rounded > last_sla.range) {
            choosen = last_sla.color;
            return false;
          }

          return true;
        }
      });

      return choosen;
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
    isAssigned(assigned_id) {
      if (
        this.user.id == assigned_id || // if assigned to the user
        ["Super", "Admin"].includes(this.user.role) // if you are an admin
      )
        return false;
      else return true;
    },
    isAllowed() {
      return !this.user.permissions.pluck("slug").includes("transfer_ticket");
    },
    getUnreadCount(p) {
      let messages = p.messages;

      return messages.filter(m => m.sender == "client" && m.is_read == false).length;
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
  }
};
</script>
