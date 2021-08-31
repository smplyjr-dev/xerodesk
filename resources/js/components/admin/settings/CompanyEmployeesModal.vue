<template>
  <div id="companies-modal" class="modal fade">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ company && company.name ? company.name : "" }} Employees</h5>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-body">
          <div>
            <ul class="nav nav-tabs" role="tablist">
              <li class="nav-item" role="presentation" v-for="c in companies" :key="c.id" @click="id = c.id">
                <a :id="`${getName(c)}-tab`" class="nav-link" :class="{ active: c.id == 1 }" data-toggle="tab" :href="`#${getName(c)}`">
                  {{ c.name }}
                </a>
              </li>
            </ul>
            <div class="tab-content p-4 border" style="margin-top: -1px;">
              <div :id="`${getName(c)}`" class="tab-pane fade" :class="{ 'show active': c.id == 1 }" role="tabpanel" :aria-labelledby="`${getName(c)}-tab`" v-for="c in companies" :key="c.id">
                <TableCompanyEmployees :company="company" :employees="employees" @refresh="$emit('refresh', $event)" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import TableCompanyEmployees from "./TableCompanyEmployees.vue";

export default {
  props: ["company"],
  components: { TableCompanyEmployees },
  data: () => ({
    companies: [],
    employees: [],
    id: null
  }),
  methods: {
    getName: p => p.name.replaceAll(" ", "-").toLowerCase()
  },
  watch: {
    async company(newValue) {
      let { data } = await axios.get(`${newValue.url}/api/filichat/companies`, {
        headers: { Authorization: `Bearer ${process.env.MIX_FILICHAT_API_TOKEN}` }
      });

      this.companies = data;
      this.id = data[0].id;
    },
    async id(newValue) {
      let { data } = await axios.get(`${this.company.url}/api/filichat/employees`, {
        params: { company_id: newValue },
        headers: { Authorization: `Bearer ${process.env.MIX_FILICHAT_API_TOKEN}` }
      });

      this.employees = data;
    }
  }
};
</script>
