<template>
    <div class="flex gap-5 flex-col">
        <div class="flex gap-5 flex-col md:flex-row">
            <QtyLeads />
        </div>
        <CampaignBoard v-if="!after_row" :campaign="campaign" />
    </div>
</template>
<script>
import { mapMutations } from 'vuex';
import QtyLeads from './-qty-leads.vue';
import CampaignBoard from './-campaign-board.vue';

export default {
    props: ['campaign', 'fields', 'after_row'],
    components: {
        QtyLeads,
        CampaignBoard,
    },
    created() {
        this.setFields(this.fields);
        this.setCampaign(this.campaign);
    },
    methods: {
        ...mapMutations('campaign', ['setCampaign', 'setFields']),
    },
};
</script>
