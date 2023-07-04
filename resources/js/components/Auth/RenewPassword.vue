<template>
    <div
        class="w-10/12 flex flex-col py-5"
        v-loading="loading"
        element-loading-text="Verificando credenciais"
    >
        <text-logo />
        <b class="mt-4">Renove sua senha</b>
        <small>Digite a nova senha e a confirme para renovar</small>
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
            <div class="flex flex-col mt-2">
                <label class="form-label">Confirme a senha</label>
                <input
                    class="form-input"
                    v-model="form.password_confirmation"
                    type="password"
                    required
                />
            </div>
            <div class="flex flex-col mt-3">
                <button class="vstack-btn primary">Renovar a senha</button>
                <a href="/login" class="my-3 text-sm">Voltar ao login</a>
            </div>
        </form>
    </div>
</template>
<script>
export default {
    props: ['token'],
    data() {
        return {
            loading: false,
            form: {
                password: '',
                password_confirmation: '',
            },
        };
    },
    methods: {
        submit() {
            this.loading = true;
            this.$http
                .post(`/esqueci-a-senha/${this.token}`, this.form)
                .then(({ data }) => {
                    if (!data.success) {
                        this.$message(data.message);
                        return (this.loading = false);
                    }
                    window.location.href = data.route;
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
