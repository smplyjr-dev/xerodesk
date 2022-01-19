/**
 * @package Clients Store
 * @author Alfredo Flores
 * @email alfredo@xerosoft.com
 */

const namespaced = true;

const state = {
  clientsAreLoading: false,
  clients: [],
  client: {}
};

const getters = {};

const mutations = {
  SET_LOADING: (state, status) => (state.clientsAreLoading = status),

  SET_CLIENT: (state, client) => (state.client = client),

  SET_CLIENTS: (state, clients) => (state.clients = clients),

  PUSH_CLIENT: (state, client) => state.clients.push(client),

  PUSH_MESSAGE: (state, params) => {
    let { message, sender } = params;
    let clients_id = state.clients.map((c) => c.id);
    let client_key = clients_id.indexOf(params.client_id);

    state.clients[client_key].sessions[state.clients[client_key].sessions.length - 1].messages.push({
      message,
      sender
    });
  }
};

const actions = {
  async fetchClients({ commit }) {
    let { data } = await axios.get(`/portal/client`);

    commit("SET_CLIENTS", data);
  }
};

export default {
  namespaced,
  state,
  mutations,
  getters,
  actions
};
