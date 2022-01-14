// import axios from "axios";
let init = window.axios;
import store from "@Scripts/store";
import App from "@Scripts/views/App.vue";

init.defaults.baseURL = process.env.MIX_APP_URL + "/api";

// Request interceptor for authorization token
init.interceptors.request.use((request) => {
  const token = store.getters["auth/token"];

  if (token) {
    request.headers.common["Authorization"] = `Bearer ${token}`;
  }

  return request;
});

// Response interceptor for failed ajax request
init.interceptors.response.use(
  (response) => response,
  (error) => {
    const { status } = error.response;

    if (status >= 500) {
      // display a notification
      store.dispatch("notifications/addNotification", {
        variant: "bg-danger",
        icon: "fa-times",
        title: "Invalid!",
        body: "Something from the background went wrong! Please contact the system administrator immediately."
      });
    }

    if (status === 401 && store.getters["auth/check"]) {
      // display the modal
      App.methods.showUnauthModal();
    }

    return Promise.reject(error);
  }
);

window.axios = init;
