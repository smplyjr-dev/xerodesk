<template>
  <div class="client-datatable">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
      <!-- <div class="control d-flex align-items-center mb-4">
        Show
        <div class="select mx-2">
          <select class="custom-select custom-select-sm" v-model="length" @change="resetPagination()">
            <option>10</option>
            <option>20</option>
            <option>30</option>
            <option>50</option>
            <option>100</option>
          </select>
        </div>
        entries
      </div> -->

      &nbsp;
      <div class="search">
        <div class="d-flex align-items-center">
          <label class="mb-0 mr-2" for="search">Search:</label>
          <input class="form-control form-control-sm" type="text" v-model="search" @input="resetPagination()" />
        </div>
      </div>
    </div>

    <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
      <tbody class="text-sm">
        <tr class="text-center" v-if="isLoading">
          <td colspan="8">
            <div class="spinner-border text-lg my-4" style="height: 5rem; width: 5rem"></div>
          </td>
        </tr>

        <template v-else>
          <tr class="text-sm" v-for="p in selectedUsers" :key="p.id">
            <td>
              <div class="d-flex">
                <span class="w-25 font-weight-bold text-right">Name:</span>
                <span class="w-75 ml-1 align-self-end">{{ p.name }}</span>
              </div>
              <div class="d-flex">
                <span class="w-25 font-weight-bold text-right">Action:</span>
                <div class="w-75 ml-1 align-self-end">
                  <div class="d-flex align-items-center">
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input" :id="`switch${p.id}`" @click="setUser(p.id)" :checked="pluckedUsers.includes(p.id)" :disabled="isProcessing.includes(p.id)" />
                      <label class="custom-control-label" :for="`switch${p.id}`">&nbsp;</label>
                    </div>

                    <i class="fa fa-fw fa-circle-notch fa-spin ml-2" v-show="isProcessing.includes(p.id)"></i>
                  </div>
                </div>
              </div>
            </td>

            <td>{{ p.name }}</td>
            <td>
              <div class="d-flex align-items-center">
                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" :id="`switch${p.id}`" @click="setUser(p.id)" :checked="pluckedUsers.includes(p.id)" :disabled="isProcessing.includes(p.id)" />
                  <label class="custom-control-label" :for="`switch${p.id}`">&nbsp;</label>
                </div>

                <i class="fa fa-fw fa-circle-notch fa-spin ml-2" v-show="isProcessing.includes(p.id)"></i>
              </div>
            </td>
          </tr>

          <tr class="text-sm" v-for="p in unselectedUsers" :key="p.id">
            <td>
              <div class="d-flex">
                <span class="w-25 font-weight-bold text-right">Name:</span>
                <span class="w-75 ml-1 align-self-end">{{ p.name }}</span>
              </div>
              <div class="d-flex">
                <span class="w-25 font-weight-bold text-right">Action:</span>
                <div class="w-75 ml-1 align-self-end">
                  <div class="d-flex align-items-center">
                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input" :id="`switch${p.id}`" @click="setUser(p.id)" :checked="pluckedUsers.includes(p.id)" :disabled="isProcessing.includes(p.id)" />
                      <label class="custom-control-label" :for="`switch${p.id}`">&nbsp;</label>
                    </div>

                    <i class="fa fa-fw fa-circle-notch fa-spin ml-2" v-show="isProcessing.includes(p.id)"></i>
                  </div>
                </div>
              </div>
            </td>

            <td>{{ p.name }}</td>
            <td>
              <div class="d-flex align-items-center">
                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" :id="`switch${p.id}`" @click="setUser(p.id)" :checked="pluckedUsers.includes(p.id)" :disabled="isProcessing.includes(p.id)" />
                  <label class="custom-control-label" :for="`switch${p.id}`">&nbsp;</label>
                </div>

                <i class="fa fa-fw fa-circle-notch fa-spin ml-2" v-show="isProcessing.includes(p.id)"></i>
              </div>
            </td>
          </tr>
        </template>

        <tr v-if="!isLoading && !paginated.length">
          <td colspan="6">
            <div class="w-100 my-3 flex-center flex-column">No result found.</div>
          </td>
        </tr>
      </tbody>
    </datatable>

    <pagination :pagination="pagination" :client="true" :filtered="filteredUsers" @prev="--pagination.currentPage" @next="++pagination.currentPage"></pagination>
  </div>
</template>

<script>
import Datatable from "@Components/datatable/server/Datatable.vue";
import Pagination from "@Components/datatable/server/Pagination.vue";

export default {
  props: ["group"],
  name: "TableGroupUsers",
  components: { Datatable, Pagination },
  data() {
    let sortOrders = {};
    let types = ["string", "number", "date"];
    let columns = [
      { sortable: 0, hide: 0, type: types[0], width: "100%", name: "info", label: "Group Agents" },
      { sortable: 1, hide: 0, type: types[0], width: "0%", name: "name", label: "Name" },
      { sortable: 0, hide: 0, type: types[0], width: "0%", name: "action", label: "Action" }
    ];
    columns.forEach((column) => {
      sortOrders[column.name] = -1;
    });
    return {
      users: [],
      columns: columns,
      sortKey: "",
      sortOrders: sortOrders,
      length: 5,
      search: "",
      tableData: {
        for_user: true
      },
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
      isProcessing: []
    };
  },
  computed: {
    filteredUsers() {
      let users = this.users;
      if (this.search) {
        users = users.filter((row) => {
          return Object.keys(row).some((key) => {
            return String(row[key]).toLowerCase().indexOf(this.search.toLowerCase()) > -1;
          });
        });
      }

      let sortKey = this.sortKey;
      let order = this.sortOrders[sortKey] || 1;
      if (sortKey) {
        users = users.slice().sort((a, b) => {
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
      return users;
    },
    paginated() {
      return this.paginate(this.filteredUsers, this.length, this.pagination.currentPage);
    },
    pluckedUsers() {
      return this.group.users.pluckArray("pivot").pluckArray("user_id");
    },
    selectedUsers() {
      return this.filteredUsers.filter((p) => this.pluckedUsers.includes(p.id));
    },
    unselectedUsers() {
      return this.paginated.filter((p) => !this.pluckedUsers.includes(p.id));
    }
  },
  methods: {
    getUsers(shouldRefresh = false) {
      this.isLoading = shouldRefresh ?? false;

      axios
        .get(`/portal/group/datatable`, { params: this.tableData })
        .then((response) => {
          this.users = response.data;
          this.pagination.total = this.users.length;
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

    profilePicture(user) {
      if (user.meta.profile_picture == "generic-profile.png") {
        return `${this.$APP_URL}/images/${user.meta.profile_picture}`;
      } else {
        return `${this.$APP_URL}/storage/uploads/users/${user.id}/${user.meta.profile_picture}`;
      }
    },
    async setUser(id) {
      this.isProcessing.push(id);

      let method = this.pluckedUsers.includes(id) ? "delete" : "create";

      if (method == "delete") {
        await axios
          .delete(`/portal/group-user/${id}`, {
            data: {
              group_id: this.group.id,
              user_id: id
            }
          })
          .takeAtLeast(500);
      } else {
        await axios
          .post(`/portal/group-user`, {
            group_id: this.group.id,
            user_id: id
          })
          .takeAtLeast(500);
      }

      // set the new group object
      this.$emit("refresh");

      // refresh the datatable without showing loader
      this.getUsers();

      this.isProcessing = this.isProcessing.filter((i) => i != id);
    }
  },
  watch: {
    group: {
      deep: true,
      handler: function () {
        this.getUsers();
      }
    }
  }
};
</script>
