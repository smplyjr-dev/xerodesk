<template>
  <div class="ticket-contents">
    <TicketMessages />
    <TicketReply />
  </div>
</template>

<script>
import TicketMessages from "@Components/admin/ticket/show/TicketMessages.vue";
import TicketReply from "@Components/admin/ticket/show/reply/TicketReply.vue";

export default {
  components: { TicketMessages, TicketReply }
};
</script>
