<template>
  <div class="container-fluid px-4">
    <div class="row">
      <div class="col-sm-8">
        <div class="card">
          <form @submit.prevent="updateCompany()">
            <div class="card-body">
              <div class="form-group">
                <label for="name">Name:</label>
                <input id="name" type="text" class="form-control" v-model="company.name" />
              </div>
              <div class="form-group">
                <label for="url">URL:</label>
                <input id="name" type="text" class="form-control" v-model="company.url" />
              </div>
              <div class="form-group">
                <label for="abbr">Abbreviation:</label>
                <input id="name" type="text" class="form-control" v-model="company.abbr" disabled />
              </div>
            </div>
            <div class="card-footer text-right">
              <button type="submit" class="btn btn-brand-1" :disabled="isUpdating">
                <div v-if="isUpdating" class="spinner-border spinner-border-sm" role="status"></div>
                <span v-else>Submit</span>
              </button>
            </div>
          </form>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="card">
          <div class="card-body">
            <p class="text-xl mb-4">Logo Upload</p>
            <!-- <p>Upload a PNG version of your company logo. Learn more about chosing the right logo image.</p> -->

            <div class="card card-picture-2">
              <div class="card-body">
                <img loading="lazy" class="object-cover" :src="`${$APP_URL}/storage/uploads/companies/${company.id}/${company.company_picture}`" @error="$onImgError($event, 2)" alt="Profile Picture" height="auto" width="100%" />
              </div>
            </div>

            <div class="text-center mt-3">
              <input type="file" class="d-none" ref="file" @change="onFileChange" />
              <button type="button" class="btn btn-link" @click="$refs.file.click()">Upload your logo</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  layout: "Admin",
  name: "SettingCompany",
  metaInfo: () => ({ title: "Setting / Company" }),
  middleware: ["auth", "permission:view_company"],
  data: () => ({
    company: {},
    companyIcon: {
      extension: "",
      file: "",
      name: "",
      size: ""
    },
    isUpdating: false
  }),
  computed: {
    ...mapState("auth", ["user"])
  },
  methods: {
    onFileChange(e) {
      this.isPictureLoading = true;

      let files = e.target.files || e.dataTransfer.files;

      if (!files.length) return;

      this.createImage(files[0]);
    },
    createImage(file) {
      let reader = new FileReader();

      reader.readAsDataURL(file);

      reader.onload = (e) => {
        this.companyIcon.file = e.target.result;
      };

      this.companyIcon.extension = file.name.split(".").pop();
      this.companyIcon.size = file.size;
      this.companyIcon.name = file.name.split(".").slice(0, -1).join(".");

      setTimeout(() => {
        this.uploadPicture();
      }, 200);
    },
    async uploadPicture() {
      let params = {
        id: this.company.id,
        old: this.company.company_picture,
        image: this.companyIcon.file,
        extension: this.companyIcon.extension,
        name: this.companyIcon.name,
        size: this.companyIcon.size
      };

      try {
        await axios.post(`/portal/company/picture`, params);
        await this.fetchCompany();

        this.$store.dispatch("notifications/addNotification", {
          variant: "bg-success",
          icon: "fa-check",
          title: "Success!",
          body: `<span class="font-weight-semi">${this.name}</span> profile picture has been successfully updated.`
        });
      } catch (error) {
        let errorObj = error.response.data.errors;
        let pictureError = [];

        for (const key in errorObj) {
          if (errorObj.hasOwnProperty(key)) {
            const error = errorObj[key];

            error.forEach((message) => {
              pictureError.push(message);
            });
          }
        }

        this.$store.dispatch("notifications/addNotification", {
          variant: "bg-danger",
          icon: "fa-times",
          title: "Alert!",
          body: pictureError.join("<br />")
        });
      }

      this.isPictureLoading = false;
    },
    fetchCompany() {
      axios.get(`/portal/company/${this.user.company_id}`).then((res) => {
        this.company = res.data;
      });
    },
    updateCompany() {
      axios.put(`/portal/company/${this.user.company_id}`, this.company).then((res) => {
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
    this.$emit("setTitle", "Company");
    this.fetchCompany();
  }
};
</script>
