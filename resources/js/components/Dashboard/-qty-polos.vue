<template>
    <loading-shimmer :loading="loading" :h="120" class="h-100">
        <dashboard-card
            title="Empresas"
            :qty="polos_qty"
            subtitle="Empresas atualmente ativas no sistema"
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
        ...mapGetters('dashboard', ['polos_qty']),
    },
    created() {
        this.init();
    },
    methods: {
        ...mapActions('dashboard', ['getPolosQty']),
        init() {
            this.getPolosQty().then(() => {
                this.loading = false;
            });
        },
    },
};
</script>
