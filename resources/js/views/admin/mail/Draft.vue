<template>
  <div class="table table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th width="20%">Subject</th>
          <th width="70%">Content</th>
          <th width="10%">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="py-2">I need help</td>
          <td class="py-2">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quis, delectus.</td>
          <td class="py-2">1</td>
        </tr>
        <tr>
          <td class="py-2">Ticket is invalid</td>
          <td class="py-2">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quis, delectus.</td>
          <td class="py-2">1</td>
        </tr>
        <tr>
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
  name: "InboxDraft",
  metaInfo: () => ({ title: "Draft" }),
  middleware: "auth",
  data: () => ({}),
  computed: {
    ...mapState("auth", ["user"])
  },
  methods: {},
  async created() {
    this.$emit("setTitle", "Draft");
    this.$emit("toggleSidebar", true);
  }
};
</script>
