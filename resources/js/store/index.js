Vue.use(Vuex);

import clients from "./modules/client/clients.js";
import messages from "./modules/client/messages.js";
import sessions from "./modules/client/sessions.js";

import auth from "./modules/auth.js";
import companies from "./modules/companies.js";
import groups from "./modules/groups.js";
import notifications from "./modules/notifications.js";
import roles from "./modules/roles.js";
import tags from "./modules/tags.js";

import slas from "./modules/slas.js";

export default new Vuex.Store({
  modules: {
    clients,
    messages,
    sessions,

    auth,
    companies,
    groups,
    notifications,
    roles,
    tags,

    slas
  }
});
