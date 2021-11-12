<template>
  <div class="messages">
    <div class="messages-header">
      <span @click="$router.push('/chats')">
        <i class="fa fa-chevron-left"></i>
      </span>
      <span @click="$emit('toggle')">Open</span>
    </div>
    <div class="messages-body" ref="body" :style="{ 'padding-right': overflowing ? '10px' : '0px' }">
      <div class="bubble bubble--external">
        <div class="bubble-content">
          <p>Hi</p>
        </div>
      </div>
      <div class="bubble bubble--internal">
        <div class="bubble-content">
          <p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Quod debitis obcaecati expedita nisi eius voluptatum, deleniti aliquam cupiditate corrupti quidem?</p>
        </div>
      </div>
    </div>
    <div class="messages-footer">
      <Editor />
      <div class="messages-footer-submit">
        <button type="button">Send</button>
      </div>
    </div>
  </div>
</template>

<script>
import Editor from "@Components/admin/chat/Editor.vue";

export default {
  components: { Editor },
  data: () => ({
    overflowing: false
  }),
  methods: {
    scrollbarVisible(element) {
      return element.scrollHeight > element.clientHeight;
    }
  },
  mounted() {
    this.overflowing = this.scrollbarVisible(this.$refs.body);
  }
};
</script>
