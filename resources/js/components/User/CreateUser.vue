<template>
    <div class="row" id="auth">
        <div class="col-6 d-none d-md-block left-side">
            <div class="d-flex container flex-column d-flex align-items-center justify-content-center">
                <img src="/assets/images/auth/signup.png" class="w-100" />
            </div>
        </div>
        <div class="col-md-6 col-sm-12 right-side" v-loading="loading" element-loading-text="Verificando credenciais ...">
            <div class="container d-flex flex-column justify-content-center">
                <text-logo />
                <b>Cadastro</b>
                <small>Preencha os campos do formulário para efetuar seu cadastro</small>
                <form v-on:submit.prevent="checkUser">
                    <div class="d-flex flex-column mb-2">
                        <label class="label-input">Nome</label>
                        <input class="form-control" v-model="form.name" required />
                    </div>
                    <div class="d-flex flex-column mb-2">
                        <label class="label-input">Email</label>
                        <input class="form-control" v-model="form.email" type="email" required disabled />
                    </div>
                    <div class="d-flex flex-column mb-2">
                        <label class="label-input">Senha</label>
                        <input class="form-control" v-model="form.password" type="password" required />
                    </div>
                    <div class="d-flex flex-column mb-2">
                        <label class="label-input">Confirme a senha</label>
                        <input class="form-control" v-model="form.password_confirmation" type="password" required />
                    </div>
                    <div class="d-flex flex-column mt-3">
                        <button class="btn btn-block btn-primary b-0">Cadastre-se</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<<script>
export default {
	props : ["invite"],
	data() {
		return {
			loading : false,
			form : {
				name : null,
				email : this.invite.email,
				password : "",
				password_confirmation : ""
			},
		}
	},
	methods : {
		selectPolo(id) {
			let loading = this.$loading({text : "Entrando ..."})
			 this.$http.post(`/complete-login`, {...this.form,polo_id : id}).then(({data}) => {   
				if(data.success) return window.location.href = data.route
				this.$message(data.message)
				return loading.close()
            })
		},
		showPolosList(polos) {
			let select_polo = this.$refs["select-polo"]
			select_polo.options = polos.map((x) => ({ key: x.id, label: x.name }))
			select_polo.open()
		},
		checkUser() {
            this.loading = true;
            this.$http.post(this.invite.route, this.form).then(({data}) => {   
                if(!data.success) {
					this.$message(data.message)
					return this.loading = false
				}
				window.location.href = data.route
            }).catch(er => {
				this.loading = false
                this.errors = er.response.data.errors
                this.$validationErrorMessage(er)
			})
		}
	}
}
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