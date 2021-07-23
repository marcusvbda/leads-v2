<template>
    <div v-if="lead.id">
        <div class="row">
            <div class="col-12 d-flex flex-row justify-content-between align-items-center">
                <div class="d-flex flex-column header">
                    <a :href="`/admin/leads/${lead.code}/edit`" class="lead-name" target="_BLANK">
                        {{ lead.name ? lead.name : undefined_text }} <small class="ml-2 f-12">#{{ lead.code }}</small>
                    </a>
                    <v-runtime-template class="mt-2" :template="lead.f_rating" />
                </div>
                <resource-tags-input class="mt-3" resource="leads" :resource_code="lead.code" />
            </div>
        </div>
        <div class="row mt-1">
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
                                        <small v-html="lead.f_status_badge" />
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <b class="f-12 text-muted">Data de Entrada</b>
                                        <small class="f-12">
                                            {{ lead.f_created_at ? lead.f_created_at : undefined_text }}
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column" v-if="lead.f_schedule">
                                        <b class="f-12 text-muted">Agendamento</b>
                                        <small class="f-12">
                                            {{ lead.f_schedule }}
                                        </small>
                                    </div>
                                </td>
                                <td></td>
                                <td></td>
                                <td>
                                    <slot />
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" v-if="lead.obs || lead.comment">
            <div class="col-12">
                <div class="bg-light p-3">
                    <div class="d-flex flex-row align-items-center mb-3 f-12">
                        <b class="mr-1">Observações :</b>
                        <span v-html="lead.obs" />
                    </div>
                    <div class="d-flex flex-row align-items-center f-12">
                        <b class="mr-1">Comentários :</b>
                        <span v-html="lead.comment" />
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4" v-if="lead.status.value == 'canceled'">
            <div class="col-12">
                <div class="alert alert-danger" role="alert">
                    <b class="alert-heading">{{ lead.f_status }}</b>
                    <p>
                        {{ lead.objection.name }}
                        {{ lead.other_objection }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import VRuntimeTemplate from 'v-runtime-template'

export default {
    props: ['lead_id', 'original_lead'],
    data() {
        return {
            undefined_text: 'Não Informado',
            lead: this.original_lead ?? null,
        }
    },
    components: {
        'v-runtime-template': VRuntimeTemplate,
    },
    created() {
        if (!this.lead) {
            this.init()
        }
    },
    methods: {
        async init() {
            let { data } = await this.$http.post('/vstack/json-api', {
                model: '\\App\\Http\\Models\\Lead',
                filters: {
                    where: [['id', '=', this.lead_id]],
                },
            })
            this.lead = data[0]
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