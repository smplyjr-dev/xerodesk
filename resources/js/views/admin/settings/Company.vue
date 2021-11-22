<template>
  <div class="col-md-9">
    <div class="card card-1">
      <div class="card-body">
        <form @submit.prevent="updateCompany()">
          <div class="form-group">
            <label for="name">Name:</label>
            <input id="name" type="text" class="form-control" v-model="company.name" />
          </div>
          <div class="form-group">
            <label for="address">Address:</label>
            <input id="name" type="text" class="form-control" v-model="company.address" />
          </div>
          <div class="form-group">
            <label for="url">URL:</label>
            <input id="name" type="text" class="form-control" v-model="company.url" />
          </div>
          <div class="form-group">
            <label for="abbr">Abbreviation:</label>
            <input id="name" type="text" class="form-control" v-model="company.abbr" disabled />
          </div>
          <div class="form-group mb-0">
            <!-- <button type="button" class="btn btn-light">Close</button> -->
            <button type="submit" class="btn btn-primary" :disabled="isUpdating">
              <div v-if="isUpdating" class="spinner-border spinner-border-sm" role="status"></div>
              <span v-else>Submit</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import SettingMeta from "@Components/admin/settings/SettingMeta.vue";

export default {
  layout: "Settings",
  name: "SettingCompany",
  metaInfo: () => ({ title: "Setting / Company" }),
  middleware: ["auth", "permission:view_company"],
  components: { SettingMeta },
  data: () => ({
    company: {},
    isUpdating: false
  }),
  computed: {
    ...mapState("auth", ["user"])
  },
  methods: {
    fetchCompany() {
      axios.get(`/portal/company/${this.user.company_id}`).then(res => {
        this.company = res.data;
      });
    },
    updateCompany() {
      axios.put(`/portal/company/${this.user.company_id}`, this.company).then(res => {
        this.company = res.data;

        this.$store.dispatch("notifications/addNotification", {
          variant: "bg-success",
          icon: "fa-check",
          title: "Success!",
          body: "The company has been successfully updated."
        });
      });
    }
  },
  created() {
    this.fetchCompany();
  },
  mounted() {
    this.$emit("meta", {
      title: "Company",
      description: "You may edit your company details in here."
    });
  }
};
</script>
