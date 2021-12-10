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
                    <small class="text-muted f-12 mb-2">Filtro por nome, email, telefone ou origem</small>
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
                        :options="statuses.map(x => ({ label: x.name, value: String(x.id) }))"
                    />
                    <template>
                        <el-select v-model="date_type" class="w-100 mt-2">
                            <el-option label="data customizada" value="custom" />
                            <el-option label="todas as datas" value="all" />
                            <el-option v-for="(key, i) in Object.keys(preset_date)" :label="key" :value="i" :key="i" />
                        </el-select>
                        <el-date-picker
                            class="w-100 mt-2"
                            v-if="showScheduleFilter && date_type == 'custom'"
                            v-model="filter.schedule"
                            type="datetimerange"
                            range-separator=" - "
                            start-placeholder=""
                            end-placeholder=""
                            format="dd/MM/yyyy"
                            value-format="yyyy-MM-dd"
                        />
                    </template>
                    <el-select v-model="filter.date_index" class="w-100 mt-2" v-if="date_type != 'all'">
                        <el-option label="Data de Criação" value="DATE(created_at)" />
                        <el-option label="Data de Agendamento" value="DATE(json_unquote(json_extract(data,'$.schedule')))" />
                    </el-select>

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
                                        <div
                                            class="d-flex flex-column"
                                            v-infinite-scroll="getLeads"
                                            style="overflow: auto; height: 300px"
                                        >
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
                        <el-tab-pane
                            v-if="user.department_id"
                            v-loading="!initialized"
                            element-loading-text="Inicializando..."
                            name="pending"
                        >
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
                                        <div
                                            class="d-flex flex-column"
                                            v-infinite-scroll="getLeads"
                                            style="overflow: auto; height: 300px"
                                        >
                                            <lead-card
                                                v-for="(lead, i) in pending_leads.data"
                                                :lead="lead"
                                                :key="`pending_${i}`"
                                            />
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
                        <el-tab-pane v-loading="!initialized" element-loading-text="Inicializando..." name="potential">
                            <span slot="label">
                                Potenciais
                                <template v-if="potential_leads.total"> ({{ potential_leads.total }}) </template>
                            </span>
                            <div class="row" v-if="!potential_leads.data.length">
                                <div class="col-12 d-flex align-items-center justify-content-center my-5">
                                    <span class="text-muted"> Nenhum Lead Pendente</span>
                                </div>
                            </div>
                            <div class="row" v-else>
                                <div class="col-12 d-flex align-items-center justify-content-center">
                                    <div class="w-100" style="overflow: auto; margin: 0">
                                        <div
                                            class="d-flex flex-column"
                                            v-infinite-scroll="getLeads"
                                            style="overflow: auto; height: 300px"
                                        >
                                            <lead-card
                                                v-for="(lead, i) in potential_leads.data"
                                                :lead="lead"
                                                :key="`pending_${i}`"
                                            />
                                            <div
                                                v-if="loading_leads.potential && potential_leads.has_more"
                                                v-loading="loading_leads.potential"
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
import { mapActions, mapGetters, mapMutations } from "vuex";
export default {
    props: ["preset_date"],
    data() {
        return {
            timeout: null,
            loading: {
                statuses: true
            },
            initialized: false,
            date_type: "all"
        };
    },
    components: {
        "lead-card": require("./-lead-card.vue").default
    },
    created() {
        this.loadStatus().then(() => {
            this.getLeads(true);
        });
    },
    watch: {
        filter: {
            handler() {
                if (this.initialized) {
                    clearTimeout(this.timeout);
                    this.timeout = setTimeout(() => {
                        this.initialized = false;
                        this.getLeads(true);
                    }, 500);
                }
            },
            deep: true
        },
        date_type(val) {
            if (["custom"].includes(val)) {
                return;
            }
            if (["all"].includes(val)) {
                return (this.filter.schedule = []);
            }
            let presetKeys = Object.keys(this.preset_date);
            this.filter.schedule = this.preset_date[presetKeys[val]];
        }
    },
    computed: {
        ...mapGetters("lead", ["showScheduleFilter", "statuses", "user"]),
        ...mapGetters("lead", {
            loading_leads: "loading",
            active_leads: "active",
            pending_leads: "pending",
            potential_leads: "potential"
        }),
        filter: {
            set(val) {
                return this.setFilter(val);
            },
            get() {
                return this.$store.state.lead.filter;
            }
        },
        tab: {
            set(val) {
                return this.setTab(val);
            },
            get() {
                return this.$store.state.lead.tab;
            }
        },
        department() {
            return this.user.department?.name || "Sem Departamento";
        }
    },
    methods: {
        ...mapActions("lead", ["getStatuses", "loadLeads", "reloadAllLeads"]),
        ...mapMutations("lead", ["setFilter", "setTab"]),
        getLeads(refresh = false) {
            if (!this.initialized) {
                this.reloadAllLeads().then(() => {
                    this.initialized = true;
                });
            } else {
                this.loadLeads({ refresh, type: this.tab });
            }
        },
        async loadStatus() {
            let rows = await this.getStatuses();
            this.filter.status_ids = rows
                .filter(x =>
                    ["schedule", "waiting", "interest", "interest_with_objection", "neutral", "neutral_with_objection"].includes(
                        x.value
                    )
                )
                .map(x => String(x.id));
            this.loading.statuses = false;
            return this.filter.status_ids;
        }
    }
};
</script>
