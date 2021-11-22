<template>
  <transition name="fade">
    <div class="drawer-backdrop" v-show="isOpen" @click.self="$emit('toggleDrawer', false)">
      <transition name="right">
        <div class="drawer-container" v-show="isOpen">
          <div class="drawer-close" @click="$emit('toggleDrawer', false)">
            <InlineSvg name="template/mdi-close.svg" color="#c6c6c6" size="100%" />
          </div>

          <form @submit.prevent="$emit('onSearch', refine)">
            <h4>Search by Session</h4>
            <div class="form-group">
              <label>Session</label>
              <input type="text" class="form-control" v-model="refine.session" />
            </div>
            <div class="form-group d-flex flex-row-reverse">
              <button class="btn btn-primary" type="submit">Search</button>
              <button class="btn btn-light" @click="resetSearch(), $emit('onSearch', refine)">Reset</button>
            </div>
          </form>

          <br />

          <form @submit.prevent="$emit('onSearch', refine)">
            <h4>Refine Search</h4>
            <div class="form-group">
              <label>Company</label>
              <select class="custom-select" v-model="refine.company" @focus="$store.dispatch('companies/fetchCompanies')">
                <option :value="null">-- Select Company --</option>
                <option v-for="c in companies" :key="c.id" :value="c.id">{{ c.name }}</option>
                <option :value="null" disabled v-if="$isEmpty(companies)">No company found.</option>
              </select>
            </div>
            <div class="form-group">
              <label>Priority</label>
              <select class="custom-select" v-model="refine.priority">
                <option :value="null">-- Select Priority --</option>
                <option v-for="p in priority" :key="p.id" :value="p.id">{{ p.name }}</option>
                <option :value="null" disabled v-if="$isEmpty(priority)">No priority found.</option>
              </select>
            </div>
            <div class="form-group">
              <label>Assigned to</label>
              <select class="custom-select" v-model.number="refine.agent">
                <option :value="null">-- Select Agent --</option>
                <option :value="u.id" v-for="u in users" :key="u.id">{{ `${u.bio.first_name} ${u.bio.last_name}` }}</option>
                <option :value="null" disabled v-if="$isEmpty(users)">No users found.</option>
              </select>
            </div>
            <div class="form-group">
              <label>Status</label>
              <select class="custom-select" v-model.number="refine.status">
                <option :value="null">-- Select Status --</option>
                <option :value="s.id" v-for="s in status" :key="s.id">{{ `${s.name}` }}</option>
                <option :value="null" disabled v-if="$isEmpty(status)">No status found.</option>
              </select>
            </div>
            <div class="form-group">
              <label>Timestamp</label>
              <a-date-picker v-model="refine.created_at" />
            </div>
            <div class="form-group d-flex flex-row-reverse">
              <button class="btn btn-primary" type="submit">Search</button>
              <button class="btn btn-light" @click="resetSearch(), $emit('onSearch', refine)">Reset</button>
            </div>
          </form>
        </div>
      </transition>
    </div>
  </transition>
</template>

<script>
import { mapState } from "vuex";
import { tickets } from "@Scripts/observable";
import ADatePicker from "@Components/neutral/ADatePicker.vue";

export default {
  props: ["isOpen"],
  components: { ADatePicker },
  data() {
    return {
      priority: tickets.priority,
      status: tickets.status,
      refine: {
        company: null,
        session: null,
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
        session: null,
        priority: null,
        agent: null,
        status: null,
        created_at: null
      };
    }
  },
  created() {
    this.setRefineValues();
  },
  watch: {
    $route() {
      this.setRefineValues();
    }
  }
};
</script>
