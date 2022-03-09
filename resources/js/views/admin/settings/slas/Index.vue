<template>
  <div class="container-fluid px-4">
    <div class="server-datatable">
      <div class="flex-center-between flex-wrap">
        <length @onSelect="handleOnSelect" />
        <button type="button" class="btn btn-brand-1" @click="setMethod('create')"><i class="fa fa-plus mr-1"></i> Add SLA</button>
      </div>

      <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
        <tbody class="text-sm">
          <tr class="text-center" v-if="isLoading">
            <td colspan="4">
              <div class="spinner-border text-lg my-4" style="height: 5rem; width: 5rem"></div>
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
                <div class="w-50 ml-1 align-self-end">{{ p.range }} hours</div>
              </div>
              <div class="text-center">
                <button type="button" class="btn btn-secondary btn-sm my-1" @click="setMethod('update', p)">Edit</button>
                <button type="button" class="btn btn-danger btn-sm my-1" @click="setMethod('delete', p)">Delete</button>
              </div>
            </td>

            <td>{{ p.name }}</td>
            <td>
              <!-- <div class="sla-colors">
                <div class="color-item cursor-default active">
                  <div class="color-content" :style="{ background: p.color }"></div>
                </div>
              </div> -->

              <div style="border-radius: 100%; height: 30px; width: 30px" :style="{ background: p.color }"></div>
            </td>
            <td>{{ p.range }} hours</td>
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
            <td colspan="4">
              <div class="w-100 my-3 flex-center-center flex-column">No result found.</div>
            </td>
          </tr>
        </tbody>
      </datatable>

      <div class="flex-center-between flex-wrap">
        <entries :pagination="pagination" />
        <pagination :pagination="pagination" @prev="getDatatable(pagination.prevPageUrl)" @next="getDatatable(pagination.nextPageUrl)" />
      </div>
    </div>

    <modal mId="manage-sla">
      <modal-header>
        <h5 class="modal-title" v-if="slaMethod == 'delete'">Are you sure?</h5>
        <h5 class="modal-title" v-if="slaMethod != 'delete'">{{ slaMethod == "create" ? "Create" : "Update" }} SLA</h5>
      </modal-header>
      <form @submit.prevent="submitSla()">
        <modal-body>
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
                <div class="color-item" @click="slaDetails.color = '#616161'" :class="{ active: slaDetails.color == '#616161' }">
                  <div class="color-content" :style="{ background: '#616161' }"></div>
                </div>
                <div class="color-item" @click="slaDetails.color = '#fd6666'" :class="{ active: slaDetails.color == '#fd6666' }">
                  <div class="color-content" :style="{ background: '#fd6666' }"></div>
                </div>
                <div class="color-item" @click="slaDetails.color = '#dcdf3d'" :class="{ active: slaDetails.color == '#dcdf3d' }">
                  <div class="color-content" :style="{ background: '#dcdf3d' }"></div>
                </div>
                <div class="color-item" @click="slaDetails.color = '#71e476'" :class="{ active: slaDetails.color == '#71e476' }">
                  <div class="color-content" :style="{ background: '#71e476' }"></div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="range">Time to Resolve (hours) <span class="text-danger">*</span></label>
              <input id="range" type="text" class="form-control" v-model.number="slaDetails.range" />
            </div>
          </template>
        </modal-body>
        <modal-footer>
          <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>

          <template v-if="slaMethod == 'delete'">
            <button type="submit" class="btn btn-brand-1" :disabled="isSlaLoading">
              <div v-if="isSlaLoading" class="spinner-border spinner-border-sm" role="status"></div>
              <span v-else>Delete</span>
            </button>
          </template>
          <template v-else>
            <button type="submit" class="btn btn-brand-1" :disabled="isSlaLoading">
              <div v-if="isSlaLoading" class="spinner-border spinner-border-sm" role="status"></div>
              <span v-else>{{ slaMethod == "create" ? "Create" : "Update" }}</span>
            </button>
          </template>
        </modal-footer>
      </form>
    </modal>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import { Length, Search, Datatable, Entries, Pagination, Mixin } from "@SDT";

export default {
  mixins: [Mixin],
  layout: "Admin",
  name: "SettingSlas",
  metaInfo: () => ({ title: "Setting / SLA's" }),
  middleware: ["auth", "permission:view_slas"],
  components: { Length, Search, Datatable, Entries, Pagination },
  data() {
    let sortOrders = {};
    let types = ["string", "number", "date"];
    let columns = [
      { sortable: 0, hide: 0, type: types[0], width: "100%", name: "info", label: "SLA Details" },
      { sortable: 1, hide: 0, type: types[0], width: "30%", name: "name", label: "Name" },
      { sortable: 1, hide: 0, type: types[0], width: "25%", name: "color", label: "Color" },
      { sortable: 1, hide: 0, type: types[0], width: "25%", name: "range", label: "Time to Resolve" },
      { sortable: 0, hide: 0, type: types[0], width: "20%", name: "action", label: "Action" }
    ];
    columns.forEach((column) => {
      sortOrders[column.name] = -1;
    });
    return {
      columns: columns,
      sortKey: "id",

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
        .then((response) => {
          let data = response.data;
          if (this.tableData.draw == data.draw) {
            this.paginated = data.data.data;
            this.configPagination(data.data);
            this.isLoading = false;
          }
        })
        .catch((errors) => {
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

            error.forEach((message) => {
              this.slaError.push(message);
            });
          }
        }
      }

      this.isSlaLoading = false;
    }
  },
  created() {
    this.$emit("setTitle", "Service-level Agreement");
    this.getDatatable();
  }
};
</script>
