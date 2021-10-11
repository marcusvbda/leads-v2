<template>
    <div>
        <a href="#" @click.prevent="showModal">Ver Conteúdo</a>
        <ElDialog
            title="Conteúdo do Request"
            :visible.sync="visible"
            width="60%"
            top="10"
            @closed="closeHandler"
            :close-on-click-modal="false"
            :close-on-press-escape="false"
        >
            <transition name="fade">
                <div v-if="page == 'json'">
                    <div class="mb-2 mt-0 text-muted" v-if="!approved">
                        Selecione a chave que deverá ser considerada como referência de comparação de polo e em seguida o polo
                        para registros com conteúdo semelhante será enviado.
                    </div>
                    <div v-bind:class="{ clickable: !approved }">
                        <VueJsonPretty :data="content" @click="handleClick" />
                    </div>
                </div>
                <div v-if="page == 'compare'" class="d-flex flex-column">
                    <div class="mb-4 mt-0 text-muted" v-if="!approved">
                        <div class="d-flex flex-row justify-content-end">
                            <a href="#" @click.prevent="clearSelection" class="mb-4">
                                Voltar ao request
                            </a>
                        </div>
                        <div class="mb-2">
                            Todos os requests que o registro <b v-html="clickedIndex" /> for igual ao registro
                            <b v-html="clickedValue" /> serão enviados para o polo ...
                        </div>
                        <div>
                            <el-select
                                v-model="selectedPolo"
                                filterable
                                placeholder="Selecione o polo ..."
                                class="w-100"
                                clearable
                            >
                                <el-option v-for="(polo, i) in polos" :key="i" :label="polo.name" :value="polo.id" />
                            </el-select>
                        </div>
                        <div class="d-flex flex-row mt-3 justify-content-end">
                            <div>
                                <el-button-group>
                                    <el-button type="info" @click="clearSelection">Voltar</el-button>
                                    <el-button type="success" :disabled="!selectedPolo" @click="submit">Salvar</el-button>
                                </el-button-group>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </ElDialog>
    </div>
</template>
<script>
import VueJsonPretty from "vue-json-pretty";
import "vue-json-pretty/lib/styles.css";
export default {
    props: ["content", "approved", "webhook", "tenant_id"],
    data() {
        return {
            visible: false,
            clickedIndex: null,
            clickedValue: null,
            selectedPolo: null,
            page: "json",
            polos: [],
            loading: true
        };
    },
    components: {
        VueJsonPretty
    },
    methods: {
        getPolos() {
            this.loading = true;
            this.$http
                .post("/vstack/json-api", {
                    model: "\\App\\Http\\Models\\Polo",
                    filters: {
                        where: [["tenant_id", "=", this.tenant_id]]
                    }
                })
                .then(({ data }) => {
                    this.polos = data;
                    this.loading = true;
                });
        },
        clearSelection() {
            this.page = "json";
            this.clickedIndex = null;
            this.clickedValue = null;
        },
        closeHandler() {
            this.clearSelection();
            this.page = "json";
        },
        showModal() {
            this.visible = true;
        },
        handleClick(val) {
            if (this.approved) {
                return;
            }
            if (!this.polos.length) {
                this.getPolos();
            }
            this.clickedIndex = val.replace("root.", "");
            this.$nextTick(() => {
                this.clickedValue = this.getRecursiveContentValue();
                if (["object", "array"].includes(typeof this.clickedValue)) {
                    this.$message.error("O conteúdo do registro não pode ser um objeto ou array.");
                    return this.clearSelection();
                }
                this.page = "compare";
            });
        },
        getRecursiveContentValue() {
            let value = this.content;
            let arrayIndexes = this.clickedIndex.split(".");
            arrayIndexes.forEach(index => {
                value = value[index];
            });
            return value;
        },
        submit() {
            this.$loading({ text: "Salvando configuração ..." });
            this.$http
                .post(`/admin/webhooks/${this.webhook.token}/store-settings`, {
                    index: this.clickedIndex,
                    value: this.clickedValue,
                    polo_id: this.selectedPolo
                })
                .then(({ data }) => {
                    if (data.success) {
                        window.location.reload();
                    }
                });
        }
    }
};
</script>
<style lang="scss">
.clickable {
    .vjs-key {
        cursor: pointer;
        &:hover {
            transition: 0.3s;
            border: 3px dashed #6060c5;
        }
    }
}
</style>
