<template>
    <div id="lead-convert">
        <identification-row :original_lead="lead">
            <el-dropdown @command="actionCommand">
                <span class="el-dropdown-link">
                    <b class="f-12 text-muted"> <span class="el-icon-menu mr-2" />Ações </b>
                    <i class="el-icon-arrow-down el-icon--right" />
                </span>
                <el-dropdown-menu slot="dropdown">
                    <el-dropdown-item command="makePotential">Voltar lead para <b>potencial</b> (sem responsável e sem
                        departamento)
                    </el-dropdown-item>
                    <el-dropdown-item command="transferLead">Transferir para outro <b>departamento</b>
                    </el-dropdown-item>
                    <el-dropdown-item command="finishLead"><b>Finalizar</b> lead</el-dropdown-item>
                </el-dropdown-menu>
            </el-dropdown>
            <select-dialog ref="select-department" title="Departamentos"
                description="Selecione o departamento que deseja transferir este lead" btn_text="Selecionar"
                @selected="transferToDerpartment" />
        </identification-row>
        <div class="row">
            <div class="col-12">
                <div class="card no-radius">
                    <div class="card-body">
                        <info-obs-row :after_row="after_row" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapActions, mapGetters, mapMutations } from 'vuex'
export default {
    props: ['use_tags', 'resource_id', 'answers', 'objections', 'original_lead', 'after_row'],
    components: {
        'info-obs-row': require('./-info-obs-row').default,
        'identification-row': require('./-identification-row').default,
    },
    computed: {
        ...mapGetters("lead", ["lead"])
    },
    created() {
        if (this.original_lead) {
            this.setLead(this.original_lead)
        }
        this.setUseTags(true)
        this.setResourceId('leads')
        this.getTypes()
        this.getAnswers()
        this.getObjections()
    },
    methods: {
        ...mapMutations("lead", ["setUseTags", "setResourceId", "setLead"]),
        ...mapActions("lead", ["getTypes", "getAnswers", "getObjections", "setleadActive", "reloadAllLeads", "getDepartments"]),
        ...mapActions("lead", {
            transferLeadAction: "transferLead",
            finishLeadAction: "finishLead"
        }),
        startAttendance() {
            this.$confirm('Deseja iniciar este atendimento ?', 'Confirmação').then(() => {
                return this.setleadActive('active')
            })
        },
        actionCommand(command) {
            this[command]()
        },
        transferLead() {
            this.getDepartments().then((deps) => {
                let select_dep = this.$refs['select-department']
                select_dep.options = deps.map((x) => ({ key: x.id, label: x.name }))
                select_dep.open()
            })
        },
        transferToDerpartment(department_id) {
            this.transferLeadAction(department_id).then(() => {
                this.reloadAllLeads().then(() => {
                    this.setLead({})
                    this.$message.success('Lead Transferido !!')
                })
            })
        },
        makePotential() {
            this.$confirm('Deseja voltar esse lead para potencial (sem responsável e sem departamento definido) ?', 'Confirmar').then(() => {
                this.transferLeadAction(null).then(() => {
                    this.reloadAllLeads().then(() => {
                        this.setLead({})
                        this.$message.success('Lead Retornado para potencial !!')
                    })
                })
            })
        },
        finishLead() {
            this.$confirm('Deseja finalizar este Lead ?', 'Confirmar').then(() => {
                this.finishLeadAction().then(() => {
                    this.reloadAllLeads().then(() => {
                        this.setLead({})
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