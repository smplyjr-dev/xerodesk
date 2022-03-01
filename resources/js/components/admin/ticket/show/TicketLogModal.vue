<template>
  <modal mId="ticket-log-modal" mClass="modal-xl">
    <modal-header>
      <h5 class="modal-title" id="ticket-log-modal-label">Ticket Activity Log</h5>
    </modal-header>
    <modal-body>
      <div class="client-datatable">
        <div class="flex-center-between flex-wrap">
          <div class="control d-flex align-items-center">
            Show
            <div class="select mx-2">
              <select class="custom-select custom-select-sm" v-model="length" @change="resetPagination()">
                <option>10</option>
                <option>20</option>
                <option>30</option>
              </select>
            </div>
            entries
          </div>
        </div>

        <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
          <tbody class="text-sm">
            <tr class="text-center" v-if="isLoading">
              <td colspan="6">
                <div class="spinner-border text-lg my-4" style="height: 5rem; width: 5rem"></div>
              </td>
            </tr>

            <tr class="text-sm" v-else v-for="p in paginated" :key="p.id">
              <!-- Mobile View -->
              <td>Mobile View</td>

              <td>{{ p.description }}</td>
              <td v-html="getDescription(p)"></td>
              <td>{{ $dayjs("format", p.created_at, "MM/DD/YYYY") }} {{ $dayjs("format", p.created_at, "hh:mm A") }}</td>
              <td>
                <popup :arrow="true" offset="50px,5px" placement="left" toggle="hide" trigger="click">
                  <div class="text-left text-break p-2" style="max-width: 500px" slot="content" v-html="getDetails(p)"></div>
                  <i class="fa fa-info-circle text-info cursor-pointer" slot="reference"></i>
                </popup>
              </td>
            </tr>

            <tr v-if="!isLoading && !paginated.length">
              <td colspan="6">
                <div class="w-100 my-3 flex-center flex-column">No result found.</div>
              </td>
            </tr>
          </tbody>
        </datatable>

        <pagination :pagination="pagination" :client="true" :filtered="filteredRoles" @prev="--pagination.currentPage" @next="++pagination.currentPage"></pagination>
      </div>
    </modal-body>
  </modal>
</template>

<script>
import { mapState } from "vuex";
import Datatable from "@Components/datatable/client/Datatable.vue";
import Pagination from "@Components/datatable/client/Pagination.vue";
import { tickets } from "@Scripts/observable";

export default {
  name: "TicketLogModal",
  components: { Datatable, Pagination },
  data() {
    let sortOrders = {};
    let types = ["string", "number", "date"];
    let columns = [
      { sortable: 0, hide: 0, type: types[0], width: "100%", name: "info", label: "Log Details" },
      { sortable: 0, hide: 0, type: types[0], width: "10%", name: "log_name", label: "Transaction" },
      { sortable: 0, hide: 0, type: types[0], width: "40%", name: "description", label: "Description" },
      { sortable: 0, hide: 0, type: types[2], width: "20%", name: "created_at", label: "Timestamp" },
      { sortable: 0, hide: 0, type: types[0], width: "10%", name: "details", label: "Details" }
    ];
    columns.forEach((column) => {
      sortOrders[column.name] = -1;
    });
    return {
      roles: [],
      columns: columns,
      sortKey: "",
      sortOrders: sortOrders,
      length: 10,
      search: "",
      tableData: {},
      pagination: {
        currentPage: 1,
        total: "",
        nextPage: "",
        prevPage: "",
        from: "",
        to: ""
      },

      // custom data
      isLoading: false,
      priorities: tickets.priority,
      statuses: tickets.status
    };
  },
  computed: {
    ...mapState("auth", ["users"]),
    ...mapState("clients", ["clients"]),
    ...mapState("sessions", ["session"]),

    filteredRoles() {
      let roles = this.roles;

      if (this.search) {
        roles = roles.filter((row) => {
          return Object.keys(row).some((key) => {
            return String(row[key]).toLowerCase().indexOf(this.search.toLowerCase()) > -1;
          });
        });
      }

      let sortKey = this.sortKey;
      let order = this.sortOrders[sortKey] || 1;
      if (sortKey) {
        roles = roles.slice().sort((a, b) => {
          let index = this.getIndex(this.columns, "name", sortKey);
          a = String(a[sortKey]).toLowerCase();
          b = String(b[sortKey]).toLowerCase();

          if (this.columns[index].type && this.columns[index].type === "date") {
            return (a === b ? 0 : new Date(a).getTime() > new Date(b).getTime() ? 1 : -1) * order;
          } else if (this.columns[index].type && this.columns[index].type === "number") {
            return (+a === +b ? 0 : +a > +b ? 1 : -1) * order; // 1 = asc, -1 = desc
          } else {
            return (a === b ? 0 : a > b ? 1 : -1) * order;
          }
        });
      }
      return roles;
    },
    paginated() {
      return this.paginate(this.filteredRoles, this.length, this.pagination.currentPage);
    }
  },
  methods: {
    getDatatable(url = `/portal/session/${this.session.session}/logs`) {
      this.isLoading = true;

      axios
        .get(url, { params: this.tableData })
        .then((response) => {
          this.roles = response.data;
          this.pagination.total = this.roles.length;
          this.isLoading = false;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },
    paginate(array, length, pageNumber) {
      this.pagination.from = array.length ? (pageNumber - 1) * length + 1 : " ";
      this.pagination.to = pageNumber * length > array.length ? array.length : pageNumber * length;
      this.pagination.prevPage = pageNumber > 1 ? pageNumber : "";
      this.pagination.nextPage = array.length > this.pagination.to ? pageNumber + 1 : "";
      return array.slice((pageNumber - 1) * length, pageNumber * length);
    },
    resetPagination() {
      this.pagination.currentPage = 1;
      this.pagination.prevPage = "";
      this.pagination.nextPage = "";
    },
    sortBy(key) {
      this.resetPagination();
      this.sortKey = key;
      this.sortOrders[key] = this.sortOrders[key] * -1;
    },
    getIndex(array, key, value) {
      // return array.findIndex(i => i[key] == value);

      let index = null;
      array.forEach((el, i) => {
        if (el[key] == value) {
          index = i;
        }
      });
      return index;
    },

    getTicketAttr(type, id) {
      if (type == "priority") return this.priorities.find((p) => p.id == id).name;
      if (type == "status") return this.statuses.find((s) => s.id == id).name;
    },
    getDescription(p) {
      let user = this.getUser(p.causer_id, p.causer_type);
      let message = ``;

      if (p.description == "ASSIGN") message += `<strong>${user}</strong> has assigned this ticket to <strong>${this.getUser(p.properties.ending.user_id, "App\\Models\\User\\User")}</strong>.`;
      if (p.description == "CREATE") message += `<strong>${user}</strong> has open this ticket.`;
      if (p.description == "GRAB") message += `<strong>${user}</strong> has grabbed and locked this ticket.`;
      if (p.description == "LOCK") message += `<strong>${user}</strong> has accepted and locked this ticket.`;
      if (p.description == "STATUS") message += `<strong>${user}</strong> has update the status of the ticket.`;
      if (p.description == "TRANSCRIPT") message += `<strong>${user}</strong> has sent a copy of transcript to the client.`;
      if (p.description == "TRANSFER") message += `<strong>${user}</strong> has transferred this ticket to <strong>${this.getUser(p.properties.ending.user_id, "App\\Models\\User\\User")}</strong>.`;
      if (p.description == "UPDATE") message += `<strong>${user}</strong> updates this ticket.`;

      return message;
    },
    getDetails(p) {
      let message = ``;
      let starting = p.properties.starting;
      let ending = p.properties.ending;

      if (p.description == "UPDATE") {
        let details = [];

        for (const key in starting) {
          if (Object.hasOwnProperty.call(starting, key)) {
            let value = starting[key];
            let trans = value == null ? "set" : "updated";

            details.push({ key, value, trans, starting, ending });
          }
        }

        message += `<div class="mb-2"><strong>Affected Attributes:</strong></div>`;

        details.forEach((d) => {
          if (d.trans == "set") {
            if (d.key == "title") message += `- <strong>Title</strong> has been set to <strong>${d.ending.title}</strong> <br />`;
            if (d.key == "priority") message += `- <strong>Priority</strong> has been set to <strong>${this.getTicketAttr("priority", d.ending.priority)}</strong> <br />`;
            if (d.key == "category") message += `- <strong>Category</strong> has been set to <strong>${d.ending.category}</strong> <br />`;
            if (d.key == "resolution_code") message += `- <strong>Resolution code</strong> has been set to <strong>${d.ending.resolution_code}</strong> <br />`;
            if (d.key == "status") message += `- <strong>Status</strong> has been set to <strong>${this.getTicketAttr("status", d.ending.status)}</strong> <br />`;
            if (d.key == "solution") message += `- <strong>Solution</strong> has been set to <strong>${d.ending.solution}</strong> <br />`;
          }

          if (d.trans == "updated") {
            if (d.key == "title") message += `- <strong>Title</strong> has been updated from <strong>${d.starting.title}</strong> to <strong>${d.ending.title}</strong> <br />`;
            if (d.key == "priority") message += `- <strong>Priority</strong> has been updated from <strong>${this.getTicketAttr("priority", d.starting.priority)}</strong> to <strong>${this.getTicketAttr("priority", d.ending.priority)}</strong> <br />`;
            if (d.key == "category") message += `- <strong>Category</strong> has been updated from <strong>${d.starting.category}</strong> to <strong>${d.ending.category}</strong> <br />`;
            if (d.key == "resolution_code") message += `- <strong>Resolution</strong> <strong>code</strong> has been updated from <strong>${d.starting.resolution_code}</strong> to <strong>${d.ending.resolution_code}</strong> <br />`;
            if (d.key == "status") message += `- <strong>Status</strong> has been updated from <strong>${this.getTicketAttr("status", d.starting.status)}</strong> to <strong>${this.getTicketAttr("status", d.ending.status)}</strong> <br />`;
            if (d.key == "solution") message += `- <strong>Solution</strong> has been updated from <strong>${d.starting.solution}</strong> to <strong>${d.ending.solution}</strong> <br />`;
          }
        });
      } else {
        message += `No further details provided.`;
      }

      return message;
    },
    getUser(id, type) {
      type = type == "App\\Models\\User\\User" ? "user" : "client";

      if (type == "client") return this.clients.find((c) => c.id == id).name;
      if (type == "user") {
        let user = this.users.find((u) => u.id == id);
        return `${user.bio.first_name} ${user.bio.last_name}`;
      }
    }
  },
  async created() {
    if (!this.clients.length) await this.$store.dispatch("clients/fetchClients");
    this.getDatatable();

    const self = this;
    $("#ticket-log-modal").on("hidden.bs.modal", function () {
      self.$emit("onHidden", false);
    });
  }
};
</script>
