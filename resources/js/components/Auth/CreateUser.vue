<template>
    <div
        class="w-10/12 flex flex-col py-5"
        v-loading="loading"
        element-loading-text="Verificando credenciais"
    >
        <text-logo />
        <b class="mt-4 dark:text-neutral-200">Cadastro de Usuário</b>
        <small class="dark:text-neutral-300">Cadastre-se para ter acesso</small>
        <form v-on:submit.prevent="checkUser" class="vstack-form">
            <div class="flex flex-col mt-8">
                <label class="form-label">Email</label>
                <input
                    class="form-input"
                    :value="form.email"
                    disabled
                    type="email"
                    required
                />
            </div>
            <div class="flex flex-col mt-8">
                <label class="form-label">Nome</label>
                <input
                    class="form-input"
                    v-model="form.name"
                    type="text"
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
            <div class="flex flex-col my-3">
                <button class="vstack-btn primary">Cadastrar</button>
            </div>
        </form>
    </div>
</template>
<script>
export default {
    props: ['invite'],
    data() {
        return {
            loading: false,
            form: {
                email: '',
                password: '',
                password_confirmation: '',
                name: '',
            },
        };
    },
    created() {
        this.form.email = this.invite.email;
    },
    methods: {
        confirmUser() {
            this.loading = true;
            this.$http
                .post(window.location.pathname, this.form)
                .then(({ data }) => {
                    if (data.success) {
                        return (window.location.href = '/login');
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
