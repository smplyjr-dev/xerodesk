/**
 * @package FiliPay Live Chat Entry File
 *
 * @author Alfredo Flores
 * @email alfredo@xerosoft.com
 */

// ======================================================== >>
// Place for plugins and libraries
// ======================================================== >>
import App from "@Scripts/views/App.vue";
import router from "@Scripts/router";
import store from "@Scripts/store";

// ======================================================== >>
// Require Directives and Filters
// ======================================================== >>
require("./components");
require("./directives");
// require("./filters");
require("./plugins");
require("./prototypes");

new Vue({
  ...App,
  router,
  store
});
