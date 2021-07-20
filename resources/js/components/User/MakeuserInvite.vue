<template>
    <div class="row mb-5" id="crud-view">
        <div class="col-12">
            <form class="needs-validation m-0" novalidate v-on:submit.prevent="submit" @keypress.13.prevent>
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card box-shadow">
                            <div class="card-header crud-card-header d-flex align-items-center">
                                <b class="crud-title">Informações Básicas</b>
                            </div>
                            <div class="card-body p-0">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row-responsive-table">
                                            <table class="table table-crud mb-0">
                                                <tbody>
                                                    <v-input
                                                        label="Email *"
                                                        description="Email para qual o convite será enviado"
                                                        v-model="form.email"
                                                        :errors="errors.email ? errors.email : false"
                                                    />
                                                    <tr>
                                                        <td class="w-25">
                                                            <el-switch v-model="select_all" active-text="Todos os Polos" inactive-text="Selecionar os Polos" />
                                                            *
                                                        </td>
                                                        <td>
                                                            <div class="d-flex flex-column">
                                                                <div class="input-group">
                                                                    <el-select
                                                                        v-model="form.polos"
                                                                        filterable
                                                                        required
                                                                        class="w-100"
                                                                        multiple
                                                                        placeholder="Selecione os polos que este usuário possui"
                                                                    >
                                                                        <el-option v-for="(t, i) in polos" :key="i" :label="t.name" :value="t.id" />
                                                                    </el-select>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="w-25">
                                                            <span class="input-title">Grupo de Acesso *</span>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex flex-column">
                                                                <div class="input-group">
                                                                    <el-select
                                                                        v-model="form.role_id"
                                                                        filterable
                                                                        required
                                                                        class="w-100"
                                                                        placeholder="Selecione o grupo de acesso que este usuário deve ter"
                                                                    >
                                                                        <el-option v-for="(r, i) in roles" :key="i" :label="r.description" :value="r.id" />
                                                                    </el-select>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3 mb-5">
                    <div class="col-12 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Enviar Convite</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
export default {
    props: ['roles', 'polos'],
    data() {
        return {
            loading: false,
            form: {
                email: null,
                role_id: null,
                polos: [],
            },
            errors: {},
            select_all: false,
        }
    },
    watch: {
        select_all(val) {
            if (val) this.form.polos = this.polos.map((x) => x.id)
            else this.form.polos = []
        },
    },
    methods: {
        submit() {
            let loading = this.$loading()
            this.$http
                .post('/admin/usuarios/send_invite', this.form)
                .then((resp) => {
                    resp = resp.data
                    this.errors = []
                    if (resp.success) window.location.href = '/admin/usuarios'
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