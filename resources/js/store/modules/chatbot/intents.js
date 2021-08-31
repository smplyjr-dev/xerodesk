/**
 * @package Intent Store
 * @author Alfredo Flores
 * @email alfredo@xerosoft.com
 */

import sortBy from "lodash/sortBy";
import isEmpty from "lodash/isEmpty";

const namespaced = true;

const state = {
  intents: [],
  intent: {},
  intentsAreLoading: false,
  intentIsLoading: false
};

const getters = {
  // Intents
  sortedIntents: state => sortBy(state.intents, "name"),

  // Utterance
  utterancesWithValidator: state => {
    let pattern = /[^'a-zA-Z {}?_.-]+/;
    let utterances = state.intent.utterances.map(utterance => ({
      value: utterance,
      isValid: !pattern.test(utterance)
    }));

    return utterances;
  },

  // Responses
  customIntentResponses: state => {
    let customResponses = [];

    state.intent.responses.forEach(rGroup => {
      customResponses.push({
        message: "",
        messages: rGroup
      });
    });

    return customResponses;
  }
};

const mutations = {
  // Dynamic Loader
  SET_LOADING: (state, loadingObj) => {
    if (loadingObj.type === "intent") {
      state.intentIsLoading = loadingObj.status;
    }

    if (loadingObj.type === "intents") {
      state.intentsAreLoading = loadingObj.status;
    }
  },

  // Intents
  SET_INTENTS: (state, intents) => (state.intents = intents),
  PUSH_INTENT: (state, intent) => state.intents.push(intent),
  SET_INTENT: (state, intent) => (state.intent = intent),
  DELETE_INTENT: (state, intent) => {
    state.intents = state.intents.filter(i => i.id !== intent.id);
  },

  // Utterance
  PUT_UTTERANCES: (state, utterance) => {
    state.intent.utterances.push(utterance);
  },
  DELETE_UTTERANCE: (state, utteranceKey) => {
    state.intent.utterances.splice(utteranceKey, 1);
  },

  // Responses
  ADD_RESPONSE: (state, payload) => {
    let { rgKey, rgMessage } = payload;
    state.intent.responses[rgKey].push(rgMessage);
  },
  ADD_RESPONSE_GROUP: state => {
    state.intent.responses.push([]);
  },
  DELETE_RESPONSE: (state, rKeys) => {
    let { rgKey, rKey } = rKeys;
    state.intent.responses[rgKey].splice(rKey, 1);
  },
  DELETE_RESPONSE_GROUP: (state, rgKey) => {
    state.intent.responses.splice(rgKey, 1);
  }
};

const actions = {
  async fetchIntents({ commit, getters }, id) {
    commit("SET_LOADING", { type: "intents", status: true });

    let { data } = await axios.get(`/chatbot/${id}/intents`);
    commit("SET_INTENTS", data);

    // if there are intents, set the first one
    if (!isEmpty(getters.sortedIntents)) {
      commit("SET_INTENT", getters.sortedIntents[0]);
    }

    commit("SET_LOADING", { type: "intents", status: false });
  },

  async storeIntent({ commit }, theIntent) {
    commit("SET_LOADING", { type: "intent", status: true });

    let { data } = await axios.post(`/intent`, theIntent);

    commit("PUSH_INTENT", data.data);
    commit("SET_INTENT", data.data);

    commit("SET_LOADING", { type: "intent", status: false });
  },

  async updateIntent({ commit, state }) {
    commit("SET_LOADING", { type: "intent", status: true });

    await axios.put(`/intent/${state.intent.id}`, state.intent);

    commit("SET_LOADING", { type: "intent", status: false });
  }
};

export default {
  namespaced,
  state,
  mutations,
  getters,
  actions
};
