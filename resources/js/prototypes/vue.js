Vue.prototype.$APP_URL = process.env.MIX_APP_URL;

// Async Delayer
Vue.prototype.$delay = async function(ms) {
  return await new Promise(resolve => {
    setTimeout(() => {
      resolve();
    }, ms);
  });
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
Vue.prototype.$range = function(min, max) {
  return Array.from(
    (function*(x, y) {
      while (x <= y) yield x++;
    })(min, max)
  );
};

// Generate Random Number
Vue.prototype.$rand = function(min, max) {
  if (!min && !max) return Math.floor(Math.random() * 100);
  return Math.floor(Math.random() * (max - min + 1)) + min;
};

// Image On Error Fallback
Vue.prototype.$onImgError = function(e, i) {
  if (i == 1) e.target.src = `${process.env.MIX_APP_URL}/images/generic-profile.png`;
};
