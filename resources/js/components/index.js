// Dropdown Components
import AppDropdown from "./neutral/dropdown/AppDropdown.vue";
import AppDropdownContent from "./neutral/dropdown/AppDropdownContent.vue";
import AppDropdownItem from "./neutral/dropdown/AppDropdownItem.vue";

[AppDropdown, AppDropdownContent, AppDropdownItem].forEach(Component => {
  Vue.component(Component.name, Component);
});

// Other Components
import Drawer from "./neutral/Drawer.vue";
import Skeleton from "./neutral/Skeleton.vue";
import Popup from "./neutral/Popup.vue";
import FormAlert from "./neutral/FormAlert.vue";
import InlineSvg from "./neutral/InlineSvg.vue";
import Spinner from "./neutral/Spinner.vue";

[Drawer, Skeleton, Popup, FormAlert, InlineSvg, Spinner].forEach(Component => {
  Vue.component(Component.name, Component);
});
