<template>
    <div class="col-md-8 col-sm-12" id="lead-panel">
        <div class="row" v-if="!lead.id">
            <div class="col-12 d-flex align-items-center justify-content-center my-5 pt-4 d-flex flex-column">
                <h1 class="el-icon-trophy big-icon" />
                <span class="text-muted"> Bem-vindo ao painel de atendimento de Leads</span>
            </div>
        </div>
        <template v-else>
            <convert-lead v-if="visible" :lead="lead" />            
        </template>
    </div>
</template>
<script>
import { mapGetters } from 'vuex'
export default {
    computed: {
        ...mapGetters("lead",["lead"])
    },
    data() {
        return {
            visible : false
        }
    },
    watch : {
        lead : {
            handler : function(newVal,oldVal){
                this.visible = false
                this.$nextTick(() => {
                    this.visible = true
                })
            },
            deep : true
        }
    },
    components: {
        'convert-lead': require('./-convert-lead.vue').default,
    },
}
</script>
<style lang="scss">
#lead-panel {
    .big-icon {
        opacity: 0.1;
        font-size: 130px;
    }
}
</style>