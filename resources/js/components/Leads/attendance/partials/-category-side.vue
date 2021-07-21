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
                        <el-tab-pane label="Ativos">
                            <div class="row">
                                <div class="col-12 d-flex align-items-center justify-content-center my-5">
                                    <span class="text-muted"> Nenhum Lead Ativo</span>
                                </div>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="Pendentes">
                            <div class="row">
                                <div class="col-12 d-flex align-items-center justify-content-center my-5">
                                    <span class="text-muted"> Nenhum Lead Pendente</span>
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
        }
    },
    computed: {
        user() {
            return this.$store.state.user.name
        },
        department() {
            return this.user.department?.name || 'Sem Departamento'
        },
    },
}
</script>