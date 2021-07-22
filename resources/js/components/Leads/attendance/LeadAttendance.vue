<template>
    <div id="attendance">
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
    created() {
        this.init()
    },
    methods: {
        init() {
            this.$store.commit('setUser', this.user)
        },
    },
}
</script>