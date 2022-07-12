<template>
  <div class="table table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th width="10%"></th>
          <th width="20%">Subject</th>
          <th width="60%">Content</th>
          <th width="10%">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="py-2">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="customCheck1" />
              <label class="custom-control-label" for="customCheck1"></label>
            </div>
          </td>
          <td class="py-2">I need help</td>
          <td class="py-2">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quis, delectus.</td>
          <td class="py-2">1</td>
        </tr>
        <tr>
          <td class="py-2">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="customCheck1" />
              <label class="custom-control-label" for="customCheck1"></label>
            </div>
          </td>
          <td class="py-2">Ticket is invalid</td>
          <td class="py-2">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quis, delectus.</td>
          <td class="py-2">1</td>
        </tr>
        <tr>
          <td class="py-2">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="customCheck1" />
              <label class="custom-control-label" for="customCheck1"></label>
            </div>
          </td>
          <td class="py-2">Payment failed</td>
          <td class="py-2">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quis, delectus.</td>
          <td class="py-2">1</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  layout: "Inbox",
  name: "Inbox",
  metaInfo: () => ({ title: "Inbox" }),
  middleware: "auth",
  data: () => ({}),
  computed: {
    ...mapState("auth", ["user"])
  },
  methods: {},
  async created() {
    this.$emit("setTitle", "Inbox");
    this.$emit("toggleSidebar", true);
  }
};
</script>
