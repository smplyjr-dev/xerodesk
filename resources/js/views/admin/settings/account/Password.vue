<template>
  <div class="container-fluid mt-4 px-4">
    <div class="card">
      <form @submit.prevent="updatePassword()">
        <div class="card-body">
          <h5 class="font-weight-bold mb-2">Password Update</h5>
          <p class="mb-4">It's a good habit to change your password once in a while.</p>

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
            </div>
          </div>
        </div>
        <div class="card-footer">
          <div class="text-right">
            <button type="submit" class="btn btn-brand-1" :disabled="isPasswordLoading">
              <div v-if="isPasswordLoading" class="spinner-border spinner-border-sm" role="status"></div>
              <span v-else>Change Password</span>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  layout: "Account",
  name: "AccountPassword",
  metaInfo: () => ({ title: "Account / Password" }),
  middleware: ["auth"],
  data: () => ({
    isPasswordLoading: false,
    passwordError: [],
    old_password: "",
    new_password: "",
    new_password_confirmation: ""
  }),
  computed: {
    ...mapState("auth", ["user"])
  },
  methods: {
    async updatePassword() {
      this.isPasswordLoading = true;
      this.passwordError = [];

      try {
        let { data } = await axios.put(`/portal/user/${this.user.id}`, {
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
      } catch (error) {
        const errorObj = error.response.data.errors;

        for (const key in errorObj) {
          if (errorObj.hasOwnProperty(key)) {
            const error = errorObj[key];

            error.forEach((message) => {
              this.passwordError.push(message);
            });
          }
        }
      }

      this.isPasswordLoading = false;
    }
  }
};
</script>
