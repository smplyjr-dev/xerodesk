/**
 * Bootstrap Tooltip Directive
 *
 * @developer Alfredo Flores
 * @email alfredo@xerosoft.com
 */

const bsTooltip = (el, binding, state) => {
  if (state === "update") $(el).tooltip("dispose");

  const t = [];

  if (binding.modifiers.focus) t.push("focus");
  if (binding.modifiers.hover) t.push("hover");
  if (binding.modifiers.click) t.push("click");
  if (!t.length) t.push("hover");

  $(el).tooltip({
    title: binding.value,
    placement: binding.arg || "top",
    trigger: t.join(" "),
    html: true // to allow html attribute, be careful
  });
};

export default {
  bind: (el, binding) => bsTooltip(el, binding, "init"),
  update: (el, binding) => bsTooltip(el, binding, "update"),
  unbind(el) {
    $(el).tooltip("dispose");
  }
};
