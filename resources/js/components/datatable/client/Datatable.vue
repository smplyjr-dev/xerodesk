<template>
    <div class="table-responsive">
        <table class="table table-striped table-bordered m-0">
            <thead>
                <tr>
                    <th class="text-muted font-weight-semi" v-for="column in filteredColumns" :key="column.name" @click="handleSorting(column)" :class="sortKey === column.name ? (sortOrders[column.name] > 0 ? 'sorting_asc' : 'sorting_desc') : 'sorting'" :style="handleStyle(column)">
                        <div class="d-flex align-items-center justify-content-between">
                            {{ column.label }}

                            <div class="sorting-icon ml-1" v-if="column.sortable">
                                <div v-if="sortKey === column.name">
                                    <i class="fa fa-sort-asc" aria-hidden="true" v-if="sortOrders[column.name] < 0"></i>
                                    <i class="fa fa-sort-desc" aria-hidden="true" v-if="sortOrders[column.name] > 0"></i>
                                </div>
                                <div v-else>
                                    <i class="fa fa-sort" aria-hidden="true"></i>
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
            let columns = this.columns.filter(c => {
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

<style scoped>
th {
    white-space: nowrap;
}
</style>
