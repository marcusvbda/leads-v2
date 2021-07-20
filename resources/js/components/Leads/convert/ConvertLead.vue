<template>
    <div id="lead-convert">
        <identification-row />
        <div class="row">
            <div class="col-12">
                <div class="card no-radius">
                    <div class="card-body">
                        <info-obs-row />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import leadStore from '~/stores/lead'
export default {
    props: ['lead', 'use_tags', 'resource_id', 'answers', 'objections'],
    store: leadStore,
    components: {
        'identification-row': require('./-identification-row').default,
        'info-obs-row': require('./-info-obs-row').default,
    },

    created() {
        this.$store.commit('setUseTags', this.use_tags)
        this.$store.commit('setResourceId', this.resource_id)
        this.$store.commit('setLead', this.lead)
        this.$store.dispatch('getTypes')
        this.$store.dispatch('getAnswers')
        this.$store.dispatch('getObjections')
    },
}
</script>
<style lang="scss">
#lead-convert {
    .header {
        .lead-name {
            cursor: pointer;
            font-size: 25px;
            color: #4e5259;
            opacity: 0.7;
            transition: 0.3s;
            font-weight: 600;
            line-height: 20px;
            &:hover {
                text-decoration: unset;
                opacity: 1;
            }
        }
    }
}
</style>