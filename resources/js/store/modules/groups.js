/**
 * @package Group Store
 *
 * @author Alfredo Flores
 * @email alfredo@xerosoft.com
 */

const namespaced = true;

const state = {
  groups: []
};

const getters = {};

const mutations = {
  SET_GROUPS: (state, groups) => (state.groups = groups)
};

const actions = {
  async fetchGroups({ commit }) {
    let { data } = await axios.get(`/portal/group`);

    commit("SET_GROUPS", data);
  }
};

export default {
  namespaced,
  state,
  mutations,
  getters,
  actions
};
