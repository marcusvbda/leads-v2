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
                    <div v-if="hasSelectedValues" class="mb-3 d-flex flex-column">
                        <div class="mb-2">
                            <b>Elementos selecionados :</b>
                        </div>
                        <div>
                            <ElTag
                                v-for="(key, i) in Object.keys(clickedValue)"
                                :key="i"
                                closable
                                @close="handleCloseTag(key)"
                                class="mb-2"
                            >
                                <b>{{ key }}</b> : {{ clickedValue[key] }}
                            </ElTag>
                        </div>
                    </div>
                    <div class="d-flex flex-row mt-3 justify-content-end" v-if="hasSelectedValues">
                        <div>
                            <ElButton type="success" @click="page = 'compare'">Selecionar o Polo</ElButton>
                        </div>
                    </div>
                    <div v-bind:class="{ clickable: !approved }">
                        <VueJsonPretty :data="content" @click="handleClick" />
                    </div>
                </div>
                <div v-if="page == 'compare'" class="d-flex flex-column">
                    <div class="mb-4 mt-0 text-muted" v-if="!approved">
                        <div class="d-flex flex-row justify-content-end">
                            <a href="#" @click.prevent="page = 'json'" class="mb-4">
                                Voltar ao request
                            </a>
                        </div>
                        <div class="mb-2">
                            Todos os requests que conter as <b>tags abaixo </b> serão enviados para o <b>polo selecionado</b>
                        </div>
                        <div class="mb-2">
                            <ElTag v-for="(key, i) in Object.keys(clickedValue)" :key="i" class="mb-2">
                                <b>{{ key }}</b> : {{ clickedValue[key] }}
                            </ElTag>
                        </div>
                        <div>
                            <ElSelect
                                v-model="selectedPolo"
                                filterable
                                placeholder="Selecione o polo ..."
                                class="w-100"
                                clearable
                            >
                                <ElOption v-for="(polo, i) in polos" :key="i" :label="polo.name" :value="polo.id" />
                            </ElSelect>
                        </div>
                        <div class="d-flex flex-row mt-3 justify-content-end">
                            <div>
                                <ElButtonGroup>
                                    <ElButton type="info" @click="page = 'json'">Voltar</ElButton>
                                    <ElButton type="success" :disabled="!selectedPolo" @click="submit">Salvar</ElButton>
                                </ElButtonGroup>
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
            clickedValue: {},
            selectedPolo: null,
            page: "json",
            polos: [],
            loading: true
        };
    },
    components: {
        VueJsonPretty
    },
    created() {
        if (!this.polos.length) {
            this.getPolos();
        }
    },
    computed: {
        hasSelectedValues() {
            return Object.keys(this.clickedValue).length;
        }
    },
    methods: {
        handleCloseTag(key) {
            let newObj = Object.assign({}, this.clickedValue);
            delete newObj[key];
            this.clickedValue = newObj;
        },
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
            this.clickedValue = {};
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
            let index = val.replace("root.", "");
            let value = this.getRecursiveContentValue(index);
            if (["object", "array"].includes(typeof value)) {
                return this.$message.error("Conteúdo do registro selecionado inválido !!");
            }
            this.$message.info("Regra adicionada !!!");
            this.$set(this.clickedValue, index, value);
        },
        getRecursiveContentValue(index) {
            let value = this.content;
            let arrayIndexes = index.split(".");
            arrayIndexes.forEach(index => {
                value = value[index];
            });
            return value;
        },
        submit() {
            this.$loading({ text: "Salvando configuração ..." });
            this.$http
                .post(`/admin/webhooks/${this.webhook.token}/store-settings`, {
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
    .vjs-tree__node {
        cursor: pointer;
    }
}
</style>
