<template>
  <div class="container-fluid">
    <SettingMeta />

    <div class="card card-1">
      <div class="card-body">
        <div class="page-title">
          <div>
            <h5 class="mb-2">Companies</h5>
            <p class="text-secondary">Select all allowed employees from each companies.</p>
          </div>

          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#manage-company" @click="setMethod('create')">Add Company</button>
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
                <td colspan="8">
                  <div class="spinner-border text-lg my-4" style="height: 5rem; width: 5rem;"></div>
                </td>
              </tr>

              <tr class="text-sm" v-else v-for="p in paginated" :key="p.id">
                <td>
                  <div class="d-flex">
                    <span class="w-25 font-weight-bold text-right">Name:</span>
                    <span class="w-75 ml-1 align-self-end">{{ p.name }}</span>
                  </div>
                  <div class="d-flex">
                    <span class="w-25 font-weight-bold text-right">URL:</span>
                    <span class="w-75 ml-1 align-self-end">
                      <a :href="p.url" target="_blank">{{ p.url }}</a>
                    </span>
                  </div>
                  <div class="d-flex">
                    <span class="w-25 font-weight-bold text-right">Action:</span>
                    <div class="w-75 ml-1 align-self-end">
                      <button type="button" class="btn btn-primary btn-sm" @click="selectCompany(p.id)">Select Employees</button>
                      <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#manage-company" @click="setMethod('update', p)">Edit</button>
                      <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#manage-company" @click="setMethod('delete', p)">Delete</button>
                    </div>
                  </div>
                </td>

                <td>{{ p.name }}</td>
                <td>
                  <a :href="p.url" target="_blank">{{ p.url }}</a>
                </td>
                <td>
                  <button type="button" class="btn btn-primary btn-sm" @click="selectCompany(p.id)">Select Employees</button>
                  <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#manage-company" @click="setMethod('update', p)">Edit</button>
                  <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#manage-company" @click="setMethod('delete', p)">Delete</button>
                </td>
              </tr>

              <tr v-if="!isLoading && !paginated.length">
                <td colspan="8">
                  <div class="w-100 my-3 flex-center flex-column">No result found.</div>
                </td>
              </tr>
            </tbody>
          </datatable>

          <pagination :pagination="pagination" :client="true" :filtered="filteredCompanies" @prev="--pagination.currentPage" @next="++pagination.currentPage"></pagination>
        </div>
      </div>
    </div>

    <div id="manage-company" class="modal fade">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" v-if="companyMethod == 'delete'">Are you sure?</h5>
            <h5 class="modal-title" v-if="companyMethod != 'delete'">{{ companyMethod == "create" ? "Add" : "Update" }} Company</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <form @submit.prevent="submitCompany()">
            <div class="modal-body">
              <template v-if="companyMethod == 'delete'">
                <p class="mb-0">
                  You are about to delete the company <span class="font-weight-semi">{{ companyDetails.name }}</span
                  >.
                </p>
              </template>
              <template v-else>
                <div class="form-group" v-show="!$isEmpty(companyError)">
                  <form-alert variant="warning">
                    <p v-for="error in companyError" :key="error" v-html="error"></p>
                  </form-alert>
                </div>
                <div class="form-group">
                  <label for="cname">Name <span class="text-danger">*</span></label>
                  <input id="cname" type="text" class="form-control" v-model="companyDetails.name" />
                </div>
                <div class="form-group">
                  <label for="curl">URL <span class="text-danger">*</span></label>
                  <input id="curl" type="text" class="form-control" v-model="companyDetails.url" />
                </div>
              </template>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>

              <template v-if="companyMethod == 'delete'">
                <button type="submit" class="btn btn-primary" :disabled="isCompanyLoading">
                  <div v-if="isCompanyLoading" class="spinner-border spinner-border-sm" role="status"></div>
                  <span v-else>Delete</span>
                </button>
              </template>
              <template v-else>
                <button type="submit" class="btn btn-primary" :disabled="isCompanyLoading">
                  <div v-if="isCompanyLoading" class="spinner-border spinner-border-sm" role="status"></div>
                  <span v-else>{{ companyMethod == "create" ? "Add" : "Update" }}</span>
                </button>
              </template>
            </div>
          </form>
        </div>
      </div>
    </div>

    <CompanyEmployeesModal :company="company" @refresh="(company = $event), getCompanies()" />
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import Datatable from "@Components/datatable/server/Datatable.vue";
import Pagination from "@Components/datatable/server/Pagination.vue";
import SettingMeta from "@Components/admin/settings/SettingMeta.vue";
import CompanyEmployeesModal from "@Components/admin/settings/CompanyEmployeesModal.vue";

export default {
  layout: "Admin",
  name: "SettingCompanies",
  metaInfo: () => ({
    title: "Setting / Companies"
  }),
  middleware: ["auth", "permission:view_companies"],
  components: { Datatable, Pagination, CompanyEmployeesModal, SettingMeta },
  data() {
    let sortOrders = {};
    let types = ["string", "number", "date"];
    let columns = [
      { sortable: 0, hide: 0, type: types[0], width: "100%", name: "info", label: "Company Details" },
      { sortable: 0, hide: 1, type: types[1], width: "0%", name: "id", label: "Id" },
      { sortable: 1, hide: 0, type: types[0], width: "0%", name: "name", label: "Name" },
      { sortable: 1, hide: 0, type: types[0], width: "0%", name: "url", label: "URL" },
      { sortable: 0, hide: 0, type: types[0], width: "0%", name: "action", label: "Action" }
    ];
    columns.forEach(column => {
      sortOrders[column.name] = -1;
    });
    return {
      company: null,
      companies: [],
      columns: columns,
      sortKey: "id",
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
      isCompanyLoading: false,
      companyError: [],
      companyMethod: "",
      companyDetails: {
        id: "",
        name: "",
        url: ""
      }
    };
  },
  computed: {
    ...mapGetters("auth", ["permissions"]),

    filteredCompanies() {
      let companies = this.companies;
      if (this.search) {
        companies = companies.filter(row => {
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
        companies = companies.slice().sort((a, b) => {
          let index = this.getIndex(this.columns, "name", sortKey);
          a = String(a[sortKey]).toLowerCase();
          b = String(b[sortKey]).toLowerCase();

          if (this.columns[index].type && this.columns[index].type === "date") {
            return (a === b ? 0 : new Date(a).getTime() > new Date(b).getTime() ? 1 : -1) * order;
          } else if (this.columns[index].type && this.columns[index].type === "number") {
            return (+a === +b ? 0 : +a > +b ? 1 : 1) * order; // 1 = asc, -1 = desc
          } else {
            return (a === b ? 0 : a > b ? 1 : -1) * order;
          }
        });
      }
      return companies;
    },
    paginated() {
      return this.paginate(this.filteredCompanies, this.length, this.pagination.currentPage);
    }
  },
  methods: {
    getCompanies(shouldRefresh = true, url = "/company/datatable") {
      this.isLoading = shouldRefresh;

      axios
        .get(url, { params: this.tableData })
        .then(response => {
          this.companies = response.data;
          this.pagination.total = this.companies.length;
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

    setMethod(method, company) {
      this.companyError = [];

      if (method == "create") {
        this.companyMethod = "create";
        this.companyDetails.name = "";
        this.companyDetails.url = "";
      } else {
        this.companyMethod = method;
        this.companyDetails.id = company.id;
        this.companyDetails.name = company.name;
        this.companyDetails.url = company.url;
      }
    },
    async submitCompany() {
      this.isCompanyLoading = true;
      this.companyError = [];

      try {
        let response = "";

        if (this.companyMethod == "create") {
          let { data } = await axios.post(`/company`, {
            name: this.companyDetails.name,
            url: this.companyDetails.url
          });

          response = data;
        }

        if (this.companyMethod == "update") {
          let { data } = await axios.put(`/company/${this.companyDetails.id}`, {
            name: this.companyDetails.name,
            url: this.companyDetails.url
          });

          response = data;
        }

        if (this.companyMethod == "delete") {
          let { data } = await axios.delete(`/company/${this.companyDetails.id}`);

          response = data;
        }

        // close modal
        $("#manage-company").modal("hide");

        // refresh datatable
        this.getCompanies(false);

        // show notification
        this.$store.dispatch("notifications/addNotification", {
          variant: `bg-${response.status}`,
          icon: "fa-check",
          title: response.status == "success" ? "Success." : "Invalid.",
          body: response.message
        });

        // clear inputs
        this.companyDetails.name = "";
        this.companyDetails.url = "";
        this.companyError = [];
      } catch (error) {
        const errorObj = error.response.data.errors;

        for (const key in errorObj) {
          if (errorObj.hasOwnProperty(key)) {
            const error = errorObj[key];

            error.forEach(message => {
              this.companyError.push(message);
            });
          }
        }
      }

      this.isCompanyLoading = false;
    },
    async selectCompany(id) {
      // check permission
      ["select_company_employees"].forEach(allowed_permission => {
        if (!this.permissions.includes(allowed_permission)) {
          // display a notification
          this.$store.dispatch("notifications/addNotification", {
            variant: "bg-danger",
            icon: "fa-times",
            title: "Invalid.",
            body: "Sorry! But you don't have the permission to select an employee."
          });

          return false;
        }
      });

      // set the company
      this.company = this.companies.find(c => c.id == id);

      // show the modal
      $("#companies-modal").modal("show");
    }
  },
  created() {
    this.getCompanies();
  }
};
</script>
