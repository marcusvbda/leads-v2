<template>
    <div class="col-md-4 col-sm-12">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex flex-column">
                    <b class="mb-3 d-flex align-items-center">
                        <span class="el-icon-user-solid mr-2" />
                        {{ user.name }}
                        <small class="ml-auto text-muted">{{ department }}</small>
                    </b>
                    <el-input placeholder="Pesquisar..." suffix-icon="el-icon-search" v-model="filter.text" clearable />
                    <el-select-all
                        v-if="!loading.statuses"
                        v-model="filter.status_ids"
                        class="mt-2"
                        filterable
                        multiple
                        collapse-tags
                        placeholder="Selecione o status"
                        label="Todos os Status"
                        clearable
                        :options="statuses.map((x) => ({ label: x.name, value: String(x.id) }))"
                    />
                    <el-tabs class="mt-3" v-model="tab">
                        <el-tab-pane v-loading="!initialized" element-loading-text="Inicializando..." name="active">
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
                                        <div class="d-flex flex-column" v-infinite-scroll="getLeads" style="overflow: auto; height: 300px">
                                            <lead-card v-for="(lead, i) in active_leads.data" :lead="lead" :key="`active_${i}`" />
                                            <div
                                                v-if="loading_leads.active && active_leads.has_more"
                                                v-loading="loading_leads.active"
                                                class="py-5 my-3"
                                                element-loading-text="Loading ..."
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane v-loading="!initialized" element-loading-text="Inicializando..." name="pending">
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
                                        <div class="d-flex flex-column" v-infinite-scroll="getLeads" style="overflow: auto; height: 300px">
                                            <lead-card v-for="(lead, i) in pending_leads.data" :lead="lead" :key="`pending_${i}`" />
                                            <div
                                                v-if="loading_leads.pending && pending_leads.has_more"
                                                v-loading="loading_leads.pending"
                                                class="py-5 my-3"
                                                element-loading-text="Loading ..."
                                            />
                                        </div>
                                    </div>
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
            timeout: null,
            loading: {
                statuses: true,
            },
            initialized: false,
        }
    },
    components: {
        'lead-card': require('./-lead-card.vue').default,
    },
    created() {
        this.loadStatus().then((ids) => {
            this.getLeads(true)
        })
    },
    watch: {
        filter: {
            handler(val) {
                if (this.initialized) {
                    clearTimeout(this.timeout)
                    this.timeout = setTimeout(() => {
                        this.initialized = false
                        this.getLeads(true)
                    }, 500)
                }
            },
            deep: true,
        },
    },
    computed: {
        filter: {
            set(val) {
                return this.$store.commit('setFilter', val)
            },
            get() {
                return this.$store.state.filter
            },
        },
        tab: {
            set(val) {
                return this.$store.commit('setTab', val)
            },
            get() {
                return this.$store.state.tab
            },
        },
        loading_leads() {
            return this.$store.state.loading
        },
        statuses() {
            return this.$store.state.statuses
        },
        active_leads() {
            return this.$store.state.leads.active
        },
        pending_leads() {
            return this.$store.state.leads.pending
        },
        user() {
            return this.$store.state.user
        },
        department() {
            return this.user.department?.name || 'Sem Departamento'
        },
    },
    methods: {
        getLeads(refresh = false) {
            if (!this.initialized) {
                this.$store.dispatch('reloadAllLeads').then(() => {
                    this.initialized = true
                })
            } else {
                this.$store.dispatch('loadLeads', { refresh, type: this.tab })
            }
        },
        async loadStatus() {
            let rows = await this.$store.dispatch('getStatuses')
            this.filter.status_ids = rows
                .filter((x) => ['schedule', 'waiting', 'interest', 'interest_with_objection', 'neutral', 'neutral_with_objection'].includes(x.value))
                .map((x) => String(x.id))
            this.loading.statuses = false
            return this.filter.status_ids
        },
    },
}
</script>