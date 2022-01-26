// Dropdown Components
import Dropdown from "./neutral/dropdown/Dropdown.vue";
import DropdownContent from "./neutral/dropdown/DropdownContent.vue";
import DropdownItem from "./neutral/dropdown/DropdownItem.vue";

[Dropdown, DropdownContent, DropdownItem].forEach((Component) => {
  Vue.component(Component.name, Component);
});

// Modal Components
import Modal from "./neutral/modal/Modal.vue";
import ModalBody from "./neutral/modal/ModalBody.vue";
import ModalHeader from "./neutral/modal/ModalHeader.vue";
import ModalFooter from "./neutral/modal/ModalFooter.vue";

[Modal, ModalBody, ModalHeader, ModalFooter].forEach((Component) => {
  Vue.component(Component.name, Component);
});

// Other Components
import Drawer from "./neutral/Drawer.vue";
import Skeleton from "./neutral/Skeleton.vue";
import Popup from "./neutral/Popup.vue";
import FormAlert from "./neutral/FormAlert.vue";
import InlineSvg from "./neutral/InlineSvg.vue";
import Spinner from "./neutral/Spinner.vue";

[Drawer, Skeleton, Popup, FormAlert, InlineSvg, Spinner].forEach((Component) => {
  Vue.component(Component.name, Component);
});
