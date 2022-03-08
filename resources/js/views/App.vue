<template>
  <div id="xerodesk-portal">
    <loading ref="loading" :layout="layout" />

    <transition name="page" mode="out-in">
      <component :is="layout" v-if="layout" />
    </transition>

    <modal mId="unauth-modal" data-backdrop="static" data-keyboard="false">
      <modal-header :close="false">
        <p class="text-lg mb-0 font-weight-semi">Session Expired</p>
      </modal-header>
      <modal-body>Your session has expired. Please login again.</modal-body>
      <modal-footer>
        <a :href="redirectToLoginUrl" class="btn btn-brand-1">Okay, login again</a>
      </modal-footer>
    </modal>
  </div>
</template>

<script>
import Loading from "../components/neutral/Loading.vue";

// load layout components dymagically
const context = require.context("@Scripts/layouts", false, /.*\.vue$/);
const layouts = context
  .keys()
  .map((file) => [file.replace(/(^.\/)|(\.vue$)/g, ""), context(file)])
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
      let replaced = location.replace(window.Laravel.BASE_URL, "");

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
          .map(function (c) {
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
    // pusher init
    window.pusher = new Pusher(process.env.MIX_PUSHER_APP_KEY, {
      cluster: process.env.MIX_PUSHER_APP_CLUSTER,
      encrypted: true
    });

    this.$loading = this.$refs.loading;
  }
};
</script>
