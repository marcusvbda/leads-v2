<template>
    <loading-shimmer :loading="loading" :h="120" class="h-100">
        <dashboard-card
            title="Departamentos"
            :qty="departments_qty"
            subtitle="Departamentos atualmente ativos no sistema"
        />
    </loading-shimmer>
</template>
<script>
import { mapActions, mapGetters } from 'vuex';
export default {
    data() {
        return {
            loading: true,
        };
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
