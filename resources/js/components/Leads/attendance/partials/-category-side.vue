<template>
    <div class="col-md-4 col-sm-12">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex flex-column">
                    <b class="mb-3 d-flex align-items-center">
                        <span class="el-icon-user-solid mr-2" />
                        {{ user }}
                        <small class="ml-auto text-muted">{{ department }}</small>
                    </b>
                    <el-input placeholder="Pesquisar..." suffix-icon="el-icon-search" v-model="filter" />
                    <el-tabs class="mt-3">
                        <el-tab-pane>
                            <span slot="label">
                                Ativos
                                <template v-if="active_leads.total"> ({{ active_leads.total }}) </template>
                            </span>
                            <div class="row" v-if="!active_leads.data.length">
                                <div class="col-12 d-flex align-items-center justify-content-center my-5">
                                    <span class="text-muted"> Nenhum Lead Ativo</span>
                                </div>
                            </div>
                            <div class="row" v-else>
                                <div class="col-12 d-flex align-items-center justify-content-center">
                                    <div class="w-100" style="overflow: auto; margin: 0">
                                        <div class="d-flex flex-column" v-infinite-scroll="loadActive" style="overflow: auto; height: 300px">
                                            <div v-for="lead in active_leads.data" :key="lead.id">{{ lead.name }}</div>
                                            <div
                                                v-if="loading.active_leads && active_leads.has_more"
                                                v-loading="loading.active_leads"
                                                class="py-5 my-3"
                                                element-loading-text="Loading ..."
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane>
                            <span slot="label">
                                Pendentes
                                <template v-if="pending_leads.total"> ({{ pending_leads.total }}) </template>
                            </span>
                            <div class="row" v-if="!pending_leads.data.length">
                                <div class="col-12 d-flex align-items-center justify-content-center my-5">
                                    <span class="text-muted"> Nenhum Lead Pendente</span>
                                </div>
                            </div>
                            <div class="row" v-else>
                                <div class="col-12 d-flex align-items-center justify-content-center">
                                    <div class="w-100" style="overflow: auto; margin: 0">
                                        <div class="d-flex flex-column" v-infinite-scroll="loadPending" style="overflow: auto; height: 300px">
                                            <div v-for="lead in pending_leads.data" :key="lead.id">{{ lead.name }}</div>
                                            <div
                                                v-if="loading.pending_leads && pending_leads.has_more"
                                                v-loading="loading.pending_leads"
                                                class="py-5 my-3"
                                                element-loading-text="Loading ..."
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="Potenciais">
                            <div class="row">
                                <div class="col-12 d-flex align-items-center justify-content-center my-5">
                                    <span class="text-muted"> Nenhum Lead Potencial</span>
                                </div>
                            </div>
                        </el-tab-pane>
                    </el-tabs>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            filter: '',
            loading: {
                pending_leads: true,
                active_leads: true,
            },
        }
    },
    created() {
        this.init()
    },
    computed: {
        active_leads() {
            return this.$store.state.leads.active
        },
        pending_leads() {
            return this.$store.state.leads.pending
        },
        user() {
            return this.$store.state.user.name
        },
        department() {
            return this.user.department?.name || 'Sem Departamento'
        },
    },
    methods: {
        init() {
            this.loadActive()
            this.loadPending()
        },
        loadActive() {
            if (this.active_leads.has_more) {
                this.loading.active_leads = true
                this.$store.dispatch('getLeads', 'active').then(() => (this.loading.active_leads = false))
            }
        },
        loadPending() {
            if (this.pending_leads.has_more) {
                this.loading.pending_leads = true
                this.$store.dispatch('getLeads', 'pending').then(() => (this.loading.pending_leads = false))
            }
        },
    },
}
</script>