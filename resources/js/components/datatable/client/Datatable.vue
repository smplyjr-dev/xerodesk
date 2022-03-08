<template>
  <div class="table-responsive">
    <table class="table table-hover m-0">
      <thead>
        <tr>
          <th v-for="column in filteredColumns" :key="column.name" @click="handleSorting(column)" :class="sortKey === column.name ? (sortOrders[column.name] > 0 ? 'sorting_asc' : 'sorting_desc') : 'sorting'" :style="handleStyle(column)">
            <div class="d-flex align-items-center">
              {{ column.label }}

              <div class="sorting-icon" v-if="column.sortable">
                <div v-show="sortKey === column.name">
                  <InlineSvg name="svg/mdi/menu-up.svg" color="#464646" size="1.25rem" v-show="sortOrders[column.name] < 0" />
                  <InlineSvg name="svg/mdi/menu-down.svg" color="#464646" size="1.25rem" v-show="sortOrders[column.name] > 0" />
                </div>
                <div v-show="sortKey !== column.name">
                  <InlineSvg name="svg/mdi/menu-swap.svg" color="#464646" size="1.25rem" />
                </div>
              </div>
            </div>
          </th>
        </tr>
      </thead>

      <slot></slot>
    </table>
  </div>
</template>

<script>
export default {
  props: ["columns", "sortKey", "sortOrders"],
  computed: {
    filteredColumns() {
      let columns = this.columns.filter((c) => {
        if (typeof c.hide == "undefined" || !c.hide) return true;
      });

      return columns;
    }
  },
  methods: {
    handleSorting(column) {
      if (column.sortable) {
        this.$emit("sort", column.name);
      }
    },
    handleStyle(column) {
      return "width:" + column.width + ";" + (column.sortable ? "cursor: pointer;" : "");
    }
  }
};
</script>
