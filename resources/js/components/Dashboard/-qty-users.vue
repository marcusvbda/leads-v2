<template>
    <loading-shimmer :loading="loading" :h="120" class="h-100">
        <dashboard-card
            title="Usuários"
            :qty="users_qty"
            subtitle="Usuários atualmente ativos no sistema"
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
        ...mapGetters('dashboard', ['users_qty']),
    },
    created() {
        this.init();
    },
    methods: {
        ...mapActions('dashboard', ['getUsersQty']),
        init() {
            this.getUsersQty().then(() => {
                this.loading = false;
            });
        },
    },
};
</script>
