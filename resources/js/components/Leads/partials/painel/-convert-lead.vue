<template>
    <div id="lead-convert">
        <identification-row :original_lead="lead">
            <el-dropdown @command="actionCommand">
                <span class="el-dropdown-link">
                    <b class="f-12 text-muted"> <span class="el-icon-menu mr-2" />Ações </b>
                    <i class="el-icon-arrow-down el-icon--right" />
                </span>
                <el-dropdown-menu slot="dropdown">
                    <el-dropdown-item command="transferLead">Transferir para outro <b>departamento</b></el-dropdown-item>
                    <el-dropdown-item command="finishLead"><b>Finalizar</b> lead</el-dropdown-item>
                </el-dropdown-menu>
            </el-dropdown>
            <select-dialog
                ref="select-department"
                title="Departamentos"
                description="Selecione o departamento que deseja transferir este lead"
                btn_text="Selecionar"
                @selected="transferToDerpartment"
            />
        </identification-row>
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
        'info-obs-row': require('./-info-obs-row').default,
    },
    computed: {
        lead() {
            return this.$store.state.lead
        },
    },
    created() {
        this.$store.commit('setUseTags', true)
        this.$store.commit('setResourceId', 'leads')
        this.$store.dispatch('getTypes')
        this.$store.dispatch('getAnswers')
        this.$store.dispatch('getObjections')
    },
    methods: {
        startAttendance() {
            this.$confirm('Deseja iniciar este atendimento ?', 'Confirmação').then(() => {
                return this.$store.commit('setleadActive', 'active')
            })
        },
        actionCommand(command) {
            this[command]()
        },
        transferLead() {
            this.$store.dispatch('getDepartments').then((deps) => {
                let select_dep = this.$refs['select-department']
                select_dep.options = deps.map((x) => ({ key: x.id, label: x.name }))
                select_dep.open()
            })
        },
        transferToDerpartment(department_id) {
            this.$store.dispatch('transferLead', department_id).then(() => {
                this.$store.dispatch('reloadAllLeads').then(() => {
                    this.$store.commit('setLead', {})
                    this.$message.success('Lead Transferido !!')
                })
            })
        },
        finishLead() {
            this.$confirm('Deseja finalizar este Lead ?', 'Confirmar').then(() => {
                this.$store.dispatch('finishLead').then(() => {
                    this.$store.dispatch('reloadAllLeads').then(() => {
                        this.$store.commit('setLead', {})
                        this.$message.success('Lead Finalizado !!')
                    })
                })
            })
        },
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