<template>
  <div class="container-fluid px-4">
    <div class="row">
      <div class="col">
        <h4>Saved Replies</h4>
        <p>Have you find yourself typing the same thing all over again? Try our saved reply feature.</p>

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
                <div class="font-weight-semi">Add Reply</div>
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
                    <p class="font-weight-bold mb-0">Body:</p>
                    <span class="w-50 ml-1 align-self-end">{{ p.body }}</span>
                  </div>
                  <div class="text-center">
                    <button type="button" class="btn btn-secondary btn-sm my-1" @click="setMethod('update', p)">Edit</button>
                    <button type="button" class="btn btn-danger btn-sm my-1" @click="setMethod('delete', p)">Delete</button>
                  </div>
                </td>

                <td>{{ p.name }}</td>
                <td v-html="p.body"></td>
                <td>{{ $dayjs("format", p.updated_at, "MM/DD/YYYY hh:mm A") }}</td>
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

        <modal mId="manage-reply">
          <modal-header>
            <h5 class="modal-title" v-if="replyMethod == 'delete'">Are you sure?</h5>
            <h5 class="modal-title" v-if="replyMethod != 'delete'">{{ replyMethod == "create" ? "Create" : "Update" }} Reply</h5>
          </modal-header>
          <form @submit.prevent="submitReply()">
            <modal-body>
              <template v-if="replyMethod == 'delete'">
                <p class="mb-0">
                  You are about to delete the reply <span class="font-weight-semi">{{ replyDetails.name }}</span
                  >.
                </p>
              </template>
              <template v-else>
                <div class="form-group" v-show="!$isEmpty(replyError)">
                  <form-alert variant="warning">
                    <p v-for="error in replyError" :key="error" v-html="error"></p>
                  </form-alert>
                </div>
                <div class="form-group">
                  <label for="name">Name <span class="text-danger">*</span></label>
                  <input id="name" type="text" class="form-control" v-model="replyDetails.name" />
                </div>
                <div class="form-group">
                  <label for="body">Body <span class="text-danger">*</span></label>
                  <input id="body" type="text" class="form-control" v-model="replyDetails.body" />
                </div>
              </template>
            </modal-body>
            <modal-footer>
              <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>

              <template v-if="replyMethod == 'delete'">
                <button type="submit" class="btn btn-brand-1" :disabled="isReplyLoading">
                  <div v-if="isReplyLoading" class="spinner-border spinner-border-sm" role="status"></div>
                  <span v-else>Delete</span>
                </button>
              </template>
              <template v-else>
                <button type="submit" class="btn btn-brand-1" :disabled="isReplyLoading">
                  <div v-if="isReplyLoading" class="spinner-border spinner-border-sm" role="status"></div>
                  <span v-else>{{ replyMethod == "create" ? "Create" : "Update" }}</span>
                </button>
              </template>
            </modal-footer>
          </form>
        </modal>
      </div>
    </div>
  </div>
</template>

<script>
import { Datatable, Pagination, Mixin } from "@CDT";
import { mapState } from "vuex";

export default {
  layout: "Account",
  mixins: [Mixin],
  name: "Replies",
  metaInfo: () => ({ title: "Account / Replies" }),
  middleware: ["auth"],
  components: { Datatable, Pagination },
  data() {
    let types = ["string", "number", "date"];

    return {
      columns: [
        { sortable: 0, hide: 0, type: types[0], width: "100%", name: "info", label: "Reply Details" },
        { sortable: 1, hide: 0, type: types[0], width: "15%", name: "name", label: "Name" },
        { sortable: 1, hide: 0, type: types[0], width: "50%", name: "body", label: "Content" },
        { sortable: 0, hide: 0, type: types[0], width: "20%", name: "updated_at", label: "Timestamp" },
        { sortable: 0, hide: 0, type: types[0], width: "15%", name: "action", label: "Action" }
      ],

      // custom data
      isLoading: false,
      isReplyLoading: false,
      replyError: [],
      replyMethod: "",
      replyDetails: {
        id: "",
        body: ""
      }
    };
  },
  computed: {
    ...mapState("auth", ["user"])
  },
  methods: {
    getDatatable(shouldRefresh = true, url = `/portal/user/${this.user.id}/replies`) {
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

    setMethod(method, reply) {
      this.replyError = [];

      if (method == "create") {
        this.replyMethod = "create";
        this.replyDetails.name = "";
        this.replyDetails.body = "";
      }

      if (method == "update") {
        this.replyMethod = "update";
        this.replyDetails.id = reply.id;
        this.replyDetails.name = reply.name;
        this.replyDetails.body = reply.body;
      }

      if (method == "delete") {
        this.replyMethod = "delete";
        this.replyDetails.id = reply.id;
        this.replyDetails.name = reply.name;
        this.replyDetails.body = reply.body;
      }

      $("#manage-reply").modal("show");
    },
    async submitReply() {
      this.isReplyLoading = true;
      this.replyError = [];

      try {
        let response = "";

        if (this.replyMethod == "create") {
          let { data } = await axios.post(`/portal/user-reply`, {
            name: this.replyDetails.name,
            body: this.replyDetails.body
          });

          response = data;
        }

        if (this.replyMethod == "update") {
          let { data } = await axios.put(`/portal/user-reply/${this.replyDetails.id}`, {
            name: this.replyDetails.name,
            body: this.replyDetails.body
          });

          response = data;
        }

        if (this.replyMethod == "delete") {
          let { data } = await axios.delete(`/portal/user-reply/${this.replyDetails.id}`);

          response = data;
        }

        // close modal
        $("#manage-reply").modal("hide");

        // refresh datatable
        this.getDatatable(false);

        // show notification
        this.$store.dispatch("notifications/addNotification", {
          variant: `bg-${response.status}`,
          icon: "fa-check-circle",
          title: response.status == "success" ? "Success." : "Invalid.",
          body: response.message
        });

        // clear inputs
        this.replyDetails.id = "";
        this.replyDetails.body = "";
      } catch (error) {
        const errorObj = error.response.data.errors;

        for (const key in errorObj) {
          if (errorObj.hasOwnProperty(key)) {
            const error = errorObj[key];

            error.forEach((message) => {
              this.replyError.push(message);
            });
          }
        }
      }

      this.isReplyLoading = false;
    }
  }
};
</script>
