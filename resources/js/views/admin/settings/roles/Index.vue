<template>
  <div class="container-fluid px-4">
    <div class="client-datatable">
      <div class="flex-center-between flex-wrap">
        <div class="control d-flex align-items-center">
          <div class="select">
            <select class="custom-select custom-select-sm" v-model="length" @change="resetPagination()">
              <option>10</option>
              <option>20</option>
              <option>30</option>
              <option>50</option>
              <option>100</option>
            </select>
          </div>
        </div>
        <button type="button" class="btn btn-brand-1" @click="setMethod('create')">
          <div class="flex-center-between">
            <div class="font-weight-semi">Add User</div>
            <InlineSvg class="ml-2" name="svg/mdi/plus-circle-outline.svg" size="1.35rem" />
          </div>
        </button>
      </div>

      <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
        <tbody class="text-sm">
          <tr class="text-center" v-if="isLoading">
            <td colspan="6">
              <div class="spinner-border text-lg my-4" style="height: 5rem; width: 5rem"></div>
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
                <span class="badge badge-pill badge-danger mb-1 mr-1" v-for="p in p.permissions" :key="p.slug">{{ p.name }}</span>
              </div>
              <div class="text-center">
                <button type="button" class="btn btn-secondary btn-sm my-1" @click="setMethod('update', p)">Edit</button>
                <button type="button" class="btn btn-danger btn-sm my-1" @click="setMethod('delete', p)">Delete</button>
              </div>
            </td>

            <td>{{ p.name }}</td>
            <td>
              <span class="badge badge-pill badge-danger mb-1 mr-1" v-for="p in p.permissions" :key="p.slug">{{ p.slug }}</span>
            </td>
            <td>
              <dropdown :carret="false" :position="`right`">
                <template v-slot:value><i class="fas fa-ellipsis-v"></i></template>

                <dropdown-content minWidth="100px">
                  <template v-slot:content>
                    <dropdown-item @select="setMethod('update', p)">Edit</dropdown-item>
                    <dropdown-item @select="setMethod('delete', p)">Delete</dropdown-item>
                  </template>
                </dropdown-content>
              </dropdown>
            </td>
          </tr>

          <tr v-if="!isLoading && !paginated.length">
            <td colspan="6">
              <div class="w-100 my-3 flex-center-center flex-column">No result found.</div>
            </td>
          </tr>
        </tbody>
      </datatable>

      <pagination :pagination="pagination" :client="true" :filtered="filteredDatas" @prev="--pagination.currentPage" @next="++pagination.currentPage"></pagination>
    </div>

    <modal mId="manage-role">
      <modal-header>
        <h5 class="modal-title" v-if="roleMethod == 'delete'">Are you sure?</h5>
        <h5 class="modal-title" v-if="roleMethod != 'delete'">{{ roleMethod == "create" ? "Create" : "Update" }} Role</h5>
      </modal-header>
      <form @submit.prevent="submitRole()">
        <modal-body>
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
              <label>Select permissions down below</label>

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
        </modal-body>
        <modal-footer>
          <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>

          <template v-if="roleMethod == 'delete'">
            <button type="submit" class="btn btn-brand-1" :disabled="isRoleLoading">
              <div v-if="isRoleLoading" class="spinner-border spinner-border-sm" role="status"></div>
              <span v-else>Delete</span>
            </button>
          </template>
          <template v-else>
            <button type="submit" class="btn btn-brand-1" :disabled="isRoleLoading">
              <div v-if="isRoleLoading" class="spinner-border spinner-border-sm" role="status"></div>
              <span v-else>{{ roleMethod == "create" ? "Create" : "Update" }}</span>
            </button>
          </template>
        </modal-footer>
      </form>
    </modal>
  </div>
</template>

<script>
import Permissions from "@Public/docs/permissions.json";
import { Datatable, Pagination, Mixin } from "@CDT";

export default {
  layout: "Admin",
  mixins: [Mixin],
  name: "SettingRoles",
  metaInfo: () => ({ title: "Setting / Roles" }),
  middleware: ["auth", "permission:view_roles"],
  components: { Datatable, Pagination },
  data() {
    let types = ["string", "number", "date"];

    return {
      columns: [
        { sortable: 0, hide: 0, type: types[0], width: "100%", name: "info", label: "Role Details" },
        { sortable: 1, hide: 0, type: types[0], width: "15%", name: "name", label: "Name" },
        { sortable: 0, hide: 0, type: types[0], width: "70%", name: "permissions", label: "Permissions" },
        { sortable: 0, hide: 0, type: types[0], width: "15%", name: "action", label: "Action" }
      ],

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
  methods: {
    getDatatable(shouldRefresh = true, url = `/portal/role/datatable`) {
      this.isLoading = shouldRefresh;

      axios
        .get(url, { params: this.tableData })
        .then((response) => {
          this.datatableDatas = response.data;
          this.pagination.total = this.datatableDatas.length;
          this.isLoading = false;
        })
        .catch((errors) => {
          console.log(errors);
        });
    },

    setMethod(method, role) {
      this.roleError = [];

      if (method == "create") {
        this.roleMethod = "create";
        this.roleDetails.name = "";
        this.roleDetails.permissions = [];
      }

      if (method == "update") {
        this.roleMethod = "update";
        this.roleDetails.id = role.id;
        this.roleDetails.name = role.name;
        this.roleDetails.permissions = [...role.permissions.map((p) => ({ name: p.name, slug: p.slug }))];
      }

      if (method == "delete") {
        this.roleMethod = "delete";
        this.roleDetails.id = role.id;
        this.roleDetails.name = role.name;
      }

      $("#manage-role").modal("show");
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
        this.getDatatable(false);

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

            error.forEach((message) => {
              this.roleError.push(message);
            });
          }
        }
      }

      this.isRoleLoading = false;
    }
  },
  created() {
    this.$emit("setTitle", "Roles");
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
