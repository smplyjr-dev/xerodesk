<template>
  <div class="tag-container">
    <div class="tag-input">
      <input type="text" class="form-control" v-model="newTag" :disabled="isCreatingAttaching || disabled" @focus="fetchTags" />
    </div>

    <div class="tag-lookup" v-if="!$isEmpty(newTag)">
      <ul class="list-unstyled">
        <!-- if empty, create and attach -->
        <template v-if="$isEmpty(filteredTags)">
          <li>
            <button class="btn btn-brand-1 btn-sm btn-block" @click="createTag()" :disabled="isCreatingAttaching">
              <div v-if="isCreatingAttaching" class="spinner-border spinner-border-sm" role="status"></div>
              <span v-else>Create and Attach</span>
            </button>
          </li>
        </template>

        <!-- if not empty, pick and attach -->
        <template v-else>
          <li v-if="sessionTags.find(sT => sT.name.toLowerCase() === newTag.toLowerCase())">
            <strong>{{ newTag }}</strong> is already tagged to this session.
          </li>
          <li v-else class="list-item" v-for="(ft, ftKey) in filteredTags" :key="ftKey" @click="attachTag(ft)" :style="{ 'pointer-events': isAttaching.includes(ft.id) ? 'none' : 'auto' }">
            {{ ft.name }}
          </li>
        </template>
      </ul>
    </div>

    <div class="tag-badges">
      <span class="badge badge-primary text-white" :class="{ 'badge-danger': sTag.name.toLowerCase() === newTag.toLowerCase() }" v-for="(sTag, stKey) in sessionTags" :key="stKey">
        {{ sTag.name }}
        <div v-if="isAttaching.includes(sTag.id)" class="spinner-border spinner-border-sm ml-1" role="status"></div>
        <div v-else class="ml-1 cursor-pointer" @click="detachTag(sTag)">
          <InlineSvg name="svg/mdi/close-circle.svg" size="1rem" />
        </div>
      </span>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  props: {
    disabled: {
      type: Boolean,
      required: false
    },
    session: {
      type: Object,
      required: true
    }
  },
  data: () => ({
    isCreatingAttaching: false,
    isAttaching: [],
    newTag: "",
    sessionTags: []
  }),
  computed: {
    ...mapState("tags", ["tags"]),

    filteredTags() {
      let newTag = this.newTag.toLowerCase();
      let filterResponse = [];

      if (!this.$isEmpty(this.newTag)) {
        let sessionTags = this.sessionTags;

        let filteredA = this.tags.filter(fTag => fTag.name.toLowerCase().includes(newTag));
        let filteredB = filteredA.filter(a1 => {
          let returnThis = true;

          sessionTags.forEach(a2 => {
            if (JSON.stringify(a1) === JSON.stringify(a2)) returnThis = false;
          });

          return returnThis;
        });

        filterResponse = filteredB;
      }

      return filterResponse;
    }
  },
  methods: {
    async fetchSessionTags() {
      let { data } = await axios.get(`/portal/session/${this.session.session}/tag`);

      this.sessionTags = data.map(d => {
        return {
          id: d.id,
          name: d.name,
          created_at: d.created_at,
          updated_at: d.updated_at
        };
      });
    },
    async createTag() {
      this.isCreatingAttaching = true;

      // create the taggable
      let { data } = await axios.post(`/portal/tag`, {
        name: this.newTag
      });

      // get all the taggable, this will auto refresh the dropdown list
      await this.$store.dispatch("tags/fetchTags");

      // set the newly created taggable
      let { length, [length - 1]: lastTag } = this.tags;
      this.attachTag(lastTag);

      this.isCreatingAttaching = false;
    },
    async attachTag(tag) {
      this.isAttaching.push(tag.id);

      let { data } = await axios.post(`/portal/session/${this.session.session}/tag`, {
        taggable_id: tag.id
      });

      await this.fetchSessionTags();
      this.newTag = "";

      this.isAttaching = this.isAttaching.filter(id => id != data);
    },
    async detachTag(tag) {
      this.isAttaching.push(tag.id);

      let { data } = await axios.delete(`/portal/session/${this.session.session}/tag`, {
        params: {
          taggable_id: tag.id
        }
      });

      await this.fetchSessionTags();
      this.newTag = "";

      this.isAttaching = this.isAttaching.filter(id => id != data);
    },
    async fetchTags() {
      if (this.$isEmpty(this.tags)) await this.$store.dispatch("tags/fetchTags");
    }
  },
  async created() {
    this.sessionTags = this.session.taggables;
  }
};
</script>
