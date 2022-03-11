// Define $APP_ENV and $APP_URL for Vue files
Vue.prototype.$APP_ENV = window.Laravel.APP_ENV;
Vue.prototype.$APP_URL = window.Laravel.BASE_URL;

// Async Delayer
Vue.prototype.$delay = async function (ms) {
  return await Promise.prototype.delay(ms);
};

// Date Formatter
Vue.prototype.$dayjs = function (trans, date, data) {
  let result;
  // let dayjs = require("dayjs");
  // let relativeTime = require("dayjs/plugin/relativeTime");
  // dayjs.extend(relativeTime);

  window.dayjs.extend(window.dayjs_plugin_relativeTime);

  if (trans == "format") result = window.dayjs(date).format(data);
  if (trans == "fromNow") result = window.dayjs(date).fromNow();

  return result;
};

// Array Range
Vue.prototype.$range = function (min, max) {
  return Array.from(
    (function* (x, y) {
      while (x <= y) yield x++;
    })(min, max)
  );
};

// Generate Random Number
Vue.prototype.$rand = function (min, max) {
  if (!min && !max) return Math.floor(Math.random() * 100);
  return Math.floor(Math.random() * (max - min + 1)) + min;
};

// Get Profile Picture
Vue.prototype.$profilePicture = function (user) {
  if (user.profile_picture == "profile.png") {
    return `https://ui-avatars.com/api/?font-size=0.35&name=${user.bio.first_name}`;
  } else {
    return `${this.$APP_URL}/storage/uploads/users/${user.id}/${user.profile_picture}`;
  }
};

// Image On Error Fallback
Vue.prototype.$onImgError = function (e, i) {
  if (i == 1) e.target.src = `${window.Laravel.BASE_URL}/images/placeholder/profile.png`;
  if (i == 2) e.target.src = `${window.Laravel.BASE_URL}/images/placeholder/photograph.png`;
  if (i == 3) e.target.src = `${window.Laravel.BASE_URL}/images/logo-small-white.png`;
};
