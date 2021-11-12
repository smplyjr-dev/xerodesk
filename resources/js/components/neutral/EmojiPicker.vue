<template>
  <popup :toggle="toggle">
    <div class="emoji-content" slot="content">
      <template v-if="emojis.length">
        <!-- <div class="emoji-group">
          <span v-for="(e, $e) in emojis" :key="$e" @click="group = $e">
            {{ e.emojiList[0].unicode }}
          </span>
        </div> -->
        <div class="emoji-items">
          <button class="border-0 bg-transparent" v-for="(e, $e) in emojis" :key="$e" @click="emit(e.unicode)">
            {{ e.unicode }}
          </button>
        </div>
      </template>
    </div>
    <div class="emoji-reference" slot="reference" @click="fetch()">
      <i class="far fa-smile"></i>
    </div>
  </popup>
</template>

<script>
export default {
  data() {
    return {
      toggle: "hide",
      emojis: [],
      group: 0
    };
  },
  methods: {
    emit(unicode) {
      this.toggle = "hide";
      this.$emit("onSelect", unicode);
    },
    fetch() {
      if (!this.emojis.length)
        axios.get(`${this.$APP_URL}/docs/emojis/0.json`).then(({ data }) => {
          this.emojis = data;
        });

      this.toggle = "show";
    }
  }
};
</script>
