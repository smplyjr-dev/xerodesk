<template>
  <transition name="fade">
    <div class="footer-emoji" v-on-clickaway="onAway">
      <VEmojiPicker @select="selectEmoji($event.data)" emojiSize="32" emojisByRow="8" limitFrequently="2" />
    </div>
  </transition>
</template>

<script>
import { mixin as clickaway } from "vue-clickaway";
import { VEmojiPicker } from "v-emoji-picker";

export default {
  name: "TicketReplyEmoji",
  mixins: [clickaway],
  components: { VEmojiPicker },
  methods: {
    onAway() {
      this.selectEmoji();
    },
    selectEmoji(emoji = "") {
      this.$emit("selectEmoji", emoji);
    }
  }
};
</script>

<style lang="scss" scoped>
.emoji-picker {
  width: 100% !important;
  border: 0 !important;

  #Categories {
    .category {
      padding: 10px 5px !important;
    }
  }

  #InputSearch {
    .container-search {
      margin: 0 !important;
      padding: 16px !important;

      input[type="text"] {
        border-radius: 3px !important;
        padding: 10px 16px !important;
      }
    }
  }

  #Emojis {
    .container-emoji {
      .grid-emojis {
        padding: 0px 10px 10px 10px !important;
        margin: 0px !important;
      }
    }
  }
}
</style>
