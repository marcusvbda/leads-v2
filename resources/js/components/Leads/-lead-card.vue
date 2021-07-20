<template>
    <div class="card">
        <div class="card-body p-2 d-flex flex-column">
            <div class="d-flex flex-row align-items-center justify-content-between">
                <b class="d-flex flex-row align-items-center f-12">
                    <span class="el-icon-user-solid mr-2" /> {{ lead.name }}<small class="ml-2 text-muted">#{{ lead.code }}</small>
                </b>
                <el-dropdown @command="handleCommand">
                    <span class="el-dropdown-link"> <span class="el-icon-more" /></span>
                    <el-dropdown-menu slot="dropdown">
                        <el-dropdown-item command="convert" :disabled="!can_update">Converter Lead</el-dropdown-item>
                        <el-dropdown-item command="edit" :disabled="!can_update">Editar Lead</el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </div>
            <div class="mt-2 d-flex flex-row justify-content-between">
                <v-runtime-template class="mb-2" :template="lead.f_rating" />
                <small class="text-muted f-12">{{ lead.f_substatus }}</small>
            </div>
            <div class="d-flex flex-column">
                <small class="text-muted f-12"> <span class="el-icon-alarm-clock mr-2" /> Data de entrada : {{ lead.f_created_at }}</small>
                <small class="text-muted f-12" v-if="lead.f_schedule">
                    <span class="el-icon-alarm-clock mr-2" /> Data de agendamento : {{ lead.f_schedule }}
                </small>
                <small class="text-muted f-12"> <span class="el-icon-alarm-clock mr-2" /> Última conversão : {{ lead.f_last_conversion }}</small>
                <resource-tags-input class="mt-3 extra-mini-tags" v-if="use_tags" :resource="resource_id" :resource_code="lead.code" only_view />
            </div>
            <div class="d-flex mt-2 flex-row f-12 align-items-center">
                <email-url type="email" :value="lead.email" class="f-12">{{ lead.email }}</email-url>
                <div class="d-flex flex-row justify-content-between align-items-center mt-1 ml-auto" v-if="lead.phone_number || lead.cellphone_number">
                    <span class="text-muted" v-if="lead.phone_number">{{ lead.phone_number }}</span>
                    <email-url v-if="lead.cellphone_number" type="wpp" :value="lead.cellphone_number">{{ lead.cellphone_number }}</email-url>
                </div>
            </div>
            <div class="d-flex flex-column mt-2 align-items-start mt-3 f-12">
                <div class="d-flex flex-row justify-content-between w-100">
                    <small>
                        <a class="link" :href="`https://www.google.com.br/search?q=${lead.name}`" target="_BLANK">Encontrar no Google</a>
                    </small>
                    <small>
                        <a class="link" :href="`https://www.facebook.com/search/top/?q=${lead.name}`" target="_BLANK">Encontrar no Facebook</a>
                    </small>
                </div>
                <div class="d-flex flex-row justify-content-between w-100">
                    <small>
                        <a class="link" :href="`https://www.linkedin.com/search/results/people/?keywords=${lead.name}`" target="_BLANK"
                            >Encontrar no Linkedin</a
                        >
                    </small>
                    <small>
                        <a class="link" :href="`https://twitter.com/search?q=${lead.name}&f=user`" target="_BLANK">Encontrar no Twitter</a>
                    </small>
                </div>
            </div>
            <div class="d-flex flex-column mt-2 align-items-start f-12">
                <small class="text-success" v-if="lead.interest"><span class="el-icon-s-flag mr-2" /> {{ lead.interest }}</small>
                <small class="text-primary" v-if="lead.comment"><span class="el-icon-chat-round mr-2" /> {{ lead.comment }}</small>
                <small class="text-danger" v-if="lead.objection"><span class="el-icon-warning mr-2" /> {{ lead.objection }}</small>
                <small class="text-muted" v-if="lead.obs"><span class="el-icon-info mr-2" /> {{ lead.obs }}</small>
            </div>
        </div>
    </div>
</template>
<script>
import VRuntimeTemplate from 'v-runtime-template'
export default {
    props: ['lead_id'],
    components: {
        VRuntimeTemplate,
    },
    data() {
        return {
            loading: true,
            lead: {},
            can_clone: false,
            can_update: false,
            can_delete: false,
            can_view: false,
            use_tags: false,
            pusher_initialized: false,
            resource_id: 'leads',
        }
    },
    created() {
        this.getLead()
    },
    methods: {
        getLead() {
            this.$http
                .post(
                    `/vstack/${this.resource_id}/get-partial-content`,
                    {
                        row_id: this.lead_id,
                        type: 'resourceTableContent',
                        complete_model: true,
                    },
                    { retries: 3 }
                )
                .then((resp) => {
                    resp = resp.data
                    this.lead = resp.content
                    Object.keys(resp.acl).forEach((key) => (this[key] = resp.acl[key]))
                    this.loading = false
                    if (!this.pusher_initialized) {
                        this.initPusher()
                        this.pusher_initialized = true
                    }
                })
        },
        initPusher() {
            if (laravel.tenant.id && laravel.chat.pusher_key) {
                this.$echo.private(`App.Tenant.${laravel.tenant.id}`).listen(`.notifications.resource.${this.resource_id}.${this.row_id}`, (resp) => {
                    if (resp.event == 'reload') {
                        this.getResourceTableContent()
                    }
                })
            }
        },
        handleCommand(command) {
            this[command]()
        },
        convert() {
            window.location.href = `/admin/funil-de-conversao/${this.lead.code}/converter${window.location.search}`
        },
        edit() {
            window.location.href = `/admin/leads/${this.lead.code}/edit`
        },
    },
}
</script>