/**
 * @package Chatbot Store
 * @author Alfredo Flores
 * @email alfredo@xerosoft.com
 */

const namespaced = true;

const state = {
  chatbot: {},
  chatbotIsLoading: false
};

const getters = {};

const mutations = {
  // Dynamic Loader
  SET_LOADING: (state, loadingObj) => {
    if (loadingObj.type === "chatbot") {
      state.chatbotIsLoading = loadingObj.status;
    }

    if (loadingObj.type === "chatbots") {
      state.chatbotsAreLoading = loadingObj.status;
    }
  },

  // Chatbots
  SET_CHATBOT: (state, chatbot) => {
    state.chatbot = chatbot;
  },

  // Error Handling - Clarification Prompts
  PUT_CLARIFICATIONS: (state, clarification) => {
    state.chatbot.clarification_prompt.unshift(clarification);
  },
  DELETE_CLARIFICATION: (state, clarificationKey) => {
    state.chatbot.clarification_prompt.splice(clarificationKey, 1);
  },

  // Error Handling - Abort Statement
  PUT_STATEMENT: (state, statement) => {
    state.chatbot.hangup_phrase.unshift(statement);
  },
  DELETE_STATEMENT: (state, statementKey) => {
    state.chatbot.hangup_phrase.splice(statementKey, 1);
  }
};

const actions = {
  async fetchChatbot({ commit }) {
    commit("SET_LOADING", { type: "chatbot", status: true });

    let { data } = await axios.get(`/chatbot/1`);
    commit("SET_CHATBOT", data);

    commit("SET_LOADING", { type: "chatbot", status: false });
  }
};

export default {
  namespaced,
  state,
  mutations,
  getters,
  actions
};
