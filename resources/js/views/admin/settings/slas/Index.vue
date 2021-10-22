<template>
  <div class="container-fluid">
    <SettingMeta />

    <div class="card card-1">
      <div class="card-body">
        <div class="page-title">
          <div>
            <h5 class="mb-2">Service-level Agreement</h5>
            <p class="text-secondary">Set the level of your SLA's in here.</p>
          </div>

          <button type="button" class="btn btn-primary" @click="setMethod('create')">Create SLA</button>
        </div>

        <div class="server-datatable">
          <div class="d-flex justify-content-between align-items-center flex-wrap">
            <length @onSelect="handleOnSelect" />
            <search @onSearch="searchDatatable" />
          </div>

          <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
            <tbody class="text-sm">
              <tr class="text-center" v-if="isLoading">
                <td colspan="4">
                  <div class="spinner-border text-lg my-4" style="height: 5rem; width: 5rem;"></div>
                </td>
              </tr>

              <tr class="text-sm" v-else v-for="p in paginated" :key="p.id">
                <td>
                  <div class="d-flex align-items-center">
                    <div class="w-50 font-weight-bold text-right">Name:</div>
                    <div class="w-50 ml-1 align-self-end">{{ p.name }}</div>
                  </div>
                  <div class="d-flex align-items-center">
                    <div class="w-50 font-weight-bold text-right">Color:</div>
                    <div class="w-50 ml-1 align-self-end">
                      <div class="sla-colors">
                        <div class="color-item cursor-default active">
                          <div class="color-content" :style="{ background: p.color }"></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="d-flex align-items-center">
                    <div class="w-50 font-weight-bold text-right">Range:</div>
                    <div class="w-50 ml-1 align-self-end">{{ p.range }}</div>
                  </div>
                  <div class="text-center">
                    <button type="button" class="btn btn-secondary btn-sm my-1" @click="setMethod('update', p)">Edit</button>
                    <button type="button" class="btn btn-danger btn-sm my-1" @click="setMethod('delete', p)">Delete</button>
                  </div>
                </td>

                <td>{{ p.name }}</td>
                <td>
                  <div class="sla-colors">
                    <div class="color-item cursor-default active">
                      <div class="color-content" :style="{ background: p.color }"></div>
                    </div>
                  </div>
                </td>
                <td>{{ p.range }}</td>
                <td>
                  <button type="button" class="btn btn-secondary btn-sm my-1" @click="setMethod('update', p)">Edit</button>
                  <button type="button" class="btn btn-danger btn-sm my-1" @click="setMethod('delete', p)">Delete</button>
                </td>
              </tr>

              <tr v-if="!isLoading && !paginated.length">
                <td colspan="4">
                  <div class="w-100 my-3 flex-center flex-column">No result found.</div>
                </td>
              </tr>
            </tbody>
          </datatable>

          <div class="d-flex justify-content-between align-items-center flex-wrap">
            <entries :pagination="pagination" />
            <pagination :pagination="pagination" @prev="getDatatable(pagination.prevPageUrl)" @next="getDatatable(pagination.nextPageUrl)" />
          </div>
        </div>
      </div>
    </div>

    <div id="manage-sla" class="modal fade">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" v-if="slaMethod == 'delete'">Are you sure?</h5>
            <h5 class="modal-title" v-if="slaMethod != 'delete'">{{ slaMethod == "create" ? "Create" : "Update" }} SLA</h5>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          </div>
          <form @submit.prevent="submitSla()">
            <div class="modal-body">
              <template v-if="slaMethod == 'delete'">
                <p class="mb-0">
                  You are about to delete the SLA named <span class="font-weight-semi">{{ slaDetails.name }}</span
                  >.
                </p>
              </template>
              <template v-else>
                <div class="form-group" v-show="!$isEmpty(slaError)">
                  <form-alert variant="warning">
                    <p v-for="error in slaError" :key="error" v-html="error"></p>
                  </form-alert>
                </div>
                <div class="form-group">
                  <label for="name">Name <span class="text-danger">*</span></label>
                  <input id="name" type="text" class="form-control" v-model="slaDetails.name" />
                </div>
                <div class="form-group">
                  <label for="color">Color <span class="text-danger">*</span></label>
                  <div class="sla-colors">
                    <div class="color-item" @click="slaDetails.color = 'black'" :class="{ active: slaDetails.color == 'black' }">
                      <div class="color-content" :style="{ background: 'black' }"></div>
                    </div>
                    <div class="color-item" @click="slaDetails.color = 'red'" :class="{ active: slaDetails.color == 'red' }">
                      <div class="color-content" :style="{ background: 'red' }"></div>
                    </div>
                    <div class="color-item" @click="slaDetails.color = 'yellow'" :class="{ active: slaDetails.color == 'yellow' }">
                      <div class="color-content" :style="{ background: 'yellow' }"></div>
                    </div>
                    <div class="color-item" @click="slaDetails.color = 'green'" :class="{ active: slaDetails.color == 'green' }">
                      <div class="color-content" :style="{ background: 'green' }"></div>
                    </div>
                  </div>
                  <!-- <input id="color" type="text" class="form-control" v-model="slaDetails.color" /> -->
                </div>
                <div class="form-group">
                  <label for="range">Time to Resolve (hours) <span class="text-danger">*</span></label>
                  <input id="range" type="text" class="form-control" v-model.number="slaDetails.range" />
                </div>
              </template>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>

              <template v-if="slaMethod == 'delete'">
                <button type="submit" class="btn btn-primary" :disabled="isSlaLoading">
                  <div v-if="isSlaLoading" class="spinner-border spinner-border-sm" role="status"></div>
                  <span v-else>Delete</span>
                </button>
              </template>
              <template v-else>
                <button type="submit" class="btn btn-primary" :disabled="isSlaLoading">
                  <div v-if="isSlaLoading" class="spinner-border spinner-border-sm" role="status"></div>
                  <span v-else>{{ slaMethod == "create" ? "Create" : "Update" }}</span>
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
import { Length, Search, Datatable, Entries, Pagination, Mixin } from "@SDT";
import SettingMeta from "@Components/admin/settings/SettingMeta.vue";

export default {
  mixins: [Mixin],
  layout: "Admin",
  name: "SettingSlas",
  metaInfo: () => ({ title: "Setting / SLA's" }),
  middleware: ["auth", "permission:view_slas"],
  components: { Length, Search, Datatable, Entries, Pagination, SettingMeta },
  data() {
    let sortOrders = {};
    let types = ["string", "number", "date"];
    let columns = [
      { sortable: 0, hide: 0, type: types[0], width: "100%", name: "info", label: "SLA Details" },
      { sortable: 1, hide: 0, type: types[0], width: "25%", name: "name", label: "Name" },
      { sortable: 1, hide: 0, type: types[0], width: "25%", name: "color", label: "Color" },
      { sortable: 1, hide: 0, type: types[0], width: "25%", name: "range", label: "Time to Resolve (hours)" },
      { sortable: 0, hide: 0, type: types[0], width: "25%", name: "action", label: "Action" }
    ];
    columns.forEach(column => {
      sortOrders[column.name] = -1;
    });
    return {
      columns: columns,
      sortKey: "id",
      sortOrders: sortOrders,

      // over tableData defaults from the mixin
      tableData: {
        column: 2
      },

      // custom data
      isLoading: false,
      isSlaLoading: false,
      slaError: [],
      slaMethod: "",
      slaDetails: {
        id: "",
        name: "",
        color: "",
        range: ""
      }
    };
  },
  computed: {
    ...mapGetters("auth", ["permissions"])
  },
  methods: {
    getDatatable(shouldRefresh = true, url = `/portal/sla/datatable`) {
      this.isLoading = shouldRefresh;
      this.tableData.draw++;

      axios
        .get(url, { params: this.tableData })
        .then(response => {
          let data = response.data;
          if (this.tableData.draw == data.draw) {
            this.paginated = data.data.data;
            this.configPagination(data.data);
            this.isLoading = false;
          }
        })
        .catch(errors => {
          console.log(errors);
        });
    },
    setMethod(method, sla) {
      this.slaError = [];

      if (method == "create") {
        this.slaMethod = "create";
        this.slaDetails.name = "";
        this.slaDetails.color = "";
        this.slaDetails.range = "";

        if (this.paginated.length <= 3) {
          $("#manage-sla").modal("show");
        } else {
          // display a notification
          this.$store.dispatch("notifications/addNotification", {
            variant: "bg-danger",
            icon: "fa-times",
            title: "Invalid.",
            body: "You are only allowed to enter up to 4 SLA."
          });
        }
      }

      if (method == "update") {
        this.slaMethod = "update";
        this.slaDetails.id = sla.id;
        this.slaDetails.name = sla.name;
        this.slaDetails.color = sla.color;
        this.slaDetails.range = sla.range;
        $("#manage-sla").modal("show");
      }

      if (method == "delete") {
        this.slaMethod = "delete";
        this.slaDetails.id = sla.id;
        this.slaDetails.name = sla.name;
        this.slaDetails.color = sla.color;
        this.slaDetails.range = sla.range;
        $("#manage-sla").modal("show");
      }
    },
    async submitSla() {
      this.isSlaLoading = true;
      this.slaError = [];

      try {
        let response = "";

        if (this.slaMethod == "create") {
          let { data } = await axios.post(`/portal/sla`, {
            name: this.slaDetails.name,
            color: this.slaDetails.color,
            range: this.slaDetails.range
          });

          response = data;
        }

        if (this.slaMethod == "update") {
          let { data } = await axios.put(`/portal/sla/${this.slaDetails.id}`, {
            name: this.slaDetails.name,
            color: this.slaDetails.color,
            range: this.slaDetails.range
          });

          response = data;
        }

        if (this.slaMethod == "delete") {
          let { data } = await axios.delete(`/portal/sla/${this.slaDetails.id}`);

          response = data;
        }

        // close modal
        $("#manage-sla").modal("hide");

        // refresh datatable
        this.getDatatable(false);

        // show notification
        this.$store.dispatch("notifications/addNotification", {
          variant: `bg-${response.status}`,
          icon: response.status == "success" ? "fa-check" : "fa-times",
          title: response.status == "success" ? "Success." : "Invalid.",
          body: response.message
        });

        // clear inputs
        this.slaDetails.id = "";
        this.slaDetails.name = "";
        this.slaDetails.color = "";
        this.slaDetails.range = "";
      } catch (error) {
        const errorObj = error.response.data.errors;

        for (const key in errorObj) {
          if (errorObj.hasOwnProperty(key)) {
            const error = errorObj[key];

            error.forEach(message => {
              this.slaError.push(message);
            });
          }
        }
      }

      this.isSlaLoading = false;
    }
  }
};
</script>
