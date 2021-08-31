<template>
  <div id="refine" class="collapse show">
    <div class="card card-1 mb-4">
      <div class="card-body">
        <div class="row">
          <div class="col form-group">
            <label>Company</label>
            <select class="custom-select" v-model="refine.company">
              <option :value="null">-- Select Company --</option>
              <option v-for="c in companies" :key="c.id" :value="c.id">{{ c.name }}</option>
            </select>
          </div>
          <div class="col form-group">
            <label>Module</label>
            <select class="custom-select" v-model="refine.module">
              <option :value="null">-- Select Module --</option>
              <option v-for="m in modules" :key="m">{{ m }}</option>
            </select>
          </div>
          <div class="col form-group">
            <label>Priority</label>
            <select class="custom-select" v-model="refine.priority">
              <option :value="null">-- Select Priority --</option>
              <option v-for="p in priority" :key="p.id" :value="p.id">{{ p.name }}</option>
            </select>
          </div>
          <div class="col form-group">
            <label>Assigned to</label>
            <select class="custom-select" v-model.number="refine.agent">
              <option :value="null">-- Select Agent --</option>
              <option :value="u.id" v-for="u in users" :key="u.id">{{ `${u.bio.first_name} ${u.bio.last_name}` }}</option>
            </select>
          </div>
          <div class="col form-group">
            <label>Status</label>
            <select class="custom-select" v-model.number="refine.status">
              <option :value="null">-- Select Status --</option>
              <option :value="s.id" v-for="s in status" :key="s.id">{{ `${s.name}` }}</option>
            </select>
          </div>
          <div class="col form-group">
            <label>Timestamp</label>
            <ADatePicker v-model="refine.created_at" />
          </div>
        </div>

        <div class="btn btn-primary pull-right ml-1" @click="$emit('onSearch', refine)">Search</div>
        <div class="btn btn-primary pull-right ml-0" @click="resetSearch(), $emit('onSearch', refine)">Reset</div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import { modules, tickets } from "@Scripts/observable";
import ADatePicker from "@Components/neutral/ADatePicker.vue";

export default {
  components: { ADatePicker },
  data() {
    return {
      modules: modules,
      priority: tickets.priority,
      status: tickets.status,
      refine: {
        company: null,
        module: null,
        priority: null,
        agent: null,
        status: null,
        created_at: null
      }
    };
  },
  computed: {
    ...mapState("auth", ["users"]),
    ...mapState("companies", ["companies"])
  },
  methods: {
    setRefineValues() {
      let query = this.$route.query;

      for (const [key, value] of Object.entries(this.refine)) {
        if (key in query) this.refine[key] = query[key];
      }
    },
    resetSearch() {
      this.refine = {
        company: null,
        module: null,
        priority: null,
        agent: null,
        status: null,
        created_at: null
      };
    }
  },
  created() {
    this.$store.dispatch("companies/fetchCompanies");
    this.setRefineValues();
  },
  watch: {
    $route() {
      this.setRefineValues();
    }
  }
};
</script>
