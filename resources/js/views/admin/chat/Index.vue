<template>
  <div class="chat-wrapper" :class="{ selected: $route.query.cId }">
    <Contacts />
    <Messages v-show="$route.query.cId" @toggle="open = !open" :open="open" />
    <Meta v-show="$route.query.cId" @toggle="open = !open" :class="{ open: open }" />
    <div v-show="!$route.query.cId" class="flex-center w-100">Please select a customer</div>
  </div>
</template>

<script>
import Contacts from "@Components/admin/chat/Contacts.vue";
import Messages from "@Components/admin/chat/Messages.vue";
import Meta from "@Components/admin/chat/Meta.vue";

export default {
  layout: "Ticket",
  name: "Chats",
  metaInfo: () => ({ title: "Live Chat" }),
  middleware: "auth",
  components: { Contacts, Messages, Meta },
  data: () => ({
    open: false
  }),
  methods: {
    fetchSession() {
      if (this.$route.query.cId) console.log("cId: " + this.$route.query.cId);
    }
  },
  created() {
    this.fetchSession();
  },
  watch: {
    $route(newValue, oldValue) {
      this.fetchSession();
    }
  }
};
</script>

<style lang="scss">
@import "@Styles/pages/chat.scss";
</style>
