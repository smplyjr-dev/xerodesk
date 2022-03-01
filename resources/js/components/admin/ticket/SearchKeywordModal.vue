<template>
  <modal mId="search" mClass="modal-xl" data-backdrop="static" data-keyboard="false">
    <modal-header>
      <h5 class="modal-title" id="search-label">Search a Keyword</h5>
    </modal-header>
    <modal-body>
      <div class="server-datatable">
        <div class="flex-center-between flex-wrap">
          <div class="dts-search w-100">
            <label class="mb-0 mr-2" for="search">Search:</label>
            <input class="form-control" id="search" type="text" placeholder="E.g. removal" v-model="search" @input="handleSearch" />
          </div>
        </div>

        <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
          <tbody class="text-sm block-body">
            <tr class="text-center" v-if="isLoading">
              <td colspan="2">
                <div class="spinner-border text-lg my-4" style="height: 5rem; width: 5rem"></div>
              </td>
            </tr>

            <tr class="text-sm" v-else v-for="p in paginated" :key="p.id">
              <!-- Mobile View -->
              <td class="sk dt-mobile">
                <p class="font-weight-semi">{{ getSender(p) }}</p>
                <router-link :to="`/tickets/${p.session}`" data-dismiss="modal" v-html="getMessage(p)"> </router-link>
              </td>

              <td class="sk">
                <p class="font-weight-semi">{{ getSender(p) }}</p>
                <router-link :to="`/tickets/${p.session}`" data-dismiss="modal" v-html="getMessage(p)"> </router-link>
              </td>
            </tr>

            <tr v-if="!isLoading && !paginated.length">
              <td colspan="2">
                <div class="w-100 my-3 text-center">
                  <template v-if="!tableData.search">No keyword found.</template>
                  <template v-else-if="tableData.search.length <= 2">Please input atleast 3 characters.</template>
                  <template v-else>No result found.</template>
                </div>
              </td>
            </tr>
          </tbody>
        </datatable>

        <div class="flex-center-between flex-wrap">
          <entries :pagination="pagination" />
          <pagination :pagination="pagination" @prev="getDatatable(pagination.prevPageUrl)" @next="getDatatable(pagination.nextPageUrl)" />
        </div>
      </div>
    </modal-body>
  </modal>
</template>

<script>
import debounce from "debounce";
import { Datatable, Entries, Pagination, Mixin } from "@SDT";

export default {
  name: "SearchKeywordModal",
  mixins: [Mixin],
  components: { Datatable, Entries, Pagination },
  data() {
    return {
      // for datatable only
      sortKey: "",
      tableData: {
        column: 0, // column where sortation will happen
        length: 20,
        search: ""
      },

      // custom data
      isLoading: false,
      search: ""
    };
  },
  computed: {
    columns() {
      let types = ["string", "number", "date"];
      let columns = [
        { sortable: 0, hide: 0, type: types[0], width: "100%", name: "info", label: "Result" },
        { sortable: 0, hide: 0, type: types[0], width: "100%", name: "message", label: "Result" }
      ];

      return columns;
    }
  },
  methods: {
    getDatatable(url = `/portal/message/search`) {
      this.isLoading = true;
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
    handleSearch() {
      if (this.search && this.search.length >= 3) {
        this.processSearch();
      } else {
        this.tableData.search = this.search;
        this.paginated = [];
      }
    },
    processSearch: debounce(function () {
      this.tableData.search = this.search;
      this.getDatatable();
    }, 500),

    getSender(p) {
      return p.sender == "client" ? p.client : p.agent;
    },
    getMessage(p) {
      let keyword = this.search.toLowerCase();
      let message = p.message.toLowerCase();
      message = message.replace("<p>", ""); // the message always start at <p>, ends with </p>
      message = message.substring(0, message.lastIndexOf("</p>"));
      message = message.trim();
      message = message.split(keyword);

      return `
        <span class="text-secondary">...${message[0] ?? ""}</span>
        <span class="text-dark font-weight-bold font-italic text-underline">${keyword}</span>
        <span class="text-secondary">${message[1] ?? ""}...</span>
      `;
    }
  }
};
</script>
