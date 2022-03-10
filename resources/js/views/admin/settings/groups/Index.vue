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
            <div class="font-weight-semi">Add Group</div>
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
                      <img :src="profilePicture(u)" @error="$onImgError($event, 1)" :alt="u.profile_picture" />
                    </router-link>

                    <a href="javascript:void(0)" @click="selectUsers(p)">
                      <i class="fa fa-fw fa-plus"></i>
                    </a>
                  </div>
                </span>
              </div>
              <div class="text-center">
                <button type="button" class="btn btn-secondary btn-sm my-1" @click="setMethod('update', p)">Edit</button>
                <button type="button" class="btn btn-danger btn-sm my-1" @click="setMethod('delete', p)">Delete</button>
              </div>
            </td>

            <td>
              <p class="mb-0 font-weight-semi">{{ p.name }}</p>
              <p class="mb-0 text-secondary text-sm">{{ p.description }}</p>
            </td>
            <td>
              <div class="social-media">
                <router-link :to="`/settings/users/${u.pivot.user_id}/edit`" v-for="(u, $u) in p.users" :key="$u">
                  <img :src="profilePicture(u)" @error="$onImgError($event, 1)" :alt="u.profile_picture" />
                </router-link>

                <a href="javascript:void(0)" @click="selectUsers(p)">
                  <i class="fas fa-fw fa-plus"></i>
                </a>
              </div>
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

    <modal mId="users-modal" mClass="modal-lg">
      <modal-header>
        <h5 class="modal-title">Add User</h5>
      </modal-header>
      <modal-body>
        <TableGroupUsers :group="group" @refresh="handleRefresh" />
      </modal-body>
      <modal-footer>
        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
      </modal-footer>
    </modal>

    <modal mId="manage-group">
      <modal-header>
        <h5 class="modal-title" v-if="groupMethod == 'delete'">Are you sure?</h5>
        <h5 class="modal-title" v-if="groupMethod != 'delete'">{{ groupMethod == "create" ? "Create" : "Update" }} Group</h5>
      </modal-header>
      <form @submit.prevent="submitGroup()">
        <modal-body>
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
        </modal-body>
        <modal-footer>
          <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>

          <template v-if="groupMethod == 'delete'">
            <button type="submit" class="btn btn-brand-1" :disabled="isGroupLoading">
              <div v-if="isGroupLoading" class="spinner-border spinner-border-sm" role="status"></div>
              <span v-else>Delete</span>
            </button>
          </template>
          <template v-else>
            <button type="submit" class="btn btn-brand-1" :disabled="isGroupLoading">
              <div v-if="isGroupLoading" class="spinner-border spinner-border-sm" role="status"></div>
              <span v-else>{{ groupMethod == "create" ? "Create" : "Update" }}</span>
            </button>
          </template>
        </modal-footer>
      </form>
    </modal>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import { Datatable, Pagination, Mixin } from "@CDT";
import TableGroupUsers from "@Components/admin/settings/TableGroupUsers.vue";

export default {
  layout: "Admin",
  mixins: [Mixin],
  name: "SettingGroups",
  metaInfo: () => ({ title: "Setting / Groups" }),
  middleware: ["auth", "permission:view_groups"],
  components: { Datatable, Pagination, TableGroupUsers },
  data() {
    let types = ["string", "number", "date"];

    return {
      group: null,
      groups: [],
      columns: [
        { sortable: 0, hide: 0, type: types[0], width: "100%", name: "info", label: "Group Details" },
        { sortable: 1, hide: 0, type: types[0], width: "30%", name: "name", label: "Name" },
        { sortable: 0, hide: 0, type: types[0], width: "50%", name: "users", label: "Agents" },
        { sortable: 0, hide: 0, type: types[0], width: "20%", name: "action", label: "Action" }
      ],

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
    ...mapGetters("auth", ["permissions"])
  },
  methods: {
    async getDatatable(shouldRefresh = true, url = `/portal/group/datatable`) {
      this.isLoading = shouldRefresh;

      let { data } = await axios.get(url, { params: this.tableData });
      this.datatableDatas = data;
      this.pagination.total = this.datatableDatas.length;

      this.isLoading = false;
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
      if (user.profile_picture == "profile.png") {
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

      $("#manage-group").modal("show");
    },
    async submitGroup() {
      this.isGroupLoading = true;
      this.groupError = [];

      try {
        let response = "";

        ["create_group", "edit_group", "delete_group"].forEach((allowed_permission) => {
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
          let { data } = await axios.post(`/portal/group`, {
            name: this.groupDetails.name,
            description: this.groupDetails.description
          });

          response = data;
        }

        if (this.groupMethod == "update") {
          let { data } = await axios.put(`/portal/group/${this.groupDetails.id}`, {
            name: this.groupDetails.name,
            description: this.groupDetails.description
          });

          response = data;
        }

        if (this.groupMethod == "delete") {
          let { data } = await axios.delete(`/portal/group/${this.groupDetails.id}`);

          response = data;
        }

        // close modal
        $("#manage-group").modal("hide");

        // refresh datatable
        this.getDatatable(false);

        // refresh groups from store
        this.$store.dispatch("groups/fetchGroups");

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

            error.forEach((message) => {
              this.groupError.push(message);
            });
          }
        }
      }

      this.isGroupLoading = false;
    },
    selectUsers(group) {
      ["select_group_users"].forEach((allowed_permission) => {
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
      await this.getDatatable(false);
      this.group = this.datatableDatas.find((g) => g.id == this.group.id);
    }
  },
  created() {
    this.$emit("setTitle", "Groups");
  }
};
</script>

<style lang="scss" scoped>
.social-media {
  a {
    .fas,
    img {
      background: #c4c4c4;
      border: 3px solid #ffffff;
      border-radius: 100%;
      color: #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 40px;
      width: 40px;
      object-fit: cover;
    }
  }
}
</style>
