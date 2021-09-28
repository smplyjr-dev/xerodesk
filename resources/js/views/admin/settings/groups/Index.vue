<template>
  <div class="container-fluid">
    <SettingMeta />

    <div class="card card-1">
      <div class="card-body">
        <div class="page-title">
          <div>
            <h5 class="mb-2">Groups</h5>
            <p class="text-secondary">You can organize your users by attaching them to a group in here.</p>
          </div>

          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#manage-group" @click="setMethod('create')">Create Group</button>
        </div>

        <div class="client-datatable">
          <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div class="control d-flex align-items-center mb-4">
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
            </div>

            <div class="search mb-4">
              <div class="d-flex align-items-center">
                <label class="mb-0 mr-2" for="search">Search:</label>
                <input class="form-control form-control-sm" type="text" v-model="search" @input="resetPagination()" />
              </div>
            </div>
          </div>

          <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
            <tbody class="text-sm">
              <tr class="text-center" v-if="isLoading">
                <td colspan="6">
                  <div class="spinner-border text-lg my-4" style="height: 5rem; width: 5rem;"></div>
                </td>
              </tr>

              <tr class="text-sm" v-else v-for="p in paginated" :key="p.id">
                <td>
                  <div class="d-flex">
                    <span class="w-50 font-weight-bold text-right">Name:</span>
                    <span class="w-50 ml-1 align-self-end">
                      <p class="mb-0 font-weight-semi">{{ p.name }}</p>
                      <p class="mb-0 text-secondary text-sm">{{ p.description }}</p>
                    </span>
                  </div>
                  <div class="d-flex">
                    <span class="w-50 font-weight-bold text-right">Users:</span>
                    <span class="w-50 ml-1 align-self-end">
                      <div class="social-media">
                        <router-link :to="`/settings/users/${u.pivot.user_id}/edit`" v-for="(u, $u) in p.users" :key="$u">
                          <img :src="profilePicture(u)" :alt="u.profile_picture" />
                        </router-link>

                        <a href="javascript:void(0)" @click="selectUsers(p)">
                          <i class="fa fa-fw fa-plus"></i>
                        </a>
                      </div>
                    </span>
                  </div>
                  <div class="text-center">
                    <button type="button" class="btn btn-secondary btn-sm my-1" data-toggle="modal" data-target="#manage-group" @click="setMethod('update', p)">Edit</button>
                    <button type="button" class="btn btn-danger btn-sm my-1" data-toggle="modal" data-target="#manage-group" @click="setMethod('delete', p)">Delete</button>
                  </div>
                </td>

                <td>
                  <p class="mb-0 font-weight-semi">{{ p.name }}</p>
                  <p class="mb-0 text-secondary text-sm">{{ p.description }}</p>
                </td>
                <td>
                  <div class="social-media">
                    <router-link :to="`/settings/users/${u.pivot.user_id}/edit`" v-for="(u, $u) in p.users" :key="$u">
                      <img :src="profilePicture(u)" :alt="u.profile_picture" />
                    </router-link>

                    <a href="javascript:void(0)" @click="selectUsers(p)">
                      <i class="fa fa-fw fa-plus"></i>
                    </a>
                  </div>
                </td>
                <td>
                  <button type="button" class="btn btn-secondary btn-sm my-1" data-toggle="modal" data-target="#manage-group" @click="setMethod('update', p)">Edit</button>
                  <button type="button" class="btn btn-danger btn-sm my-1" data-toggle="modal" data-target="#manage-group" @click="setMethod('delete', p)">Delete</button>
                </td>
              </tr>

              <tr v-if="!isLoading && !paginated.length">
                <td colspan="6">
                  <div class="w-100 my-3 flex-center flex-column">No result found.</div>
                </td>
              </tr>
            </tbody>
          </datatable>

          <pagination :pagination="pagination" :client="true" :filtered="filteredGroups" @prev="--pagination.currentPage" @next="++pagination.currentPage"></pagination>
        </div>
      </div>
    </div>

    <div id="users-modal" class="modal fade">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add User</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <div class="modal-body">
            <TableGroupUsers :group="group" @refresh="handleRefresh" />
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <div id="manage-group" class="modal fade">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" v-if="groupMethod == 'delete'">Are you sure?</h5>
            <h5 class="modal-title" v-if="groupMethod != 'delete'">{{ groupMethod == "create" ? "Create" : "Update" }} Group</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <form @submit.prevent="submitGroup()">
            <div class="modal-body">
              <template v-if="groupMethod == 'delete'">
                <p class="mb-0">
                  You are about to delete the group named <span class="font-weight-semi">{{ groupDetails.name }}</span
                  >.
                </p>
              </template>
              <template v-else>
                <div class="form-group" v-show="!$isEmpty(groupError)">
                  <form-alert variant="warning">
                    <p v-for="error in groupError" :key="error" v-html="error"></p>
                  </form-alert>
                </div>
                <div class="form-group">
                  <label for="name">Name <span class="text-danger">*</span></label>
                  <input id="name" type="text" class="form-control" v-model="groupDetails.name" />
                </div>
                <div class="form-group">
                  <label for="description">Description</label>
                  <textarea id="description" class="form-control" v-model="groupDetails.description"></textarea>
                </div>
              </template>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>

              <template v-if="groupMethod == 'delete'">
                <button type="submit" class="btn btn-primary" :disabled="isGroupLoading">
                  <div v-if="isGroupLoading" class="spinner-border spinner-border-sm" role="status"></div>
                  <span v-else>Delete</span>
                </button>
              </template>
              <template v-else>
                <button type="submit" class="btn btn-primary" :disabled="isGroupLoading">
                  <div v-if="isGroupLoading" class="spinner-border spinner-border-sm" role="status"></div>
                  <span v-else>{{ groupMethod == "create" ? "Create" : "Update" }}</span>
                </button>
              </template>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import Datatable from "@Components/datatable/client/Datatable.vue";
import Pagination from "@Components/datatable/client/Pagination.vue";
import SettingMeta from "@Components/admin/settings/SettingMeta.vue";
import TableGroupUsers from "@Components/admin/settings/TableGroupUsers.vue";

export default {
  layout: "Admin",
  name: "SettingGroups",
  metaInfo: () => ({ title: "Setting / Groups" }),
  middleware: ["auth", "permission:view_groups"],
  components: { Datatable, Pagination, SettingMeta, TableGroupUsers },
  data() {
    let sortOrders = {};
    let types = ["string", "number", "date"];
    let columns = [
      { sortable: 0, hide: 0, type: types[0], width: "100%", name: "info", label: "Group Details" },
      { sortable: 1, hide: 0, type: types[0], width: "0%", name: "name", label: "Name" },
      { sortable: 0, hide: 0, type: types[0], width: "0%", name: "users", label: "Agents" },
      { sortable: 0, hide: 0, type: types[0], width: "0%", name: "action", label: "Action" }
    ];
    columns.forEach(column => {
      sortOrders[column.name] = -1;
    });
    return {
      group: null,
      groups: [],
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
      isGroupLoading: false,
      groupError: [],
      groupMethod: "",
      groupDetails: {
        id: "",
        name: "",
        description: ""
      }
    };
  },
  computed: {
    ...mapGetters("auth", ["permissions"]),

    filteredGroups() {
      let groups = this.groups;
      if (this.search) {
        groups = groups.filter(row => {
          return Object.keys(row).some(key => {
            return (
              String(row[key])
                .toLowerCase()
                .indexOf(this.search.toLowerCase()) > -1
            );
          });
        });
      }

      let sortKey = this.sortKey;
      let order = this.sortOrders[sortKey] || 1;
      if (sortKey) {
        groups = groups.slice().sort((a, b) => {
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
      return groups;
    },
    paginated() {
      return this.paginate(this.filteredGroups, this.length, this.pagination.currentPage);
    }
  },
  methods: {
    getGroups(shouldRefresh = true, url = "/groups/datatable") {
      this.isLoading = shouldRefresh;

      axios
        .get(url, { params: this.tableData })
        .then(response => {
          this.groups = response.data;
          this.pagination.total = this.groups.length;
          this.isLoading = false;
        })
        .catch(errors => {
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
      if (user.profile_picture == "generic-profile.png") {
        return `${this.$APP_URL}/images/${user.profile_picture}`;
      } else {
        return `${this.$APP_URL}/storage/uploads/users/${user.pivot.user_id}/${user.profile_picture}`;
      }
    },
    setMethod(method, group) {
      this.groupError = [];

      if (method == "create") {
        this.groupMethod = "create";
        this.groupDetails.name = "";
        this.groupDetails.description = "";
      }

      if (method == "update") {
        this.groupMethod = "update";
        this.groupDetails.id = group.id;
        this.groupDetails.name = group.name;
        this.groupDetails.description = group.description;
      }

      if (method == "delete") {
        this.groupMethod = "delete";
        this.groupDetails.id = group.id;
        this.groupDetails.name = group.name;
        this.groupDetails.description = group.description;
      }
    },
    async submitGroup() {
      this.isGroupLoading = true;
      this.groupError = [];

      try {
        let response = "";

        ["create_group", "edit_group", "delete_group"].forEach(allowed_permission => {
          if (!this.permissions.includes(allowed_permission)) {
            // display a notification
            this.$store.dispatch("notifications/addNotification", {
              variant: "bg-danger",
              icon: "fa-times",
              title: "Invalid.",
              body: `Sorry! But you don't have the permission to ${this.groupMethod} a group.`
            });

            this.isGroupLoading = false;

            return false;
          }
        });

        if (this.groupMethod == "create") {
          let { data } = await axios.post(`/groups`, {
            name: this.groupDetails.name,
            description: this.groupDetails.description
          });

          response = data;
        }

        if (this.groupMethod == "update") {
          let { data } = await axios.put(`/groups/${this.groupDetails.id}`, {
            name: this.groupDetails.name,
            description: this.groupDetails.description
          });

          response = data;
        }

        if (this.groupMethod == "delete") {
          let { data } = await axios.delete(`/groups/${this.groupDetails.id}`);

          response = data;
        }

        // close modal
        $("#manage-group").modal("hide");

        // refresh datatable
        this.getGroups(false);

        // refresh groups from store
        this.$store.dispatch("group/fetchGroups");

        // show notification
        this.$store.dispatch("notifications/addNotification", {
          variant: `bg-${response.status}`,
          icon: response.status == "success" ? "fa-check" : "fa-times",
          title: response.status == "success" ? "Success." : "Invalid.",
          body: response.message
        });

        // clear inputs
        this.groupDetails.id = "";
        this.groupDetails.name = "";
      } catch (error) {
        const errorObj = error.response.data.errors;

        for (const key in errorObj) {
          if (errorObj.hasOwnProperty(key)) {
            const error = errorObj[key];

            error.forEach(message => {
              this.groupError.push(message);
            });
          }
        }
      }

      this.isGroupLoading = false;
    },
    selectUsers(group) {
      ["select_group_users"].forEach(allowed_permission => {
        if (!this.permissions.includes(allowed_permission)) {
          // display a notification
          this.$store.dispatch("notifications/addNotification", {
            variant: "bg-danger",
            icon: "fa-times",
            title: "Invalid.",
            body: "Sorry! But you don't have the permission to select a user."
          });

          return false;
        }
      });

      this.group = group;
      $("#users-modal").modal("show");
    },
    async handleRefresh() {
      this.getGroups(false);
      await this.$delay(300);
      this.group = this.groups.find(g => g.id == this.group.id);
    }
  },
  created() {
    this.getGroups();
  }
};
</script>

<style lang="scss" scoped>
@import "@Styles/bootstrap/_variables.scss";

.social-media {
  display: flex;
  align-items: center;

  a {
    text-decoration: none;

    .fa,
    img {
      background: $secondary;
      border: 3px solid #ccc;
      border-radius: 100%;
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 40px;
      width: 40px;
      object-fit: cover;
    }

    &:not(:first-child) {
      margin-left: -15px;
    }
  }
}
</style>
