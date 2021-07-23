<template>
    <div id="attendance">
        <div class="row">
            <category-side />
            <lead-painel />
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
        'lead-painel': require('./partials/painel/-lead-painel.vue').default,
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