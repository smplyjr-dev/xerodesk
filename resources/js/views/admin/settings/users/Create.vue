<template>
  <div class="container-fluid">
    <h4 class="font-weight-normal mb-4">Create User</h4>

    <div class="card card-picture" style="background-image: url('/images/template/bg-profile.jpg')">
      <div class="card-body">
        <div class="card-picture-container">
          <input type="file" class="d-none" ref="file" @change="onFileChange" />

          <img loading="lazy" class="object-cover" :src="profilePicture" alt="Profile Picture" />

          <div class="loader" v-if="isPictureLoading">
            <div class="spinner-border text-light" style="height: 3rem; width: 3rem;" role="status"></div>
          </div>

          <button @click="$refs.file.click()" v-else>
            <InlineSvg name="svg/camera.svg" size="2.5rem" />
          </button>
        </div>

        <div class="text-white">
          <p class="font-weight-bold mb-2 text-center text-lg">{{ `${user.bio.last_name.toTitle()}, ${user.bio.first_name.toTitle()}` }}</p>

          <div class="flex-center flex-wrap">
            <div class="flex-center mr-2">
              <InlineSvg name="template/mdi-shield-account.svg" class="mr-1" size="20px" />
              <span style="margin-top: 2.5px;">{{ user.role }}</span>
            </div>
            <div class="flex-center mr-2">
              <InlineSvg name="template/mdi-calendar.svg" class="mr-1" size="20px" />
              <span style="margin-top: 2.5px;">Joined {{ $dayjs("format", user.created_at, "MMMM YYYY") }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-4">
        <div class="card">
          <div class="card-body">
            <form @submit.prevent="updateProfile()">
              <h5 class="mb-4">Create User</h5>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group" v-show="!$isEmpty(profileError)">
                    <form-alert variant="warning">
                      <p v-for="error in profileError" :key="error" v-html="error"></p>
                    </form-alert>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="fname">First Name <span class="text-danger">*</span></label>
                    <input id="fname" type="text" class="form-control" v-model="user.bio.first_name" />
                  </div>
                  <div class="form-group">
                    <label for="lname">Last Name <span class="text-danger">*</span></label>
                    <input id="lname" type="text" class="form-control" v-model="user.bio.last_name" />
                  </div>
                  <div class="form-group">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input id="email" type="text" class="form-control" v-model="user.email" />
                  </div>
                  <div class="form-group">
                    <label for="uname">Username <span class="text-danger">*</span></label>
                    <input id="uname" type="text" class="form-control" v-model="user.username" />
                  </div>
                  <div class="form-group">
                    <label for="dob">Date of Birth</label>
                    <a-date-picker v-model="user.bio.dob" />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="role" class="text-sm">Role <span class="text-danger">*</span></label>
                    <select id="role" class="custom-select" v-model="user.role">
                      <option selected disabled value>-- Choose Role --</option>
                      <option v-for="role in roles" :key="role.id">{{ role.name }}</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="status">Status <span class="text-danger">*</span></label>
                    <select class="form-control" v-model="user.status">
                      <option :value="true">Active</option>
                      <option :value="false">Inactive</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="facebook">Facebook</label>
                    <input id="facebook" type="text" class="form-control" v-model="user.bio.facebook" />
                  </div>
                  <div class="form-group">
                    <label for="twitter">Twitter</label>
                    <input id="twitter" type="text" class="form-control" v-model="user.bio.twitter" />
                  </div>
                  <div class="form-group">
                    <label for="linkedin">LinkedIn</label>
                    <input id="linkedin" type="text" class="form-control" v-model="user.bio.linkedin" />
                  </div>
                  <div class="text-right mt-4">
                    <button type="submit" class="btn btn-primary" :disabled="isProfileLoading">
                      <div v-if="isProfileLoading" class="spinner-border spinner-border-sm" role="status"></div>
                      <span v-else>Create User</span>
                    </button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-6 mb-4">
        <div class="card">
          <div class="card-body">
            <form @submit.prevent="updatePassword()">
              <h5 class="mb-4">Change Password</h5>

              <div class="row">
                <div class="col">
                  <div class="form-group" v-show="!$isEmpty(passwordError)">
                    <form-alert variant="warning">
                      <p v-for="error in passwordError" :key="error" v-html="error"></p>
                    </form-alert>
                  </div>
                  <div class="form-group">
                    <label for="">Old Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" v-model="old_password" />
                  </div>
                  <div class="form-group">
                    <label for="">New Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" v-model="new_password" />
                  </div>
                  <div class="form-group">
                    <label for="">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" class="form-control" v-model="new_password_confirmation" />
                  </div>
                  <div class="text-right mt-4">
                    <button type="submit" class="btn btn-primary" :disabled="isPasswordLoading">
                      <div v-if="isPasswordLoading" class="spinner-border spinner-border-sm" role="status"></div>
                      <span v-else>Change Password</span>
                    </button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import ADatePicker from "@Components/neutral/ADatePicker.vue";

export default {
  layout: "Admin",
  name: "SettingCreateUser",
  metaInfo: () => ({ title: "Setting / Create User" }),
  middleware: ["auth"],
  components: { ADatePicker },
  computed: {
    ...mapState("roles", ["roles"]),

    profilePicture() {
      if (this.user.profile_picture == "generic-profile.png") {
        return `${this.$APP_URL}/images/${this.user.profile_picture}`;
      } else {
        return `${this.$APP_URL}/storage/uploads/users/${this.user.id}/${this.user.profile_picture}`;
      }
    },
    name() {
      return `${this.user.bio.last_name}, ${this.user.bio.first_name}`;
    }
  },
  data: () => ({
    isPictureLoading: false,
    isProfileLoading: false,
    profileError: [],
    isPasswordLoading: false,
    passwordError: [],
    old_password: "",
    new_password: "",
    new_password_confirmation: "",
    userIcon: {
      extension: "",
      file: "",
      name: "",
      size: ""
    },
    user: {
      email: "",
      old_password: "",
      new_password: "",
      new_password_confirmation: "",
      profile_picture: "generic-profile.png",
      status: "",
      role: "Role",
      bio: {
        first_name: "First",
        last_name: "Last",
        facebook: "",
        twiiter: "",
        linkedin: ""
      }
    }
  }),
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

      reader.onload = e => {
        this.userIcon.file = e.target.result;
      };

      this.userIcon.extension = file.name.split(".").pop();
      this.userIcon.size = file.size;
      this.userIcon.name = file.name
        .split(".")
        .slice(0, -1)
        .join(".");

      setTimeout(() => {
        this.uploadPicture();
      }, 200);
    },

    async fetchUser() {
      let id = 1;
      let { data } = await axios.get(`/users/${id}`);

      this.user = data;
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
        let { data } = await axios.post("/users/picture", params);

        await this.fetchUser();

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

            error.forEach(message => {
              pictureError.push(message);
            });
          }
        }

        this.$store.dispatch("notifications/addNotification", {
          variant: "bg-danger",
          icon: "fa-times",
          title: "Invalid.",
          body: pictureError.join("<br />")
        });
      }

      this.isPictureLoading = false;
    },

    async updateProfile() {
      this.isProfileLoading = true;
      this.profileError = [];

      try {
        let user = {
          method: "profile",
          status: this.user.status,
          role: this.user.role,
          username: this.user.username,
          email: this.user.email,
          first_name: this.user.bio.first_name,
          last_name: this.user.bio.last_name,
          dob: this.user.bio.dob,
          facebook: this.user.bio.facebook,
          twitter: this.user.bio.twitter,
          linkedin: this.user.bio.linkedin
        };

        let { data } = await axios.put(`/users/${this.user.id}`, user);

        this.$store.dispatch("notifications/addNotification", {
          variant: "bg-success",
          icon: "fa-check",
          title: "Success!",
          body: `<span class="font-weight-semi">${this.name}</span> profile has been successfully updated.`
        });

        this.fetchUser();
      } catch (error) {
        const errorObj = error.response.data.errors;

        for (const key in errorObj) {
          if (errorObj.hasOwnProperty(key)) {
            const error = errorObj[key];

            error.forEach(message => {
              this.profileError.push(message);
            });
          }
        }
      }

      this.isProfileLoading = false;
    },

    async updatePassword() {
      this.isPasswordLoading = true;
      this.passwordError = [];

      try {
        let { data } = await axios.put(`/users/${this.user.id}`, {
          method: "password",
          old_password: this.old_password,
          new_password: this.new_password,
          new_password_confirmation: this.new_password_confirmation
        });

        this.$store.dispatch("notifications/addNotification", {
          variant: "bg-success",
          icon: "fa-check",
          title: "Success!",
          body: "Your password has been successfully changed."
        });

        this.fetchUser();
      } catch (error) {
        const errorObj = error.response.data.errors;

        for (const key in errorObj) {
          if (errorObj.hasOwnProperty(key)) {
            const error = errorObj[key];

            error.forEach(message => {
              this.passwordError.push(message);
            });
          }
        }
      }

      this.isPasswordLoading = false;
    }
  },
  created() {
    // this.fetchUser();
  }
};
</script>
