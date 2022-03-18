/**
 * @package Notification Store
 *
 * @author Alfredo Flores
 * @email alfredo@xerosoft.com
 */

import { nanoid } from "nanoid";

const namespaced = true;

const state = {
  notifications: [
    // sample data to pass
    // {
    //   id: nanoid(),
    //   variant: "bg-success",
    //   icon: "fa-check-circle",
    //   title: "Success!",
    //   duration: "5000",
    //   body: "This is a success notification."
    // }
  ]
};

const getters = {
  sortedNotifications: (state) => {
    return [...state.notifications].reverse().slice(0, 5);
  }
};

const mutations = {
  PUSH_NOTIFICATION: (state, notification) => {
    state.notifications.push(notification);
  },

  REMOVE_NOTIFICATION: (state, id) => {
    let remainingNotifications = state.notifications.filter((n) => n.id != id);
    state.notifications = remainingNotifications;
  }
};

const actions = {
  addNotification({ commit }, notification) {
    let id = nanoid();

    // conditional duration
    if (!("duration" in notification)) {
      notification = {
        ...notification,
        duration: "duration" in notification ? notification.duration : 7500
      };
    }

    commit("PUSH_NOTIFICATION", {
      id,
      ...notification
    });

    setTimeout(() => {
      commit("REMOVE_NOTIFICATION", id);
    }, notification.duration);
  },
  failedNotification({ dispatch }, notification) {
    dispatch("addNotification", {
      variant: "bg-danger",
      icon: "fa-exclamation-triangle",
      ...notification
    });
  },
  successNotification({ dispatch }, notification) {
    dispatch("addNotification", {
      variant: "bg-success",
      icon: "fa-check-circle",
      ...notification
    });
  }
};

export default {
  namespaced,
  state,
  mutations,
  getters,
  actions
};
