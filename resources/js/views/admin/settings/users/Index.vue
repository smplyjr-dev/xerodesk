<template>
  <div class="container-fluid">
    <SettingMeta />

    <div class="card card-1">
      <div class="card-body">
        <div class="page-title">
          <div>
            <h5 class="mb-2">Users</h5>
            <p class="text-secondary">Below are the list of your users. You can either create a user, update an existing one or resend a verification link.</p>
          </div>

          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-user">Create User</button>
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

              <tr class="text-sm" v-else v-for="(p, i) in paginated" :key="p.id">
                <td>
                  <div class="my-2 text-center">
                    <router-link :to="`/settings/users/${p.id}/edit`" style="min-width: 150px;">
                      <img loading="lazy" class="object-cover" :src="profilePicture(p)" @error="$onImgError($event, 1)" :alt="`${p.name}`" height="75px" width="75px" />
                      <div class="d-flex flex-column mt-2">
                        <span>{{ `${p.name}` }}</span>
                        <span class="text-muted text-xs">{{ p.meta.email }}</span>
                      </div>
                    </router-link>
                  </div>
                  <div class="d-flex align-items-center">
                    <span class="w-100 text-center">
                      <div class="social-media justify-content-center">
                        <a :href="p.meta.bio.facebook" target="_blank" v-if="p.meta.bio.facebook">
                          <i class="fa fa-fw fa-facebook" v-if="p.meta.bio.facebook"></i>
                        </a>
                        <a :href="p.meta.bio.twitter" target="_blank" v-if="p.meta.bio.twitter">
                          <i class="fa fa-fw fa-twitter" v-if="p.meta.bio.twitter"></i>
                        </a>
                        <a :href="p.meta.bio.linkedin" target="_blank" v-if="p.meta.bio.linkedin">
                          <i class="fa fa-fw fa-linkedin" v-if="p.meta.bio.linkedin"></i>
                        </a>
                      </div>

                      <template v-if="!p.meta.bio.facebook && !p.meta.bio.twitter && !p.meta.bio.linkedin">
                        No Social Media
                      </template>
                    </span>
                  </div>
                  <div class="text-center">
                    <p class="font-weight-bold my-2">
                      {{ p.role }} /
                      <span class="badge badge-pill badge-primary" v-if="p.status == 'Active'">{{ p.status }}</span>
                      <span class="badge badge-pill badge-secondary" v-if="p.status == 'Inactive'">{{ p.status }}</span>
                    </p>
                  </div>
                  <div class="text-center">
                    <div class="flex-center text-success font-weight-bold my-2" v-if="!$isNull(p.email_verified_at)">
                      <InlineSvg class="mr-1" name="template/mdi-check-decagram.svg" size="1.25rem" />
                      Account Verified
                    </div>
                    <button type="button" class="btn btn-primary btn-sm my-1" @click="resendVerification(i, p)" :disabled="p.status == 1" v-else>
                      <span v-if="!resendingIndex.includes(i)">Resend Email Verification</span>
                      <span v-if="resendingIndex.includes(i)" class="spinner-border spinner-border-sm"></span>
                    </button>

                    <!-- <button type="button" class="btn btn-danger btn-sm my-1" data-toggle="modal" data-target="#delete-user" @click="toDelete = p">Delete</button> -->
                    <router-link class="btn btn-secondary btn-sm my-1" :to="`/settings/users/${p.id}/edit`">Edit</router-link>
                  </div>
                </td>

                <td>
                  <router-link :to="`/settings/users/${p.id}/edit`" class="d-flex align-items-center">
                    <img loading="lazy" class="object-cover" :src="profilePicture(p)" @error="$onImgError($event, 1)" :alt="`${p.name}`" height="50px" width="50px" />
                    <div class="d-flex flex-column ml-2">
                      <span>{{ `${p.name}` }}</span>
                      <span class="text-muted text-xs">{{ p.meta.email }}</span>
                    </div>
                  </router-link>
                </td>
                <td>
                  <div class="social-media">
                    <a :href="p.meta.bio.facebook" target="_blank" v-if="p.meta.bio.facebook">
                      <i class="fa fa-fw fa-facebook" v-if="p.meta.bio.facebook"></i>
                    </a>
                    <a :href="p.meta.bio.twitter" target="_blank" v-if="p.meta.bio.twitter">
                      <i class="fa fa-fw fa-twitter" v-if="p.meta.bio.twitter"></i>
                    </a>
                    <a :href="p.meta.bio.linkedin" target="_blank" v-if="p.meta.bio.linkedin">
                      <i class="fa fa-fw fa-linkedin" v-if="p.meta.bio.linkedin"></i>
                    </a>
                  </div>

                  <template v-if="!p.meta.bio.facebook && !p.meta.bio.twitter && !p.meta.bio.linkedin">
                    No Social Media
                  </template>
                </td>
                <td>{{ p.role }}</td>
                <td>
                  <span class="badge badge-pill badge-primary" v-if="p.status == 'Activated'">{{ p.status }}</span>
                  <span class="badge badge-pill badge-secondary" v-if="p.status == 'Deactivated'">{{ p.status }}</span>
                </td>
                <td>
                  <div class="d-flex align-items-center text-success font-weight-bold" v-if="!$isNull(p.email_verified_at)">
                    <InlineSvg class="mr-1" name="template/mdi-check-decagram.svg" size="1.25rem" />
                    Account Verified
                  </div>

                  <button type="button" class="btn btn-primary btn-sm my-1" @click="resendVerification(i, p)" :disabled="p.status == 1" v-else>
                    <span v-if="!resendingIndex.includes(i)">Resend Email Verification</span>
                    <span v-if="resendingIndex.includes(i)" class="spinner-border spinner-border-sm"></span>
                  </button>
                </td>
                <td>
                  <button type="button" class="btn btn-danger btn-sm my-1" data-toggle="modal" data-target="#delete-user" @click="toDelete = p">Delete</button>
                  <router-link class="btn btn-secondary btn-sm my-1" :to="`/settings/users/${p.id}/edit`">Edit</router-link>
                </td>
              </tr>

              <tr v-if="!isLoading && !paginated.length">
                <td colspan="6">
                  <div class="w-100 my-3 flex-center flex-column">No result found.</div>
                </td>
              </tr>
            </tbody>
          </datatable>

          <pagination :pagination="pagination" :client="true" :filtered="filteredUsers" @prev="--pagination.currentPage" @next="++pagination.currentPage"></pagination>
        </div>
      </div>
    </div>

    <div id="add-user" class="modal fade">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Create User</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <div class="modal-body">
            <div class="form-group" v-show="!$isEmpty(userError)">
              <form-alert variant="warning">
                <p v-for="error in userError" :key="error" v-html="error"></p>
              </form-alert>
            </div>
            <div class="form-group">
              <label for="role" class="text-sm">Role <span class="text-danger">*</span></label>
              <select id="role" class="custom-select" v-model="user.role">
                <option selected disabled value>-- Choose Role --</option>
                <option v-for="role in roles" :key="role.id">{{ role.name }}</option>
              </select>
            </div>
            <div class="form-group row">
              <div class="col">
                <label for="first_name">First Name <span class="text-danger">*</span></label>
                <input id="first_name" type="text" class="form-control" v-model="user.first_name" />
              </div>
              <div class="col">
                <label for="last_name">Last Name <span class="text-danger">*</span></label>
                <input id="last_name" type="text" class="form-control" v-model="user.last_name" />
              </div>
            </div>
            <div class="form-group">
              <label for="username">Username <span class="text-danger">*</span></label>
              <input id="username" type="text" class="form-control" v-model="user.username" />
            </div>
            <div class="form-group">
              <label for="email">Email Address <span class="text-danger">*</span></label>
              <input id="email" type="email" class="form-control" v-model="user.email" />
            </div>
            <div class="form-group">
              <label for="password">Password <span class="text-danger">*</span></label>
              <input id="password" type="password" class="form-control" v-model="user.password" />
            </div>
            <div class="form-group">
              <label for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
              <input id="password_confirmation" type="password" class="form-control" v-model="user.password_confirmation" />
            </div>
            <div class="form-group">
              <label>Email Verification</label>

              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="skip_verification" v-model="user.skip_verification" />
                <label class="form-check-label" for="skip_verification">Check this if you want to skip the email verification</label>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" @click="createUser()" :disabled="isUserLoading">
              <div v-if="isUserLoading" class="spinner-border spinner-border-sm" role="status"></div>
              <span v-else>Create</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <div id="delete-user" class="modal fade">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Are you sure?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <div class="modal-body">
            <p class="mb-0" v-if="toDelete">
              You are about to delete <span class="font-weight-semi">{{ `${toDelete.name}` }}</span
              >.
            </p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" @click="confirmDelete()">Delete</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import Datatable from "@Components/datatable/client/Datatable.vue";
import Pagination from "@Components/datatable/client/Pagination.vue";
import SettingMeta from "@Components/admin/settings/SettingMeta.vue";

export default {
  layout: "Admin",
  name: "SettingUsers",
  metaInfo: () => ({ title: "Setting / Users" }),
  middleware: ["auth", "permission:view_users"],
  components: { Datatable, Pagination, SettingMeta },
  computed: {
    ...mapState("roles", ["roles"]),

    filteredUsers() {
      let users = this.users;
      if (this.search) {
        users = users.filter(row => {
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
    }
  },
  data() {
    let sortOrders = {};
    let types = ["string", "number", "date"];
    let columns = [
      { sortable: 0, hide: 0, type: types[0], width: "100%", name: "info", label: "User Details" },
      { sortable: 0, hide: 1, type: types[1], width: "0%", name: "id", label: "Id" },
      { sortable: 1, hide: 0, type: types[0], width: "0%", name: "name", label: "Name" },
      { sortable: 0, hide: 0, type: types[0], width: "0%", name: "social", label: "Social Media" },
      { sortable: 1, hide: 0, type: types[0], width: "0%", name: "role", label: "Role" },
      { sortable: 1, hide: 0, type: types[0], width: "0%", name: "status", label: "Status" },
      { sortable: 0, hide: 0, type: types[0], width: "0%", name: "verification", label: "Verification" },
      { sortable: 0, hide: 0, type: types[0], width: "0%", name: "action", label: "Action" }
    ];
    columns.forEach(column => {
      sortOrders[column.name] = -1;
    });
    return {
      users: [],
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
      toDelete: {},
      resendingIndex: [],
      isUserLoading: false,
      userError: [],
      user: {
        first_name: "",
        last_name: "",
        username: "",
        email: "",
        password: "",
        password_confirmation: "",
        role: "",
        skip_verification: true
      }
    };
  },
  methods: {
    getUsers(shouldRefresh = true, url = `/portal/user/datatable`) {
      this.isLoading = shouldRefresh;

      axios
        .get(url, { params: this.tableData })
        .then(response => {
          this.users = response.data;
          this.pagination.total = this.users.length;
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
      if (user.meta.profile_picture == "generic-profile.png") {
        return `${this.$APP_URL}/images/${user.meta.profile_picture}`;
      } else {
        return `${this.$APP_URL}/storage/uploads/users/${user.id}/${user.meta.profile_picture}`;
      }
    },
    async confirmDelete() {
      let { data } = await axios.delete(`/portal/user/${this.toDelete.id}`);
      let name = `${data.bio.last_name}, ${data.bio.first_name}`;

      // close modal
      $("#delete-user").modal("hide");

      // refresh datatable
      this.getUsers(false);

      // show notification
      this.$store.dispatch("notifications/addNotification", {
        variant: "bg-success",
        icon: "fa-check",
        title: "Success!",
        body: `<span class="font-weight-semi">${name}</span> has been successfully deleted.`
      });
    },
    async resendVerification(index, user) {
      let name = `${user.meta.bio.last_name}, ${user.meta.bio.first_name}`;

      this.resendingIndex.push(index);

      try {
        let { data } = await axios.post("/email/resend", {
          email: user.meta.email
        });

        this.$store.dispatch("notifications/addNotification", {
          variant: "bg-success",
          icon: "fa-check",
          title: "Success!",
          body: data.status.join(" ")
        });
      } catch (error) {
        let errorObj = error.response.data.errors;
        let resendVerificationError = "";

        for (const key in errorObj) {
          if (errorObj.hasOwnProperty(key)) {
            const error = errorObj[key];

            error.forEach(message => {
              resendVerificationError += `${message} <br />`;
            });
          }
        }

        this.$store.dispatch("notifications/addNotification", {
          variant: "bg-danger",
          icon: "fa-times",
          title: "Invalid.",
          body: resendVerificationError
        });
      }

      this.resendingIndex = this.resendingIndex.filter(num => num != index);
    },
    async createUser() {
      this.isUserLoading = true;
      this.userError = [];

      try {
        let { data } = await axios.post(`/portal/user`, this.user);

        this.$store.dispatch("notifications/addNotification", {
          variant: "bg-success",
          icon: "fa-check",
          title: "Success!",
          body: `<span class="font-weight-semi">${this.user.last_name}, ${this.user.first_name}</span> has been successfully created.`
        });

        $("#add-user").modal("hide");
        this.getUsers(false);

        this.user.first_name = "";
        this.user.last_name = "";
        this.user.email = "";
        this.user.username = "";
        this.user.password = "";
        this.user.password_confirmation = "";
        this.user.role = "";
        this.user.skip_verification = true;
      } catch (error) {
        const errorObj = error.response.data.errors;

        for (const key in errorObj) {
          if (errorObj.hasOwnProperty(key)) {
            const error = errorObj[key];

            error.forEach(message => {
              this.userError.push(message);
            });
          }
        }
      }

      this.isUserLoading = false;
    }
  },
  created() {
    this.getUsers();
    this.$store.dispatch("roles/fetchRoles");
  }
};
</script>

<style lang="scss" scoped>
.social-media {
  display: flex;
  align-items: center;

  a {
    text-decoration: none;

    .fa {
      padding: 1rem;
      border: 3px solid #fff;
      border-radius: 100%;
      color: #fff;
      height: 20px;
      width: 20px;
      display: flex;
      align-items: center;
      justify-content: center;

      &.fa-facebook {
        background: #4267b2;
      }
      &.fa-twitter {
        background: #1da1f2;
      }
      &.fa-linkedin {
        background: #0073b0;
      }
    }

    &:not(:first-child) {
      margin-left: -15px;
    }
  }
}
</style>
