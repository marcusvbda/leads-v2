<template>
    <div class="row mb-5">
        <div class="col-12">
            <form class="needs-validation m-0" novalidate v-on:submit.prevent="submit" @keypress.13.prevent>
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card box-shadow">
                            <div class="card-header crud-card-header d-flex align-items-center">
                                <b>Informações Básicas</b>
                            </div>
                            <div class="card-body p-0">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row-responsive-table">
                                            <table class="table table-crud mb-0">
                                                <tbody>
                                                    <v-input
                                                        label="Nome *"
                                                        description="Nome de exibição do grupo de acesso"
                                                        v-model="form.description"
                                                        :errors="errors.description ? errors.description : false"
                                                    />
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3" v-for="(chunked_permissions, group) in permissions_groups" :key="group">
                    <div class="col-12">
                        <div class="card box-shadow">
                            <div class="card-header crud-card-header d-flex align-items-center">
                                <label class="d-flex flex-row align-items-center mb-0">
                                    <input type="checkbox" v-model="check_all[group]" class="mr-2" @change="selectAll(group)" :ref="`check_${group}`" />
                                    Permissões
                                    <template v-if="group">
                                        de
                                        <b class="ml-1">{{ group }}</b>
                                    </template>
                                    <template v-else>
                                        <b class="ml-1">[Sem Agrupamento]</b>
                                    </template>
                                </label>
                            </div>
                            <div class="card-body f-12">
                                <div class="row d-flex flex-wrap flex-row mb-4" v-for="(cp, i) in chunked_permissions" :key="i">
                                    <div class="col-md-3 col-sm-12" v-for="(p, i) in cp" :key="`${group}_${i}`">
                                        <label class="d-flex flex-row align-items-center mb-0">
                                            <input type="checkbox" v-model="form.permissions[p.name]" class="mr-2" @change="verifyAll(group)" />
                                            <div class="d-flex flex-column">
                                                <b>{{ p.description }}</b>
                                                <!-- <small class="f-12">{{p.name}}</small> -->
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3 mb-5">
                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
export default {
    props: ['role', 'permissions_groups'],
    data() {
        return {
            loading: false,
            form: {
                id: this.role ? this.role.id : null,
                description: this.role ? this.role.description : null,
                permissions: this.role ? this.role.processed_permissions : {},
            },
            check_all: {},
            errors: {},
        }
    },
    computed: {
        isEditMode() {
            return this.form.id ? true : false
        },
    },
    mounted() {
        this.initModels()
    },
    methods: {
        verifyAll(group) {
            let chunk_permissions = this.permissions_groups[group]
            chunk_permissions = Object.assign({}, chunk_permissions)
            let all_true = false
            let result = Object.keys(chunk_permissions).map((row) => {
                let permissions = Object.assign({}, chunk_permissions[row])
                return Object.keys(permissions).filter((y) => !this.form.permissions[permissions[y].name]).length == 0
            })
            let qty = result.filter((x) => !x).length
            all_true = qty == 0
            this.check_all[group] = all_true
        },
        selectAll(group) {
            this.permissions_groups[group].map((chunk) => {
                chunk = Object.assign({}, chunk)
                Object.keys(chunk).map((row) => {
                    this.$set(this.form.permissions, chunk[row].name, this.check_all[group])
                })
            })
        },
        initModels() {
            let groups = Object.keys(this.permissions_groups)
            groups.map((group) => {
                let permissions_list = this.permissions_groups[group]
                permissions_list.map((permissions) => {
                    permissions = Object.assign({}, permissions)
                    if (!this.role) Object.keys(permissions).map((p) => this.$set(this.form.permissions, permissions[p].name, false))
                    this.$set(this.check_all, group, false)
                    this.verifyAll(group)
                })
            })
        },
        submit() {
            let loading = this.$loading()
            this.$http
                .post('/admin/grupos-de-acesso/store', this.form)
                .then((resp) => {
                    resp = resp.data
                    this.errors = []
                    if (resp.success) window.location.href = '/admin/grupos-de-acesso'
                })
                .catch((er) => {
                    loading.close()
                    this.errors = er.response.data.errors
                    this.$validationErrorMessage(er)
                })
        },
    },
}
</script>