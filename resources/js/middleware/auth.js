import store from "@Scripts/store";

export default async (to, from, next) => {
  if (!store.getters["auth/check"]) {
    let query = {
      target: encodeURIComponent(to.fullPath),
      referrer: "guard-auth",
      referrer_type: "danger",
      referrer_message: "Please login to your account to continue."
    };

    next({ path: "/", query });
  } else {
    next();
  }
};
