<template>
    <div class="row template-item px-4">
        <div class="col-md-6 col-sm-12 mb-3" v-for="(item, i) in items" :key="i">
            <div class="card h-100 shadow">
                <div class="card-body">
                    <img :src="item.image" class="w-100" />
                </div>
                <div class="card-footer">
                    {{ item.name }}
                </div>
                <div class="card-overlay">
                    <b>{{ item.name }}</b>
                    <div class="d-flex flex-row mt-3">
                        <button class="btn btn-secondary mx-2 btn-sm px-4" disabled>Pré-Visualizar</button>
                        <button class="btn btn-primary mx-2 btn-sm px-4" @click="selectTemplate(item)">Selecionar</button>
                    </div>
                </div>
            </div>
        </div>
        <el-dialog
            title="Criar Landing Page"
            :visible.sync="visible"
            width="35%"
            :close-on-press-escape="false"
            :close-on-click-modal="false"
        >
            <div class="d-flex flex-column">
                <div class="text-muted">
                    Utilize um nome de fácil memorização. Este nome servirá de indentificação da página.
                </div>
                <div class="mt-3">
                    <b>Nome da Landing Page</b>
                    <div class="input-group">
                        <input class="form-control mt-1" v-bind:class="{ 'is-invalid': errors.name }" v-model="name" />
                        <div class="invalid-feedback" v-if="errors">
                            <ul class="pl-3 mb-0">
                                <li v-for="(e, i) in errors.name" :key="i" v-html="e" />
                            </ul>
                        </div>
                    </div>
                    <small class="text-muted mt-1">
                        não se preocupe com o título da página ou url, você poderá editar isso depois.
                    </small>
                </div>
                <div class="mt-5 d-flex flex-row justify-content-end">
                    <div>
                        <button class="btn btn-secondary btn-sm px-3" @click="visible = false">
                            Cancelar
                        </button>
                        <button class="btn btn-primary ml-1 btn-sm px-3" @click="confirm">
                            Confirmar
                        </button>
                    </div>
                </div>
            </div>
        </el-dialog>
    </div>
</template>
<script>
export default {
    props: ["items"],
    data() {
        return {
            visible: false,
            selected: null,
            name: null,
            errors: {}
        };
    },
    methods: {
        selectTemplate(item) {
            this.selected = item;
            this.name = null;
            this.visible = true;
        },
        makeFormValidationErrors(er) {
            let errors = er.response.data.errors;
            this.errors = errors;
            try {
                let message = Object.keys(errors)
                    .map(key => `<li>${errors[key][0]}</li>`)
                    .join("");
                this.$message({
                    dangerouslyUseHTMLString: true,
                    showClose: true,
                    message: `<ul>${message}</ul>`,
                    type: "error"
                });
            } catch {
                return;
            }
        },
        confirm() {
            this.$confirm(`Confirma o cadastro ?`, "Confirmação", {
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
                type: "warning"
            }).then(() => {
                let loading = this.$loading({ text: "Salvando ..." });
                this.$http
                    .post("/admin/landing-pages/store", {
                        name: this.name,
                        template: this.template,
                        clicked_btn: "save_and_back",
                        resource_id: "landing-pages"
                    })
                    .then(({ data }) => {
                        if (data.success) {
                            return (window.location.href = data.route);
                        } else {
                            if (data.message) {
                                this.$message({ showClose: true, message: data.message.text, type: data.message.type });
                            }
                            loading.close();
                        }
                    })
                    .catch(er => {
                        this.makeFormValidationErrors(er);
                        loading.close();
                    });
            });
        }
    }
};
</script>
<style lang="scss">
.template-item {
    position: relative;
    cursor: pointer;
    .card-overlay {
        position: absolute;
        background-color: white;
        flex-direction: column;
        left: 0;
        right: 0;
        bottom: 0;
        top: 0;
        display: none;
    }

    .card {
        &:hover {
            transition: 0.5s;
            .card-overlay {
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }
    }
}
</style>
