<template>
  <div class="contact">
    <div class="contact-search">
      <input type="text" class="form-control" placeholder="Search" />
    </div>
    <div class="contact-list">
      <ul class="list">
        <li class="list--item" v-for="(i, $i) in paginated" :key="$i">
          <router-link class="list--item-link" :to="`/chats/?cId=${i.id}`">
            <div class="list--item-link--detail">
              <div class="name">{{ i.client }}</div>
              <div class="message" v-html="i.messages[i.messages.length - 1].message"></div>
              <div class="date">{{ $dayjs("fromNow", i.messages[i.messages.length - 1].created_at, "MM/DD/YYYY") }}</div>
            </div>
          </router-link>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  data: () => ({
    paginated: [],
    tableData: {
      draw: 0,
      length: 10,
      search: "",
      column: 0,
      dir: "desc"
    },

    contacts: [
      {
        id: 1,
        message: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Sit, molestiae.",
        name: "Unknown Customer",
        date: "11/09/2021"
      },
      {
        id: 2,
        message: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Sit, molestiae.",
        name: "Unknown Customer",
        date: "11/09/2021"
      },
      {
        id: 3,
        message: "Lorem ipsum dolor sit amet consectetur adipisicing elit. Sit, molestiae.",
        name: "Unknown Customer",
        date: "11/09/2021"
      }
    ]
  }),
  methods: {
    getDatatable(url = `/portal/session/datatable`) {
      // this.isLoading = true;
      this.tableData.draw++;

      axios
        .get(url, { params: this.tableData })
        .then(response => {
          let data = response.data;
          if (this.tableData.draw == data.draw) {
            this.paginated = data.data.data;
          }
        })
        .catch(errors => {
          console.log(errors);
        });
    }
  },
  created() {
    this.getDatatable();
  }
};
</script>
