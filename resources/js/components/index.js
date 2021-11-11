// Dropdown Components
import AppDropdown from "./neutral/dropdown/AppDropdown.vue";
import AppDropdownContent from "./neutral/dropdown/AppDropdownContent.vue";
import AppDropdownItem from "./neutral/dropdown/AppDropdownItem.vue";

[AppDropdown, AppDropdownContent, AppDropdownItem].forEach(Component => {
  Vue.component(Component.name, Component);
});

// Skeleton Components
import Skeleton from "./neutral/Skeleton.vue";

[Skeleton].forEach(Component => {
  Vue.component(Component.name, Component);
});

// Other Components
import FormAlert from "./neutral/FormAlert.vue";
import InlineSvg from "./neutral/InlineSvg.vue";
import Spinner from "./neutral/Spinner.vue";

[FormAlert, InlineSvg, Spinner].forEach(Component => {
  Vue.component(Component.name, Component);
});
