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
                                                    <v-input label="Nome *" v-model="form.name" :errors="errors.name ? errors.name : false" />
                                                    <template v-if="is_admin">
                                                        <tr>
                                                            <td class="w-25">
                                                                <span class="input-title">Selecionar Polos *</span>
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
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3" v-if="show_password">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header px-3">
                                <el-switch active-text="Alterar a senha" v-model="form.change_password" />
                            </div>
                            <div class="card-body p-0" v-if="form.change_password">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row-responsive-table">
                                            <table class="table table-crud mb-0">
                                                <tbody>
                                                    <v-input
                                                        type="password"
                                                        label="Senha *"
                                                        v-model="form.password"
                                                        :errors="errors.password ? errors.password : false"
                                                    />
                                                    <v-input
                                                        type="password"
                                                        label="Confirme a senha *"
                                                        v-model="form.password_confirm"
                                                        :errors="errors.password_confirm ? errors.password_confirm : false"
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
    props: ['user', 'logged', 'roles', 'polos', 'polos_ids'],
    data() {
        return {
            loading: false,
            form: {
                id: this.user.id,
                name: this.user.name,
                polos: this.polos_ids,
                role_id: this.user.role_id,
                change_password: false,
                password: null,
                password_confirm: null,
            },
            errors: {},
            loading_avatar: false,
        }
    },
    computed: {
        name() {
            return `${this.form.firstname} ${this.form.lastname}`
        },
        show_password() {
            return this.logged.id == this.user.id
        },
        is_admin() {
            return this.polos.length > 0 && this.roles.length > 0
        },
    },
    methods: {
        submit() {
            let loading = this.$loading()
            this.$http
                .post('/admin/profile', this.form)
                .then((resp) => {
                    resp = resp.data
                    this.errors = []
                    if (resp.success) {
                        if (this.hide_password == undefined) return window.location.reload()
                        return (window.location.href = '/admin/usuarios')
                    }
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