// Async Foreach
// window.Array.prototype.asyncForEach = async function (callback) {
//   for (let index = 0; index < this.length; index++) {
//     await callback(this[index], index, this);
//   }
// };

// Randomly Pick from Array
// window.Array.prototype.pickRandomly = function () {
//   return this[Math.floor(Math.random() * this.length)];
// };

// Create custom array plucker
window.Array.prototype.pluckArray = function (key) {
  return this.map(function (object) {
    return object[key];
  });
};
