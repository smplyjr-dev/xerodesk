/**
 * @package Live Support Store
 * @author Alfredo Flores
 * @email alfredo@xerosoft.com
 */

const namespaced = true;

const state = {
  slas: []
};

const getters = {};

const mutations = {
  SET_SLAS: (state, slas) => (state.slas = slas)
};

const actions = {
  async fetchSlas({ commit }) {
    let { data } = await axios.get(`/slas`);

    commit("SET_SLAS", data);
  }
};

export default {
  namespaced,
  state,
  mutations,
  getters,
  actions
};
