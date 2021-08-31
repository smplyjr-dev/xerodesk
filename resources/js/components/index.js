// Dropdown Components
import AppDropdown from "@Components/dropdown/AppDropdown.vue";
import AppDropdownContent from "@Components/dropdown/AppDropdownContent.vue";
import AppDropdownItem from "@Components/dropdown/AppDropdownItem.vue";

[AppDropdown, AppDropdownContent, AppDropdownItem].forEach(Component => {
  Vue.component(Component.name, Component);
});

// Other Components
import FormAlert from "./neutral/FormAlert.vue";
import InlineSvg from "./neutral/InlineSvg.vue";
import Spinner from "./neutral/Spinner.vue";

[FormAlert, InlineSvg, Spinner].forEach(Component => {
  Vue.component(Component.name, Component);
});
