<template>
  <div class="container-fluid px-4">
    <div class="server-datatable">
      <div class="flex-center-between flex-wrap">
        <length @onSelect="handleOnSelect" />
        <search @onSearch="searchDatatable" :tableData="tableData" />
      </div>

      <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
        <tbody class="text-sm">
          <tr class="text-center" v-if="isLoading">
            <td colspan="8">
              <div class="spinner-border text-lg my-4" style="height: 5rem; width: 5rem"></div>
            </td>
          </tr>

          <tr class="text-sm hoverable cursor-pointer" v-else v-for="p in paginated" :key="p.id" @click="showClient(p)">
            <!-- Mobile View -->
            <td class="dt-mobile">
              <div class="dt-mobile-item">
                <div class="title">Client:</div>
                <div class="content">{{ p.name }}</div>
              </div>
              <div class="dt-mobile-item">
                <div class="title">Email Address:</div>
                <div class="content">{{ p.email }}</div>
              </div>
              <div class="dt-mobile-item">
                <div class="title">Token:</div>
                <div class="content">{{ p.token }}</div>
              </div>
              <div class="dt-mobile-item">
                <div class="title">Phone:</div>
                <div class="content">{{ p.phone || "--" }}</div>
              </div>
              <div class="dt-mobile-item">
                <div class="title">Timestamp:</div>
                <div class="content">{{ $dayjs("format", p.created_at, "MM/DD/YYYY hh:mm A") }}</div>
              </div>
            </td>

            <td>
              <div class="d-flex w-100" style="position: relative">
                <img class="object-cover mr-2 rounded-circle" src="/images/placeholder/profile.png" @error="$onImgError($event, 1)" alt="Profile Picture" height="40px" width="40px" />
                <div class="d-flex flex-column">
                  <span>{{ p.name }}</span>
                  <span class="text-muted text-xs">{{ p.email }}</span>
                </div>
              </div>
            </td>
            <td>{{ p.token }}</td>
            <td>{{ p.phone || "--" }}</td>
            <td>
              {{ $dayjs("format", p.created_at, "MM/DD/YYYY") }} <br />
              {{ $dayjs("format", p.created_at, "hh:mm A") }}
            </td>
          </tr>

          <tr v-if="!isLoading && !paginated.length">
            <td colspan="8">
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

    <drawer :toggle="isOpen" @toggleDrawer="isOpen = $event" position="right">
      <h4 class="mb-0">{{ client.name }}</h4>
      <p class="text-muted">{{ client.email }}</p>
      <br />

      <ul class="nav nav-tabs" id="myTab">
        <li class="nav-item">
          <a class="nav-link active" data-toggle="tab" href="#sessions">Sessions</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-toggle="tab" href="#attachments">Attachments</a>
        </li>
      </ul>
      <div class="tab-content p-4 border" style="margin-top: -1px">
        <div class="tab-pane fade show active" id="sessions">
          <ul class="list-unstyled mb-0">
            <li v-for="s in sessions" :key="s.id">
              <router-link :to="`tickets/${s.session}`">
                {{ s.session }} <span v-show="s.title"> - {{ s.title }}</span>
              </router-link>
            </li>
          </ul>
          <span v-if="$isEmpty(sessions)">No session found.</span>
        </div>
        <div class="tab-pane fade" id="attachments">
          <div class="grid-container">
            <div class="grid-item" v-for="m in filtered" :key="m.id" @click="onAttmClick(m)">
              <div class="grid-item--image" v-if="['ico', 'jpeg', 'jpg', 'png'].includes(getAttachment(m).extension.toLowerCase())">
                <img :src="`${$APP_URL}/storage/uploads/clients/${client.token}/${m.session}/${getAttachment(m).name}.${getAttachment(m).extension}`" />
              </div>
              <div class="grid-item--file" v-else>
                {{ `${getAttachment(m).name}.${getAttachment(m).extension}` }}
              </div>
            </div>
          </div>
          <span v-if="$isEmpty(filtered)">No attachment found.</span>
        </div>
      </div>
    </drawer>

    <transition name="fade">
      <div class="la-wrapper" v-if="enlargeToggle">
        <div class="la-content">
          <div class="la-close" @click="enlargeToggle = false">
            <InlineSvg name="svg/mdi/close.svg" color="#fff" />
          </div>
          <img class="la-image" :src="enlargeUrl" />
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import { Length, Search, Datatable, Entries, Pagination, Mixin } from "@SDT";

export default {
  layout: "Admin",
  mixins: [Mixin],
  components: { Length, Search, Datatable, Entries, Pagination },
  name: "Clients",
  metaInfo: () => ({ title: "Clients" }),
  middleware: ["auth", "permission:view_clients"],
  data() {
    let sortOrders = {};
    let types = ["string", "number", "date"];
    let columns = [
      { sortable: 0, hide: 0, type: types[0], width: "100%", name: "info", label: "Client Details" },
      { sortable: 1, hide: 0, type: types[0], width: "25%", name: "name", label: "Client" },
      { sortable: 0, hide: 0, type: types[0], width: "25%", name: "token", label: "Token" },
      { sortable: 0, hide: 0, type: types[0], width: "25%", name: "phone", label: "Phone" },
      { sortable: 1, hide: 0, type: types[2], width: "25%", name: "created_at", label: "Timestamp" }
    ];
    columns.forEach((column) => {
      sortOrders[column.name] = -1;
    });
    return {
      columns: columns,
      sortKey: "id",

      // custom data
      isLoading: false,
      isOpen: false,
      sessions: [],
      enlargeUrl: null,
      enlargeExtension: null,
      enlargeToggle: null
    };
  },
  computed: {
    client: {
      get() {
        return this.$store.state.clients.client;
      },
      set(newVal) {
        this.$store.state.clients.client = newVal;
      }
    },
    filtered() {
      let messages = [];

      this.sessions.forEach((session) => {
        session.messages.forEach((message) => {
          messages.push({ ...message, session: session.session });
        });
      });

      return messages.filter((m) => {
        return m.attachments.length > 0;
      });
    }
  },
  methods: {
    getDatatable(url = `/portal/client/datatable`) {
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
    async showClient(client) {
      this.isOpen = true;
      this.client = client;

      this.sessions = [];
      let { data } = await axios.get(`/portal/client/${client.token}/sessions`);
      this.sessions = data.sessions;
    },
    onAttmClick(message) {
      if (["ico", "jpeg", "jpg", "png"].includes(message.attachments[0].extension)) {
        let attachment = message.attachments[0];

        this.enlargeUrl = `${this.$APP_URL}/storage/uploads/clients/${this.client.token}/${message.session}/${attachment.name}.${attachment.extension}`;
        this.enlargeExtension = attachment.extension;
        this.enlargeToggle = true;
      }
    },
    getAttachment(message) {
      return message.attachments[0];
    }
  },
  created() {
    this.$emit("setTitle", "Clients");
    this.getDatatable();
  }
};
</script>

<style lang="scss" scoped>
@import "@Styles/pages/client.scss";
</style>
