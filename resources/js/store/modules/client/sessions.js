/**
 * @package Client Sessions Store
 *
 * @author Alfredo Flores
 * @email alfredo@xerosoft.com
 */

const namespaced = true;

const state = {
  session: {}
};

const getters = {};

const mutations = {
  SET_SESSION: (state, payload) => (state.session = payload)
};

const actions = {
  async fetchSession({ commit }) {
    let { data } = await axios.get(`/portal/session/${this.$route.params.session}`);

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
