<template>
    <div
        class="w-10/12 flex flex-col py-5"
        v-loading="loading"
        element-loading-text="Verificando credenciais"
    >
        <text-logo />
        <b class="mt-4">Login</b>
        <small>Bem Vindo de volta! Efetue o login para continuar</small>
        <form v-on:submit.prevent="checkUser" class="vstack-form">
            <div class="flex flex-col mt-8">
                <label class="form-label">Email</label>
                <input
                    class="form-input"
                    v-model="form.email"
                    type="email"
                    required
                />
            </div>
            <div class="flex flex-col mt-2">
                <label class="form-label">Senha</label>
                <input
                    class="form-input"
                    v-model="form.password"
                    type="password"
                    required
                />
            </div>
            <div class="flex flex-col mt-3">
                <button class="vstack-btn primary">Efetuar Login</button>
                <a href="/esqueci-a-senha" class="my-3 text-sm">
                    Esqueceu a senha ?
                </a>
            </div>
        </form>
        <select-dialog
            ref="select-polo"
            title="Selecione a empresa que deseja logar"
            btn_text="Entrar"
            @selected="selectPolo"
        />
    </div>
</template>
<script>
export default {
    data() {
        return {
            loading: false,
            form: {
                email: '',
                password: '',
            },
        };
    },
    methods: {
        selectPolo(id) {
            let loading = this.$loading({ text: 'Entrando ...' });
            this.$http
                .post(`/complete-login`, { ...this.form, polo_id: id })
                .then(({ data }) => {
                    if (data.success)
                        return (window.location.href = data.route);
                    this.$message(data.message);
                    return loading.close();
                });
        },
        showPolosList(polos) {
            let select_polo = this.$refs['select-polo'];
            select_polo.options = polos.map((x) => ({
                key: x.id,
                label: x.name,
            }));
            select_polo.open();
        },
        checkUser() {
            this.loading = true;
            this.$http
                .post(`/login`, this.form)
                .then(({ data }) => {
                    if (!data.success) {
                        this.$message(data.message);
                        return (this.loading = false);
                    } else {
                        if (data.polos) {
                            this.loading = false;
                            if (data.polos.length == 1) {
                                return this.selectPolo(data.polos[0].id);
                            }
                            this.showPolosList(data.polos);
                        }
                    }
                })
                .catch((er) => {
                    this.loading = false;
                    this.errors = er.response.data.errors;
                    this.$validationErrorMessage(er);
                });
        },
    },
};
</script>
