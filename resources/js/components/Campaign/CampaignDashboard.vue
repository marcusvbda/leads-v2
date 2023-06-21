<template>
    <div class="flex gap-5 flex-col md:flex-row">
        <QtyLeads />
    </div>
</template>
<script>
import { mapMutations } from 'vuex';
import QtyLeads from './-qty-leads.vue';

export default {
    props: ['campaign'],
    components: {
        QtyLeads,
    },
    created() {
        this.setCampaign(this.campaign);
    },
    methods: {
        ...mapMutations('campaign', ['setCampaign']),
    },
};
</script>
