/**
 * @package Client Sessions Store
 *
 * @author Alfredo Flores
 * @email alfredo@xerosoft.com
 */

const namespaced = true;

const state = {
  sessions: [],
  session: {}
};

const getters = {};

const mutations = {
  SET_SESSIONS: (state, payload) => (state.sessions = payload),
  SET_SESSION: (state, payload) => (state.session = payload)
};

const actions = {
  async fetchSession({ commit }, session) {
    let include = "include=client.company,messages.attachments,taggables,user.bio";
    let { data } = await axios.get(`/portal/session/${session}?${include}`);

    commit("SET_SESSION", data);
  }
};

export default {
  namespaced,
  state,
  mutations,
  getters,
  actions
};
