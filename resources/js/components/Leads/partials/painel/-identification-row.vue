<template>
    <div>
        <div class="row">
            <div class="col-12 d-flex flex-row justify-content-between align-items-center">
                <div class="d-flex flex-column header">
                    <a :href="`/admin/leads/${lead.code}/edit`" class="lead-name" target="_BLANK">
                        {{ lead.name ? lead.name : undefined_text }} <small class="ml-2 f-12">#{{ lead.code }}</small>
                    </a>
                    <small class="text-muted f-12">Data de Entrada : {{ lead.f_created_at ? lead.f_created_at : undefined_text }}</small>
                    <v-runtime-template class="mt-2" :template="lead.f_rating" />
                </div>
                <resource-tags-input class="mt-3" v-if="use_tags" :resource="resource_id" :resource_code="lead.code" />
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <div class="card no-radius">
                    <div class="card-body">
                        <table class="table-id">
                            <tr>
                                <td>
                                    <div class="d-flex flex-column">
                                        <b class="f-12 text-muted">Nome</b>
                                        <span class="f-12">
                                            <a :href="`/admin/leads/${lead.code}/edit`" class="link" target="_BLANK">
                                                {{ lead.name ? lead.name : undefined_text }}
                                            </a>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <b class="f-12 text-muted">Data de Nascimento</b>
                                        <span class="f-12">{{ lead.f_birthdate ? lead.f_birthdate : undefined_text }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <b class="f-12 text-muted">Idade</b>
                                        <span class="f-12">{{ lead.age ? `${lead.age} Ano${lead.age > 1 ? 's' : ''}` : undefined_text }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <b class="f-12 text-muted">Email</b>
                                        <email-url type="email" class="f-12" v-if="lead.email" :value="lead.email">
                                            {{ lead.email }}
                                        </email-url>
                                        <span class="f-12" v-else>{{ undefined_text }}o</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <b class="f-12 text-muted">WhatsApp</b>
                                        <email-url type="wpp" class="f-12" v-if="lead.cellphone_number" :value="lead.cellphone_number">
                                            {{ lead.cellphone_number }}
                                        </email-url>
                                        <span class="f-12" v-else>{{ undefined_text }}o</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <b class="f-12 text-muted">Telefone Fixo</b>
                                        <span class="f-12">{{ lead.phone_number ? lead.phone_number : undefined_text }}</span>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex flex-column">
                                        <b class="f-12 text-muted">Status</b>
                                        <span v-html="lead.f_status_badge" />
                                    </div>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    <el-dropdown @command="actionCommand">
                                        <span class="el-dropdown-link">
                                            <b class="f-12 text-muted"> <span class="el-icon-menu mr-2" />Ações </b>
                                            <i class="el-icon-arrow-down el-icon--right" />
                                        </span>
                                        <el-dropdown-menu slot="dropdown">
                                            <el-dropdown-item command="transferLead">Transferir para outro <b>departamento</b></el-dropdown-item>
                                        </el-dropdown-menu>
                                    </el-dropdown>
                                    <select-dialog
                                        ref="select-department"
                                        title="Departamentos"
                                        description="Selecione o departamento que deseja transferir este lead"
                                        btn_text="Selecionar"
                                        @selected="transferToDerpartment"
                                    />
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import VRuntimeTemplate from 'v-runtime-template'

export default {
    data() {
        return {
            undefined_text: 'Não Informado',
        }
    },
    components: {
        'v-runtime-template': VRuntimeTemplate,
    },
    computed: {
        lead() {
            return this.$store.state.lead
        },
        use_tags() {
            return this.$store.state.use_tags
        },
        resource_id() {
            return this.$store.state.resource_id
        },
    },
    methods: {
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
                })
            })
        },
    },
}
</script>
<style lang="scss" scoped>
.table-id {
    width: 100%;
    td {
        padding: 5px 0px 10px 0px;
    }
}
</style>