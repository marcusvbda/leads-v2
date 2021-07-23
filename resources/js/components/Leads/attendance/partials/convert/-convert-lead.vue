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
export default {
    props: ['use_tags', 'resource_id', 'answers', 'objections'],
    components: {
        'identification-row': require('./-identification-row').default,
        'info-obs-row': require('./-info-obs-row').default,
    },
    created() {
        this.$store.commit('setUseTags', true)
        this.$store.commit('setResourceId', 'leads')
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