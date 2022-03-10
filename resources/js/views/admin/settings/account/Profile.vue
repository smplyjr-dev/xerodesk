<template>
  <!-- <div class="">
    <div class="banner"></div>

    <div class="container">
      <div class="card">
        <div class="card-body">
          <div class="account-page">
            <div class="aside">
              <h4 class="font-weigt-semi">Account Settings</h4>
              <p class="text-muted">You can manage your personal details in this section.</p>

              <div class="nav">
                <router-link class="nav-link flex-center-between" to="/settings/account/profile">
                  <span>Profile Information</span>
                  <i class="fas fa-chevron-right"></i>
                </router-link>
                <router-link class="nav-link flex-center-between" to="/settings/account/password">
                  <span>Password</span>
                  <i class="fas fa-chevron-right"></i>
                </router-link>
              </div>

              <h4 class="font-weigt-semi">Live Chat</h4>
              <p class="text-muted">This settings is intended to help you boost your productivity.</p>

              <div class="nav">
                <router-link class="nav-link flex-center-between" to="/settings/account/replies">
                  <span>Saved Replies</span>
                  <i class="fas fa-chevron-right"></i>
                </router-link>
              </div>
            </div>
            <div class="main">
              <h1>Some content...</h1>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div> -->

  <div class="container-fluid my-4 px-4">
    <div class="card">
      <form @submit.prevent="updateProfile()">
        <div class="card-body">
          <h5 class="font-weight-bold mb-2">Profile Information</h5>
          <p class="mb-4">Feel free to update any of your personal details down below.</p>

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
                <label for="dob">Date of Birth</label>
                <ADatePicker v-model="user.bio.dob" />
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="email">Email <span class="text-danger">*</span></label>
                <input id="email" type="text" class="form-control" v-model="user.email" />
              </div>
              <div class="form-group">
                <label for="uname">Username <span class="text-danger">*</span></label>
                <input id="uname" type="text" class="form-control" v-model="user.username" />
              </div>
            </div>
          </div>

          <hr />

          <h5 class="font-weight-bold mb-2">Social Media</h5>
          <p class="mb-4">Here, you can optionally link your social media profiles.</p>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="facebook">Facebook</label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend" style="z-index: 1">
                    <span class="input-group-text">https://facebook.com/</span>
                  </div>
                  <input id="facebook" type="text" class="form-control" v-model="user.bio.facebook" />
                </div>
              </div>
              <div class="form-group">
                <label for="twitter">Twitter</label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend" style="z-index: 1">
                    <span class="input-group-text">https://twitter.com/</span>
                  </div>
                  <input id="twitter" type="text" class="form-control" v-model="user.bio.twitter" />
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="linkedin">LinkedIn</label>
                <div class="input-group mb-3">
                  <div class="input-group-prepend" style="z-index: 1">
                    <span class="input-group-text">https://linkedin.com/</span>
                  </div>
                  <input id="linkedin" type="text" class="form-control" v-model="user.bio.linkedin" />
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-footer text-right">
          <button type="submit" class="btn btn-brand-1" :disabled="isProfileLoading">
            <div v-if="isProfileLoading" class="spinner-border spinner-border-sm" role="status"></div>
            <span v-else>Update Profile</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import ADatePicker from "@Components/neutral/ADatePicker.vue";

export default {
  layout: "Account",
  name: "Profile",
  metaInfo: () => ({ title: "Account / Profile" }),
  middleware: ["auth"],
  components: { ADatePicker },
  data: () => ({
    isProfileLoading: false,
    profileError: []
  }),
  computed: {
    ...mapState("auth", ["user"]),
    ...mapState("roles", ["roles"]),

    name() {
      return `${this.user.bio.last_name}, ${this.user.bio.first_name}`;
    }
  },
  methods: {
    async updateProfile() {
      this.isProfileLoading = true;
      this.profileError = [];

      try {
        let { data } = await axios.put(`/portal/user/${this.user.id}`, {
          method: "profile",
          role: this.user.role,
          status: this.user.status,
          username: this.user.username,
          email: this.user.email,
          first_name: this.user.bio.first_name,
          last_name: this.user.bio.last_name,
          dob: this.user.bio.dob,
          facebook: this.user.bio.facebook,
          twitter: this.user.bio.twitter,
          linkedin: this.user.bio.linkedin
        });

        this.$store.dispatch("notifications/addNotification", {
          variant: "bg-success",
          icon: "fa-check",
          title: "Success!",
          body: `<span class="font-weight-semi">${this.name}</span> profile has been successfully updated.`
        });

        this.$store.dispatch("auth/fetchUser");
      } catch (error) {
        const errorObj = error.response.data.errors;

        for (const key in errorObj) {
          if (errorObj.hasOwnProperty(key)) {
            const error = errorObj[key];

            error.forEach((message) => {
              this.profileError.push(message);
            });
          }
        }
      }

      this.isProfileLoading = false;
    }
  }
};
</script>

<style lang="scss" scoped>
.banner {
  min-height: 300px;
  background: #2c85b9;
}

.container {
  margin-top: -200px;
}
</style>
