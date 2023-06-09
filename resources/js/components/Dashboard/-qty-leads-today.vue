<template>
    <loading-shimmer :loading="loading" :h="120" class="h-100">
        <DashCard
            title="Novos Leads"
            :qty="new_leads_qty"
            :subtitle="`Considerando todos cadastrados no sistema com data de cadastro ${today.format(
                'DD/MM/YYYY'
            )}`"
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
        ...mapGetters('dashboard', ['new_leads_qty']),
        today() {
            return this.$moment();
        },
    },
    created() {
        this.getData();
    },
    methods: {
        ...mapActions('dashboard', ['getNewLeadsQty']),
        getData() {
            this.getNewLeadsQty({
                today: this.today.format('YYYY-MM-DD'),
            }).then(() => {
                this.loading = false;
            });
        },
    },
};
</script>
