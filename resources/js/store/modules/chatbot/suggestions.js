/**
 * @package Chatbot Suggestion Store
 * @author Alfredo Flores
 * @email alfredo@xerosoft.com
 */

const namespaced = true;

const state = {
  suggestions: []
};

const getters = {};

const mutations = {
  SET_SUGGESTIONS: (state, suggestions) => (state.suggestions = suggestions),

  PUSH_SUGGESTION: (state, suggestion) => {
    state.suggestions.push(suggestion);
  },

  DELETE_SUGGESTION: (state, suggestion) => {
    let remainingSuggestions = state.suggestions.filter(s => s.id !== suggestion.id);
    state.suggestions = remainingSuggestions;
  }
};

const actions = {
  async fetchSuggestions({ commit }, id) {
    let { data } = await axios.get(`/chatbot/${id}/suggestions`);
    commit("SET_SUGGESTIONS", data);
  }
};

export default {
  namespaced,
  state,
  mutations,
  getters,
  actions
};
