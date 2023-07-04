<template>
    <loading-shimmer
        :loading="loading"
        :h="156"
        class="h-full w-full md:w-3/12"
    >
        <dashboard-card
            title="Qtde Leads"
            :qty="qty_leads"
            subtitle="Quantidade de leads relacionados a esta campanha"
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
        ...mapGetters('campaign', ['qty_leads']),
    },
    created() {
        this.getData();
    },
    methods: {
        ...mapActions('campaign', ['fetchQtyLeads']),
        getData() {
            this.fetchQtyLeads().then(() => {
                this.loading = false;
            });
        },
    },
};
</script>
