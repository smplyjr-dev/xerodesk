/**
 * @package Live Support Store
 * @author Alfredo Flores
 * @email alfredo@xerosoft.com
 */

const namespaced = true;

const state = {
  messages: [],
  messages_attachments: [],

  message: "",
  message_attachments: [],

  reply_to: null,
  reply_to_shadow: null
};

const getters = {};

const mutations = {
  SET_MESSAGES: (state, data) => (state.messages = data),

  PUSH_MESSAGE: (state, message) => state.messages.push(message),

  UPDATE_MESSAGE: (state, data) => {
    state.messages = state.messages.map(m => {
      if (m.hash == data.hash) {
        return {
          ...m,
          ...data
        };
      }

      return m;
    });
  },

  INSERT_ATTACHMENT: (state, data) => {
    state.messages = state.messages.map(m => {
      if (m.hash == data.hash) {
        let attachments = [...m.attachments, data.attachment];

        return {
          ...m,
          attachments
        };
      }

      return m;
    });
  }
};

const actions = {
  async fetchSessionMessages({ commit }, session) {
    let { data } = await axios.get(`/session/${session}/messages`);

    commit("SET_MESSAGES", data);
  }
};

export default {
  namespaced,
  state,
  mutations,
  getters,
  actions
};
