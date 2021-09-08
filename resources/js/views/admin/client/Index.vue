<template>
  <div class="container-fluid">
    <div class="page-title">
      <h4 class="mb-2">Clients</h4>
      &nbsp;
    </div>

    <div class="card card-1">
      <div class="card-body">
        <div class="server-datatable">
          <search @onSelect="handleOnSelect" @onSearch="searchDatatable($event)" />

          <datatable :columns="columns" :sortKey="sortKey" :sortOrders="sortOrders" @sort="sortBy">
            <tbody class="text-sm">
              <tr class="text-center" v-if="isLoading">
                <td colspan="8">
                  <div class="spinner-border text-lg my-4" style="height: 5rem; width: 5rem;"></div>
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
                    <div class="title">Phone:</div>
                    <div class="content">{{ p.phone || "- -" }}</div>
                  </div>
                  <div class="dt-mobile-item">
                    <div class="title">Token:</div>
                    <div class="content">{{ p.token }}</div>
                  </div>
                </td>

                <td>
                  <div class="d-flex align-items-center">
                    <!-- <img class="object-cover mr-2" :src="p.picture" v-fallback="`/images/generic-profile.png`" alt="Profile Picture" height="100%" width="30px" /> -->
                    <span>{{ p.name || "--" }}</span>
                  </div>
                </td>
                <td>{{ p.email || "- -" }}</td>
                <td>{{ p.phone || "- -" }}</td>
                <td>{{ p.token }}</td>
                <td>{{ $dayjs("format", p.created_at, "MM/DD/YYYY h:mm A") }}</td>
              </tr>

              <tr v-if="!isLoading && !paginated.length">
                <td colspan="8">
                  <div class="w-100 my-3 flex-center flex-column">No result found.</div>
                </td>
              </tr>
            </tbody>
          </datatable>

          <pagination :pagination="pagination" @prev="getDatatable(pagination.prevPageUrl)" @next="getDatatable(pagination.nextPageUrl)" />
        </div>
      </div>
    </div>

    <transition name="fade">
      <div class="client-backdrop" v-show="isOpen" @click.self="isOpen = false">
        <transition name="right">
          <div class="client-container" v-show="isOpen" v-if="!$isEmpty(client)">
            <div class="client-close" @click="isOpen = false">
              <InlineSvg name="template/mdi-close.svg" color="#c6c6c6" size="100%" />
            </div>

            <h4 class="mb-0">{{ client.name }}</h4>
            <p class="text-muted">{{ client.email }}</p>
            <br />

            <ul class="nav nav-tabs" id="myTab">
              <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#sessions">Sessions</a>
              </li>
              <li class="nav-item">
                <a class="nav-link " data-toggle="tab" href="#attachments">Attachments</a>
              </li>
            </ul>
            <div class="tab-content p-4 border" style="margin-top: -1px;">
              <div class="tab-pane fade show active" id="sessions">
                <ul class="list-unstyled mb-0">
                  <li v-for="s in sessions" :key="s.id">
                    <router-link :to="`tickets/${s.session}`">
                      {{ s.title || s.session }}
                    </router-link>
                  </li>
                </ul>
                <span v-if="$isEmpty(sessions)">No session found.</span>
              </div>
              <div class="tab-pane fade " id="attachments">
                <div class="grid-container">
                  <div class="grid-item" v-for="m in filteredMessages" :key="m.id" @click="onAttmClick(m)">
                    <div class="grid-item--image" v-if="['ico', 'jpeg', 'jpg', 'png'].includes(getAttachment(m).extension)">
                      <img :src="`${$APP_URL}/storage/uploads/clients/${client.token}/${m.session}/${getAttachment(m).name}.${getAttachment(m).extension}`" />
                    </div>
                    <div class="grid-item--file" v-else>
                      {{ `${getAttachment(m).name}.${getAttachment(m).extension}` }}
                    </div>
                  </div>
                </div>
                <span v-if="$isEmpty(filteredMessages)">No attachment found.</span>
              </div>
            </div>
          </div>
        </transition>
      </div>
    </transition>

    <transition name="fade">
      <div class="large-attm" style="color: white;" v-if="enlargeToggle" @click.self="enlargeToggle = false">
        <img :src="enlargeUrl" v-if="['ico', 'jpeg', 'jpg', 'png'].includes(enlargeExtension)" />
      </div>
    </transition>
  </div>
</template>

<script>
import { Search, Datatable, Pagination, Mixin } from "@SDT";

export default {
  layout: "Admin",
  mixins: [Mixin],
  components: { Search, Datatable, Pagination },
  name: "Clients",
  metaInfo: () => ({ title: "Clients" }),
  middleware: ["auth", "permission:view_clients"],
  data() {
    let sortOrders = {};
    let types = ["string", "number", "date"];
    let columns = [
      { sortable: 0, hide: 0, type: types[0], width: "100%", name: "info", label: "Client Details" },
      { sortable: 1, hide: 0, type: types[0], width: "20%", name: "name", label: "Client" },
      { sortable: 1, hide: 0, type: types[0], width: "20%", name: "email", label: "Email Address" },
      { sortable: 0, hide: 0, type: types[0], width: "20%", name: "phone", label: "Phone" },
      { sortable: 0, hide: 0, type: types[0], width: "20%", name: "token", label: "Token" },
      { sortable: 1, hide: 0, type: types[2], width: "20%", name: "created_at", label: "Timestamp" }
    ];
    columns.forEach(column => {
      sortOrders[column.name] = -1;
    });
    return {
      columns: columns,
      sortKey: "id",
      sortOrders: sortOrders,

      // custom data
      isLoading: false,
      isOpen: false,
      isOpening: false,
      client: {},
      sessions: [],
      enlargeUrl: null,
      enlargeExtension: null,
      enlargeToggle: null
    };
  },
  computed: {
    filteredMessages() {
      let messages = [];

      this.sessions.forEach(session => {
        session.messages.forEach(message => {
          messages.push({ ...message, session: session.session });
        });
      });

      return messages.filter(m => {
        return m.attachments.length > 0;
      });
    }
  },
  methods: {
    getDatatable(url = `/client/datatable`) {
      this.isLoading = true;
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
    getIcon(ext) {
      // documents
      if (["doc", "docx", "htm", "html", "pdf", "txt", "pptx"].includes(ext)) return "document-text";

      // spread sheet
      if (["csv", "xls", "xlsx"].includes(ext)) return "document-report";

      // images
      if (["gif", "ico", "jpeg", "jpg", "png"].includes(ext)) return "photograph";

      // compressed
      if (["rar", "zip", "7z"].includes(ext)) return "folder";

      // video
      if (["mp4", "mov", "avi", "flv", "mkv", "wmv", "webm"].includes(ext)) return "video-camera";
    },
    async showClient(client) {
      this.isOpen = true;
      this.client = client;

      this.sessions = [];
      let { data } = await axios.get(`/client/${client.token}/sessions`);
      this.sessions = data.sessions;
    },
    onAttmClick(message) {
      let attachment = message.attachments[0];

      this.enlargeUrl = `${this.$APP_URL}/storage/uploads/clients/${this.client.token}/${message.session}/${attachment.name}.${attachment.extension}`;
      this.enlargeExtension = attachment.extension;
      this.enlargeToggle = true;
    },
    getAttachment(message) {
      return message.attachments[0];
    }
  }
};
</script>

<style lang="scss" scoped>
@import "@Styles/pages/client.scss";
</style>
