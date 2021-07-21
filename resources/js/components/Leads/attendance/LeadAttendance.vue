<template>
    <div id="attendance" v-loading="!loaded.page">
        <div class="row">
            <category-side />
            <lead-panel />
        </div>
    </div>
</template>
<script>
import leadStore from '~/stores/lead'

export default {
    props: ['user'],
    store: leadStore,
    components: {
        'category-side': require('./partials/-category-side.vue').default,
        'lead-panel': require('./partials/-lead-panel.vue').default,
    },
    data() {
        return {
            loaded: {
                page: false,
                departments: false,
                pending_leads: false,
            },
        }
    },
    created() {
        this.init()
    },
    methods: {
        init() {
            Promise.all([
                this.$store.commit('setUser', this.user),
                this.$store.dispatch('getPendingLeads').then(() => (this.loaded.pending_leads = true)),
            ]).then(() => {
                this.loaded.page = true
            })
        },
    },
}
</script>