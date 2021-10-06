<template>
    <div id="attendance">
        <div class="row">
            <category-side />
            <lead-painel />
        </div>
    </div>
</template>
<script>
import { mapMutations } from 'vuex'

export default {
    props: ['user'],
    components: {
        'category-side': require('./partials/-category-side.vue').default,
        'lead-painel': require('./partials/painel/-lead-painel.vue').default,
    },
    created() {
        this.init()
    },
    methods: {
        ...mapMutations("lead",["setUser"]),
        init() {
            this.setUser(this.user)
        },
    },
}
</script>