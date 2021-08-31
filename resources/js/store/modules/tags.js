/**
 * @package Tag Store
 *
 * @author Alfredo Flores
 * @email alfredo@xerosoft.com
 */

const namespaced = true;

const state = {
  tags: []
};

const getters = {};

const mutations = {
  SET_TAGS: (state, tags) => (state.tags = tags)
};

const actions = {
  async fetchTags({ commit }) {
    let { data } = await axios.get(`/tag`);

    commit("SET_TAGS", data);
  }
};

export default {
  namespaced,
  state,
  mutations,
  getters,
  actions
};
