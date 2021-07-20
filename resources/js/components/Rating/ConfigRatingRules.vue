<template>
    <form @submit.prevent="submit">
        <div class="row mt-2">
            <div class="col-12">
                <div class="d-flex flex-row justify-content-between mb-3">
                    <h4><span class="el-icon-star-on mr-2"></span> Configuração de Regra de Classificação</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div id="Informações Básicas">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="row-responsive-table">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="w-25">Descrição</th>
                                                    <th class="w-75">Peso</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="mb-3" v-for="(key, i) in Object.keys(rating_rules)" :key="i">
                                                    <td>{{ key }}</td>
                                                    <td>
                                                        <h1>
                                                            <el-rate
                                                                v-model="rating_rules[key]"
                                                                show-score
                                                                text-color="#ff9900"
                                                                :max="20"
                                                                score-template="{value} ponto(s)"
                                                            />
                                                        </h1>
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
        </div>
        <div class="row">
            <div class="col-12 d-flex justify-content-end d-flex align-items-center flex-wrap">
                <button type="submit" class="btn btn-primary btn-sm-block">Salvar</button>
            </div>
        </div>
    </form>
</template>
<script>
export default {
    props: ['rating_rule'],
    data() {
        return {
            rating_rules: this.rating_rule,
        }
    },
    methods: {
        submit() {
            this.$loading()
            this.$http.post(window.location.pathname, this.rating_rules).then(({ data }) => {
                window.location.reload()
            })
        },
    },
}
</script>
<style lang="scss" >
h1 {
    .el-rate__item {
        .el-rate__icon {
            font-size: 30px !important;
        }
    }
}
</style>