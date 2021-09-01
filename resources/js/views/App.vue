<template>
  <div id="xerodesk-portal">
    <loading ref="loading" :layout="layout" />
    <component :is="layout" v-if="layout" />

    <div id="unauth-modal" class="modal fade" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <p class="text-lg mb-0 font-weight-semi">Session Expired</p>
          </div>
          <div class="modal-body">
            Your session has expired. Please login again.
          </div>
          <div class="modal-footer">
            <a :href="redirectToLoginUrl" class="btn btn-primary">Okay, login again</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Loading from "../components/neutral/Loading.vue";

// load layout components dymagically
const context = require.context("@Scripts/layouts", false, /.*\.vue$/);
const layouts = context
  .keys()
  .map(file => [file.replace(/(^.\/)|(\.vue$)/g, ""), context(file)])
  .reduce((components, [name, component]) => {
    components[name] = component.default || component;
    return components;
  }, {});

export default {
  el: "#xerodesk-portal",
  data: () => ({
    layout: null,
    redirectToLoginUrl: ""
  }),
  components: { Loading },
  methods: {
    setLayout(layout) {
      if (!layout || !layouts[layout]) {
        layout = "Default";
      }

      this.layout = layouts[layout];
    },
    showUnauthModal() {
      let location = window.location.href;
      let replaced = location.replace(process.env.MIX_APP_URL, "");

      // update the url
      this.redirectToLoginUrl = replaced;

      // display the modal
      $("#unauth-modal").modal("show");
    },
    assertAlive(token) {
      const now = Date.now().valueOf() / 1000;
      const decoded = this.parseToken(token);

      if (typeof decoded.exp !== "undefined" && decoded.exp < now) {
        throw new Error(`Token expired: ${JSON.stringify(decoded)}`);
      }

      if (typeof decoded.nbf !== "undefined" && decoded.nbf > now) {
        throw new Error(`Token not yet valid: ${JSON.stringify(decoded)}`);
      }
    },
    parseToken(token) {
      let base64Url = token.split(".")[1];
      let base64 = base64Url.replace(/-/g, "+").replace(/_/g, "/");
      let jsonPayload = decodeURIComponent(
        atob(base64)
          .split("")
          .map(function(c) {
            return "%" + ("00" + c.charCodeAt(0).toString(16)).slice(-2);
          })
          .join("")
      );

      return JSON.parse(jsonPayload);
    },
    verifyToken() {
      let token = localStorage.getItem("payload");

      if (token) {
        try {
          this.assertAlive(token);
        } catch (error) {}
      }
    }
  },
  mounted() {
    this.$loading = this.$refs.loading;
  }
};
</script>
