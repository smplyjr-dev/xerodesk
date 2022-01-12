/**
 * @package Auth Store
 *
 * @author Alfredo Flores
 * @email alfredo@xerosoft.com
 */

import Cookies from "js-cookie";

const namespaced = true;

const state = {
  users: [],
  user: null,
  token: Cookies.get("token")
};

const getters = {
  check: state => state.user !== null,
  token: state => state.token,
  user: state => state.user,
  permissions: state => ("permissions" in state.user ? state.user.permissions.pluckArray("slug") : [])
};

const mutations = {
  SET_USERS: (state, users) => {
    state.users = users;
  },

  FETCH_USER_SUCCESS: (state, user) => {
    state.user = user;
  },

  FETCH_USER_FAILED: state => {
    state.token = null;
    Cookies.remove("token");
  },

  LOGOUT: state => {
    state.user = null;
    state.token = null;

    Cookies.remove("token");
  },

  SAVE_TOKEN: (state, { token, remember }) => {
    state.token = token;
    Cookies.set("token", token, { expires: remember ? 365 : null });
  }
};

const actions = {
  async fetchUsers({ commit }) {
    let { data } = await axios.get(`/portal/user`);

    commit("SET_USERS", data);
  },

  async fetchUser({ commit }) {
    try {
      let { data } = await axios.get(`/portal/user/me`);

      commit("FETCH_USER_SUCCESS", data);
    } catch (e) {
      commit("FETCH_USER_FAILED");
    }
  },

  async logout({ commit }) {
    try {
      await axios.post("/logout");
    } catch (e) {}

    commit("LOGOUT");
  },

  saveToken({ commit }, payload) {
    commit("SAVE_TOKEN", payload);
  }
};

export default {
  namespaced,
  state,
  mutations,
  getters,
  actions
};
