<template>
    <loading-shimmer :loading="loading" :h="120" class="h-100">
        <DashCard
            title="Polos"
            :qty="polos_qty"
            subtitle="Polos atualmente ativos no sistema"
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
