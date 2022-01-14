import debounce from "debounce";

export default {
  data() {
    return {
      paginated: [],
      perPage: [10, 20, 50, 100],
      tableData: {
        draw: 0,
        length: 10,
        search: "",
        column: 0,
        dir: "desc"
      },
      pagination: {
        lastPage: "",
        currentPage: "",
        total: "",
        lastPageUrl: "",
        nextPageUrl: "",
        prevPageUrl: "",
        from: "",
        to: ""
      }
    };
  },
  computed: {
    sortOrders() {
      let sortOrders = {};

      this.columns.forEach((column) => {
        sortOrders[column.name] = -1;
      });

      return sortOrders;
    }
  },
  methods: {
    configPagination(data) {
      this.pagination.lastPage = data.last_page;
      this.pagination.currentPage = data.current_page;
      this.pagination.total = data.total;
      this.pagination.lastPageUrl = data.last_page_url;
      this.pagination.nextPageUrl = data.next_page_url;
      this.pagination.prevPageUrl = data.prev_page_url;
      this.pagination.from = data.from;
      this.pagination.to = data.to;
    },
    sortBy(key) {
      this.sortKey = key;
      this.sortOrders[key] = this.sortOrders[key] * -1;
      this.tableData.column = this.getIndex(this.columns, "name", key) - 1;
      this.tableData.dir = this.sortOrders[key] === 1 ? "asc" : "desc";
      this.getDatatable();
    },
    getIndex(array, key, value) {
      return array.findIndex((i) => i[key] == value);
    },
    searchDatatable: debounce(function (e) {
      this.tableData.search = e;
      this.getDatatable();
    }, 500),
    handleOnSelect(e) {
      this.tableData.length = e;
      this.getDatatable();
    }
  },
  mounted() {
    this.getDatatable();
  }
};
