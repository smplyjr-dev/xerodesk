/**
 * @package Live Support Store
 * @author Alfredo Flores
 * @email alfredo@xerosoft.com
 */

const namespaced = true;

const state = {
  companies: []
};

const getters = {};

const mutations = {
  SET_COMPANIES: (state, companies) => (state.companies = companies)
};

const actions = {
  async fetchCompanies({ commit }) {
    let { data } = await axios.get(`/portal/company`);

    commit("SET_COMPANIES", data);
  }
};

export default {
  namespaced,
  state,
  mutations,
  getters,
  actions
};
