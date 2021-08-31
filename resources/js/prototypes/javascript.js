// Global Prototypes / Vanilla Javascript

// Title Case
String.prototype.toTitle = function() {
  return this.replace(/(^|\s)\S/g, function(t) {
    return t.toUpperCase();
  });
};

// Async Foreach
// Array.prototype.asyncForEach = async function(callback) {
//   for (let index = 0; index < this.length; index++) {
//     await callback(this[index], index, this);
//   }
// };

// Randomly Pick from Array
// Array.prototype.pickRandomly = function() {
//   return this[Math.floor(Math.random() * this.length)];
// };

// Custom Pluck Function
Object.defineProperty(Array.prototype, "pluck", {
  value: function(key) {
    return this.map(function(object) {
      return object[key];
    });
  }
});
