<template>
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

        <template v-else>
          <tr class="text-sm" v-for="p in selectedEmployees" :key="p.id">
            <td>
              <div class="d-flex">
                <span class="w-25 font-weight-bold text-right">ID:</span>
                <span class="w-75 ml-1 align-self-end">{{ p.id }}</span>
              </div>
              <div class="d-flex">
                <span class="w-25 font-weight-bold text-right">Name:</span>
                <span class="w-75 ml-1 align-self-end">{{ `${p.first_name} ${p.last_name}` }}</span>
              </div>
              <div class="d-flex">
                <span class="w-25 font-weight-bold text-right">Email:</span>
                <span class="w-75 ml-1 align-self-end">{{ p.primary_email }}</span>
              </div>
              <div class="d-flex">
                <span class="w-25 font-weight-bold text-right">Action:</span>
                <div class="w-75 ml-1 align-self-end">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" :id="`switch${p.id}`" @click="setUser(p.id)" :checked="company.allowed_users.includes(p.id)" />
                    <label class="custom-control-label" :for="`switch${p.id}`">&nbsp;</label>
                  </div>
                </div>
              </div>
            </td>

            <td>{{ p.id }}</td>
            <td>{{ `${p.first_name} ${p.last_name}` }}</td>
            <td>{{ p.primary_email }}</td>
            <td>
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" :id="`switch${p.id}`" @click="setUser(p.id)" :checked="company.allowed_users.includes(p.id)" />
                <label class="custom-control-label" :for="`switch${p.id}`">&nbsp;</label>
              </div>
            </td>
          </tr>
          <tr class="text-sm" v-for="p in unselectedEmployees" :key="p.id">
            <td>
              <div class="d-flex">
                <span class="w-25 font-weight-bold text-right">ID:</span>
                <span class="w-75 ml-1 align-self-end">{{ p.id }}</span>
              </div>
              <div class="d-flex">
                <span class="w-25 font-weight-bold text-right">Name:</span>
                <span class="w-75 ml-1 align-self-end">{{ `${p.first_name} ${p.last_name}` }}</span>
              </div>
              <div class="d-flex">
                <span class="w-25 font-weight-bold text-right">Email:</span>
                <span class="w-75 ml-1 align-self-end">{{ p.primary_email }}</span>
              </div>
              <div class="d-flex">
                <span class="w-25 font-weight-bold text-right">Action:</span>
                <div class="w-75 ml-1 align-self-end">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" :id="`switch${p.id}`" @click="setUser(p.id)" :checked="company.allowed_users.includes(p.id)" />
                    <label class="custom-control-label" :for="`switch${p.id}`">&nbsp;</label>
                  </div>
                </div>
              </div>
            </td>

            <td>{{ p.id }}</td>
            <td>{{ `${p.first_name} ${p.last_name}` }}</td>
            <td>{{ p.primary_email }}</td>
            <td>
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" :id="`switch${p.id}`" @click="setUser(p.id)" :checked="company.allowed_users.includes(p.id)" />
                <label class="custom-control-label" :for="`switch${p.id}`">&nbsp;</label>
              </div>
            </td>
          </tr>
        </template>

        <tr v-if="!isLoading && !paginated.length">
          <td colspan="8">
            <div class="w-100 my-3 flex-center flex-column">No employee found.</div>
          </td>
        </tr>
      </tbody>
    </datatable>

    <pagination :pagination="pagination" :client="true" :filtered="filteredEmployees" @prev="--pagination.currentPage" @next="++pagination.currentPage"></pagination>
  </div>
</template>

<script>
import Datatable from "@Components/datatable/client/Datatable.vue";
import Pagination from "@Components/datatable/client/Pagination.vue";

export default {
  props: ["company", "employees"],
  name: "TableCompanyEmployees",
  components: { Datatable, Pagination },
  data() {
    let sortOrders = {};
    let types = ["string", "number", "date"];
    let columns = [
      { sortable: 0, hide: 0, type: types[0], width: "100%", name: "info", label: "Employee Details" },
      { sortable: 1, hide: 0, type: types[1], width: "0%", name: "id", label: "Id" },
      { sortable: 1, hide: 0, type: types[0], width: "0%", name: "name", label: "Name" },
      { sortable: 1, hide: 0, type: types[0], width: "0%", name: "email", label: "Email" },
      { sortable: 0, hide: 0, type: types[0], width: "0%", name: "action", label: "Action" }
    ];
    columns.forEach(column => {
      sortOrders[column.name] = -1;
    });
    return {
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
      isLoading: false
    };
  },
  computed: {
    filteredEmployees() {
      let employees = this.employees;
      if (this.search) {
        employees = employees.filter(row => {
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
        employees = employees.slice().sort((a, b) => {
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
      return employees;
    },
    paginated() {
      return this.paginate(this.filteredEmployees, this.length, this.pagination.currentPage);
    },
    selectedEmployees() {
      return this.paginated.filter(p => {
        return this.company.allowed_users.includes(p.id);
      });
    },
    unselectedEmployees() {
      return this.paginated.filter(p => {
        return !this.company.allowed_users.includes(p.id);
      });
    }
  },
  methods: {
    getEmployees(shouldRefresh = true) {
      this.isLoading = shouldRefresh;

      this.employees = this.employees;
      this.pagination.total = this.employees.length;

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

    async setUser(id) {
      let company_employees = this.company.allowed_users;
      let allowed_users = company_employees.includes(id) ? company_employees.filter(uId => uId !== id) : company_employees.concat([id]);

      let { data } = await axios.put(`/company/${this.company.id}`, {
        ...this.company,
        allowed_users
      });

      // set the new company object
      this.$emit("refresh", data);

      // refresh the datatable without showing loader
      this.getEmployees();
    }
  },
  watch: {
    employees: {
      deep: true,
      handler: function() {
        this.getEmployees();
      }
    }
  }
};
</script>
