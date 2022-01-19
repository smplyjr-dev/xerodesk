// Create new promise that automatically resolves after some timeout
window.Promise.prototype.delay = function (time) {
  return new Promise((resolve, reject) => {
    setTimeout(resolve, time);
  });
};

// Throttle this promise to resolve no faster than the specified time
window.Promise.prototype.takeAtLeast = function (time) {
  return Promise.all([this, Promise.prototype.delay(time)]).then(([result]) => result);
};
