<template>
    <div
        class="w-10/12 flex flex-col"
        v-loading="loading"
        element-loading-text="Verificando credenciais"
    >
        <text-logo />
        <b class="mt-4 dark:text-neutral-200">Renove sua senha</b>
        <small class="dark:text-neutral-300">
            Digite o seu Email cadastrado e iremos enviar um link para recuperar
            sua senha
        </small>
        <form v-on:submit.prevent="submit" class="vstack-form">
            <div class="flex flex-col mt-8">
                <label class="form-label">Email</label>
                <input
                    class="form-input"
                    v-model="form.email"
                    type="email"
                    required
                />
            </div>
            <div class="flex flex-col mt-3">
                <button class="vstack-btn primary">
                    Me enviar um link de renovação
                </button>
                <a href="/login" class="my-3 text-sm vstack-link">
                    Voltar ao login
                </a>
            </div>
        </form>
    </div>
</template>
<script>
export default {
    data() {
        return {
            loading: false,
            form: {
                email: '',
            },
        };
    },
    methods: {
        submit() {
            this.loading = true;
            this.$http
                .post(`/esqueci-a-senha`, this.form)
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
