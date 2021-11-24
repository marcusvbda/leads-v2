<template>
    <div class="row" id="auth" data-aos="fade-down">
        <div class="col-6 d-none d-md-block left-side">
            <div class="d-flex container flex-column d-flex align-items-center justify-content-center">
                <img src="/assets/images/auth/signup.png" class="w-100" />
            </div>
        </div>
        <div class="col-md-6 col-sm-12 right-side" v-loading="loading" element-loading-text="Verificando credenciais ...">
            <div class="container d-flex flex-column justify-content-center">
                <text-logo />
                <b>Login</b>
                <small>Bem Vindo de volta! Efetue o login para continuar</small>
                <form v-on:submit.prevent="confirmUser">
                    <div class="d-flex flex-column">
                        <label class="label-input">Email</label>
                        <input class="form-control" :disabled="true" v-model="form.email" type="email" required />
                    </div>
                    <div class="d-flex flex-column mt-2">
                        <label class="label-input">Nome Completo</label>
                        <input class="form-control" v-model="form.name" required />
                    </div>
                    <div class="d-flex flex-row flex-wrap">
                        <div class="col-md-6 col-sm-12 px-0 mb-3 d-flex flex-column mt-2">
                            <label class="label-input">Senha</label>
                            <input class="form-control" v-model="form.password" type="password" required />
                        </div>
                        <div class="col-md-6 col-sm-12 px-0 mb-3 d-flex flex-column mt-2">
                            <label class="label-input">Confirme a senha</label>
                            <input class="form-control" v-model="form.password_confirmation" type="password" required />
                        </div>
                    </div>
                    <div class="d-flex flex-column mt-3">
                        <button class="btn btn-block btn-primary b-0">Efetuar Login</button>
                        <a href="/esqueci-a-senha" class="f-12 mt-4">Esqueceu a senha ?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ["invite"],
    data() {
        return {
            loading: false,
            form: {
                email: "",
                password: "",
                password_confirmation: "",
                name: ""
            }
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
                        return (window.location.href = "/login");
                    }
                })
                .catch(er => {
                    this.loading = false;
                    this.errors = er.response.data.errors;
                    this.$validationErrorMessage(er);
                });
        }
    }
};
</script>
<style lang="scss" scoped>
#auth {
    &.row {
        height: 100%;
        .left-side {
            height: 100%;
            .container {
                padding: 0 100px;
                height: 100%;
            }
            border-right: 1px solid #efefef;
        }

        .right-side {
            background-color: white;
            .container {
                height: 100%;
                max-width: 500px;
                .title {
                    color: #001737;
                    font-weight: 500;
                    line-height: 1.25;
                }
                small {
                    color: #8392a5;
                    font-size: 12px;
                }
                form {
                    border-top: 1px solid #f1f1f1;
                    margin-top: 10px;
                    padding-top: 10px;
                }
            }
        }
    }
}
</style>
