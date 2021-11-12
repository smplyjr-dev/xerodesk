<template>
  <div class="container-fluid">
    <SettingMeta />

    <div class="page-title">
      <div>
        <h5 class="mb-2">Roles</h5>
        <p class="text-secondary">Want to assign some role to a user? You can do that in here.</p>
      </div>

      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#manage-role" @click="setMethod('create')">Create Role</button>
    </div>

    <div class="client-datatable">
      <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div class="control d-flex align-items-center">
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
            <td colspan="6">
              <div class="spinner-border text-lg my-4" style="height: 5rem; width: 5rem;"></div>
            </td>
          </tr>

          <tr class="text-sm" v-else v-for="p in paginated" :key="p.id">
            <td>
              <div class="d-flex">
                <span class="w-50 font-weight-bold text-right">Name:</span>
                <span class="w-50 ml-1 align-self-end">{{ p.name }}</span>
              </div>
              <div class="text-center">
                <p class="font-weight-bold mb-0">Permissions:</p>
                <span class="badge badge-info mb-1 mr-1" v-for="p in p.permissions" :key="p.slug">{{ p.name }}</span>
              </div>
              <div class="text-center">
                <button type="button" class="btn btn-secondary btn-sm my-1" data-toggle="modal" data-target="#manage-role" @click="setMethod('update', p)">Edit</button>
                <button type="button" class="btn btn-danger btn-sm my-1" data-toggle="modal" data-target="#manage-role" @click="setMethod('delete', p)">Delete</button>
              </div>
            </td>

            <td>{{ p.name }}</td>
            <td>
              <span class="badge badge-info mb-1 mr-1" v-for="p in p.permissions" :key="p.slug">{{ p.slug }}</span>
            </td>
            <td>
              <button type="button" class="btn btn-secondary btn-sm my-1" data-toggle="modal" data-target="#manage-role" @click="setMethod('update', p)">Edit</button>
              <button type="button" class="btn btn-danger btn-sm my-1" data-toggle="modal" data-target="#manage-role" @click="setMethod('delete', p)">Delete</button>
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

    <div id="manage-role" class="modal fade">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" v-if="roleMethod == 'delete'">Are you sure?</h5>
            <h5 class="modal-title" v-if="roleMethod != 'delete'">{{ roleMethod == "create" ? "Create" : "Update" }} Role</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <form @submit.prevent="submitRole()">
            <div class="modal-body">
              <template v-if="roleMethod == 'delete'">
                <p class="mb-0">
                  You are about to delete the role <span class="font-weight-semi">{{ roleDetails.name }}</span
                  >.
                </p>
              </template>
              <template v-else>
                <div class="form-group" v-show="!$isEmpty(roleError)">
                  <form-alert variant="warning">
                    <p v-for="error in roleError" :key="error" v-html="error"></p>
                  </form-alert>
                </div>
                <div class="form-group">
                  <label for="name">Name <span class="text-danger">*</span></label>
                  <input id="name" type="text" class="form-control" v-model="roleDetails.name" />
                </div>
                <div class="form-group">
                  <label>Permissions <span class="text-danger">*</span></label>

                  <div class="row my-2" v-for="(p, pK) in permissions" :key="pK">
                    <div class="col-12">
                      <div class="permission-title">
                        <span>{{ p.name }}</span>
                      </div>
                    </div>

                    <div class="col-6" v-for="(c, cK) in p.childs" :key="cK">
                      <div class="custom-control custom-checkbox my-1 mr-sm-2">
                        <input type="checkbox" class="custom-control-input" :id="c.slug" :value="{ name: c.name, slug: c.slug }" v-model="roleDetails.permissions" />
                        <label class="custom-control-label" :for="c.slug">{{ c.name }}</label>
                      </div>
                    </div>
                  </div>
                </div>
              </template>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>

              <template v-if="roleMethod == 'delete'">
                <button type="submit" class="btn btn-primary" :disabled="isRoleLoading">
                  <div v-if="isRoleLoading" class="spinner-border spinner-border-sm" role="status"></div>
                  <span v-else>Delete</span>
                </button>
              </template>
              <template v-else>
                <button type="submit" class="btn btn-primary" :disabled="isRoleLoading">
                  <div v-if="isRoleLoading" class="spinner-border spinner-border-sm" role="status"></div>
                  <span v-else>{{ roleMethod == "create" ? "Create" : "Update" }}</span>
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
import Datatable from "@Components/datatable/client/Datatable.vue";
import Pagination from "@Components/datatable/client/Pagination.vue";
import Permissions from "@Public/docs/permissions.json";
import SettingMeta from "@Components/admin/settings/SettingMeta.vue";

export default {
  layout: "Admin",
  name: "SettingRoles",
  metaInfo: () => ({ title: "Setting / Roles" }),
  middleware: ["auth", "permission:view_roles"],
  components: { Datatable, Pagination, SettingMeta },
  data() {
    let sortOrders = {};
    let types = ["string", "number", "date"];
    let columns = [
      { sortable: 0, hide: 0, type: types[0], width: "100%", name: "info", label: "Role Details" },
      { sortable: 1, hide: 0, type: types[0], width: "15%", name: "name", label: "Name" },
      { sortable: 0, hide: 0, type: types[0], width: "70%", name: "permissions", label: "Permissions" },
      { sortable: 0, hide: 0, type: types[0], width: "15%", name: "action", label: "Action" }
    ];
    columns.forEach(column => {
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
      isRoleLoading: false,
      roleError: [],
      roleMethod: "",
      roleDetails: {
        id: "",
        name: "",
        permissions: []
      },
      permissions: Permissions
    };
  },
  computed: {
    filteredRoles() {
      let roles = this.roles;
      if (this.search) {
        roles = roles.filter(row => {
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
    getRoles(shouldRefresh = true, url = `/portal/role/datatable`) {
      this.isLoading = shouldRefresh;

      axios
        .get(url, { params: this.tableData })
        .then(response => {
          this.roles = response.data;
          this.pagination.total = this.roles.length;
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

    setMethod(method, role) {
      this.roleError = [];

      if (method == "create") {
        this.roleMethod = "create";
        this.roleDetails.name = "";
      }

      if (method == "update") {
        this.roleMethod = "update";
        this.roleDetails.id = role.id;
        this.roleDetails.name = role.name;
        this.roleDetails.permissions = [...role.permissions.map(p => ({ name: p.name, slug: p.slug }))];
      }

      if (method == "delete") {
        this.roleMethod = "delete";
        this.roleDetails.id = role.id;
        this.roleDetails.name = role.name;
      }
    },
    async submitRole() {
      this.isRoleLoading = true;
      this.roleError = [];

      try {
        let response = "";

        if (this.roleMethod == "create") {
          let { data } = await axios.post(`/portal/role`, {
            name: this.roleDetails.name,
            permissions: this.roleDetails.permissions
          });

          response = data;
        }

        if (this.roleMethod == "update") {
          let { data } = await axios.put(`/portal/role/${this.roleDetails.id}`, {
            name: this.roleDetails.name,
            permissions: this.roleDetails.permissions
          });

          response = data;
        }

        if (this.roleMethod == "delete") {
          let { data } = await axios.delete(`/portal/role/${this.roleDetails.id}`);

          response = data;
        }

        // close modal
        $("#manage-role").modal("hide");

        // refresh datatable
        this.getRoles(false);

        // refresh roles from store
        this.$store.dispatch("roles/fetchRoles");

        // show notification
        this.$store.dispatch("notifications/addNotification", {
          variant: `bg-${response.status}`,
          icon: "fa-check",
          title: response.status == "success" ? "Success." : "Invalid.",
          body: response.message
        });

        // clear inputs
        this.roleDetails.id = "";
        this.roleDetails.name = "";
        this.roleDetails.permissions = [];
      } catch (error) {
        const errorObj = error.response.data.errors;

        for (const key in errorObj) {
          if (errorObj.hasOwnProperty(key)) {
            const error = errorObj[key];

            error.forEach(message => {
              this.roleError.push(message);
            });
          }
        }
      }

      this.isRoleLoading = false;
    }
  },
  created() {
    this.getRoles();
  }
};
</script>

<style lang="scss" scoped>
.badge {
  padding: 0.35rem 0.35rem 0.32rem 0.35rem;
}

.permission-title {
  position: relative;

  span {
    background-color: #ffffff;
    position: inherit;
    padding-right: 0.5rem;
    z-index: 1;
  }

  &::after {
    content: "";
    background-color: #dbdbdb;
    height: 1px;
    width: 100%;
    position: absolute;
    left: 0;
    top: 50%;
  }
}
</style>
