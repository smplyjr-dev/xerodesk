<template>
    <div class="d-flex justify-content-between align-items-center flex-wrap" v-if="!client">
        <span class="page-stats mt-4">{{ pagination.from }} - {{ pagination.to }} of {{ pagination.total }}</span>

        <nav class="mt-4">
            <button class="btn btn-primary" v-if="pagination.prevPageUrl" @click="$emit('prev')">Prev</button>
            <button class="btn btn-primary" v-else :disabled="true" style="cursor: not-allowed;">Prev</button>

            <button class="btn btn-primary" v-if="pagination.nextPageUrl" @click="$emit('next')">Next</button>
            <button class="btn btn-primary" v-else :disabled="true" style="cursor: not-allowed;">Next</button>
        </nav>
    </div>

    <div class="d-flex justify-content-between align-items-center flex-wrap" v-else>
        <span class="page-stats mt-4">
            Showing
            {{ pagination.from }} - {{ pagination.to }} of {{ filtered.length }}
            <span v-if="filtered.length < pagination.total"> &#40;filtered from {{ pagination.total }} total entries&#41; </span>
            entries
        </span>

        <nav class="mt-4">
            <button class="btn btn-primary" v-if="pagination.prevPage" @click="$emit('prev')">Prev</button>
            <button class="btn btn-primary" v-else :disabled="true" style="cursor: not-allowed;">Prev</button>

            <button class="btn btn-primary" v-if="pagination.nextPage" @click="$emit('next')">Next</button>
            <button class="btn btn-primary" v-else :disabled="true" style="cursor: not-allowed;">Next</button>
        </nav>
    </div>
</template>

<script>
export default {
    props: ["pagination", "client", "filtered"]
};
</script>
