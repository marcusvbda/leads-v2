<template>
    <loading-shimmer :loading="loading" :h="120" class="h-100">
        <DashCard
            title="Departamentos"
            :qty="departments_qty"
            subtitle="Departamentos atualmente ativos no sistema"
        />
    </loading-shimmer>
</template>
<script>
import { mapActions, mapGetters } from 'vuex';
import DashCard from './-dash-card.vue';
export default {
    data() {
        return {
            loading: true,
        };
    },
    components: {
        DashCard,
    },
    computed: {
        ...mapGetters('dashboard', ['departments_qty']),
    },
    created() {
        this.getData();
    },
    methods: {
        ...mapActions('dashboard', ['getDepartmentsQty']),
        getData() {
            this.getDepartmentsQty().then(() => {
                this.loading = false;
            });
        },
    },
};
</script>
