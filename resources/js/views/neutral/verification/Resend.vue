<template>
  <div class="container">
    <div class="row">
      <div class="col-lg-8 m-auto">
        <div class="card">
          <div class="card-header">Resend Verification Link</div>
          <div class="card-body p-4">
            <form @submit.prevent="submitResend()">
              <div class="form-group" v-show="!$isEmpty($route.query.referrer)">
                <form-alert variant="warning">
                  <p>{{ $route.query.referrer_message }}</p>
                </form-alert>
              </div>
              <div class="form-group" v-show="!$isEmpty(resendMessage)">
                <form-alert variant="success">
                  <p v-for="message in resendMessage" :key="message" v-html="message"></p>
                </form-alert>
              </div>
              <div class="form-group" v-show="!$isEmpty(resendError)">
                <form-alert variant="warning">
                  <p v-for="error in resendError" :key="error" v-html="error"></p>
                </form-alert>
              </div>
              <div class="form-group row">
                <label for="email" class="col-sm-3 col-form-label">Email Address</label>
                <div class="col-sm-9">
                  <input type="email" class="form-control" v-model="email" />
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-3"></div>
                <div class="col-sm-9">
                  <button type="submit" class="btn btn-success" :disabled="isLoading">
                    <div v-if="isLoading" class="spinner-border spinner-border-sm" role="status"></div>
                    <span v-else>Resend</span>
                  </button>
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
export default {
  layout: "Neutral",
  name: "Resend",
  metaInfo: () => ({ title: "Dashboard" }),
  middleware: "guest",
  data: () => ({
    isLoading: false,
    resendError: [],
    resendMessage: [],
    email: ""
  }),
  methods: {
    resetForm() {
      this.email = "";
    },

    async submitResend() {
      this.isLoading = true;
      this.resendError = [];
      this.resendMessage = [];

      try {
        let { data } = await axios.post("/email/resend", {
          email: this.email
        });

        this.resetForm();
        this.resendMessage = data.status;
      } catch (error) {
        const errorObj = error.response.data.errors;

        for (const key in errorObj) {
          if (errorObj.hasOwnProperty(key)) {
            const error = errorObj[key];

            error.forEach(message => {
              this.resendError.push(message);
            });
          }
        }
      }

      this.isLoading = false;
    }
  },
  created() {
    if (this.$route.query.email) {
      this.email = this.$route.query.email;
    }
  }
};
</script>
