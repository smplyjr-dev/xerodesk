import store from "@Scripts/store";

export default (to, from, next) => {
  if (store.getters["auth/check"]) {
    next("/tickets");
  } else {
    next();
  }
};
