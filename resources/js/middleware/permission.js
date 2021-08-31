import store from "@Scripts/store";

export default (to, from, next, attrs) => {
  let user = store.state.auth.user;
  let permissions = user.permissions.map(p => p.slug);

  if (permissions.some(p => attrs.includes(p))) {
    next();
  } else {
    if (from.path == "/" && from.name == undefined) {
      // if they type the url
      next("/");
    } else {
      // if they click the url via button or any such
      next(from.path);
    }

    store.dispatch("notifications/addNotification", {
      variant: "bg-danger",
      icon: "fa-times",
      title: "Invalid.",
      body: "Sorry! You do not have a right permission to access the resource. Please ask the administrator. Thank you!"
    });
  }
};
