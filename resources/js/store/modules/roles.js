/**
 * @package Role Store
 *
 * @author Alfredo Flores
 * @email alfredo@xerosoft.com
 */

const namespaced = true;

const state = {
  roles: {}
};

const getters = {};

const mutations = {
  SET_ROLES: (state, roles) => (state.roles = roles)
};

const actions = {
  async fetchRoles({ commit }) {
    let { data } = await axios.get(`/roles`);
    commit("SET_ROLES", data);
  }
};

export default {
  namespaced,
  state,
  mutations,
  getters,
  actions
};
