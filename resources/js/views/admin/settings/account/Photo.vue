<template>
  <div class="container-fluid px-4">
    <div class="card">
      <div class="card-body">
        <h5 class="font-weight-bold mb-2">Profile Photo</h5>
        <p class="mb-4">You can change your avatar anytime.</p>

        <div class="card-picture-1">
          <div class="card-body">
            <div class="picture-container">
              <input type="file" class="d-none" ref="file" @change="onFileChange" />

              <img loading="lazy" class="object-cover" :src="$profilePicture(user)" @error="$onImgError($event, 1)" alt="Profile Photo" />

              <div class="loader" v-if="isPictureLoading">
                <div class="spinner-border text-light" style="height: 3rem; width: 3rem" role="status"></div>
              </div>

              <button type="button" @click="$refs.file.click()" v-else>
                <InlineSvg name="svg/mdi/camera.svg" size="2.5rem" />
              </button>
            </div>
            <div class="picture-detail">
              <h1 class="name">{{ `${name}` }}</h1>

              <div class="info">
                <div class="d-flex mb-2 mr-2">
                  <InlineSvg name="svg/mdi/shield-account.svg" class="mr-1" size="1.5rem" />
                  <span style="margin-top: 2.5px">{{ user.role }}</span>
                </div>
                <div class="d-flex mb-2 mr-2">
                  <InlineSvg name="svg/mdi/calendar.svg" class="mr-1" size="1.5rem" />
                  <span style="margin-top: 2.5px">Joined {{ $dayjs("format", user.created_at, "MMMM YYYY") }}</span>
                </div>
              </div>
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
  layout: "Account",
  name: "Picture",
  metaInfo: () => ({ title: "Account / Profile" }),
  middleware: ["auth"],
  data: () => ({
    isPictureLoading: false,
    userIcon: {
      extension: "",
      file: "",
      name: "",
      size: ""
    }
  }),
  computed: {
    ...mapState("auth", ["user"]),

    name() {
      return `${this.user.bio.first_name} ${this.user.bio.last_name}`;
    }
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
        this.userIcon.file = e.target.result;
      };

      this.userIcon.extension = file.name.split(".").pop();
      this.userIcon.size = file.size;
      this.userIcon.name = file.name.split(".").slice(0, -1).join(".");

      setTimeout(() => {
        this.uploadPicture();
      }, 200);
    },

    async uploadPicture() {
      let params = {
        id: this.user.id,
        image: this.userIcon.file,
        extension: this.userIcon.extension,
        name: this.userIcon.name,
        old: this.user.profile_picture,
        size: this.userIcon.size
      };

      try {
        await axios.post(`/portal/user/picture`, params);
        await this.$store.dispatch("auth/fetchUser");

        this.$store.dispatch("notifications/addNotification", {
          variant: "bg-success",
          icon: "fa-check-circle",
          title: "Success!",
          body: `<span class="font-weight-semi">${this.name}</span> profile photo has been successfully updated.`
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
          icon: "fa-exclamation-triangle",
          title: "Heads up!",
          body: pictureError.join("<br />")
        });
      }

      this.isPictureLoading = false;
    }
  }
};
</script>
