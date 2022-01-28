<template>
    <div class="row">
        <div class="col-md-3 col-sm-12">
            <ElTree class="tree-logs" :data="tree" @node-click="handleNodeClick" />
        </div>
        <div class="col-md-9 col-sm-12">
            <div v-if="is_loading" class="shimmer is-loading" />
            <template v-else-if="selected_node_content_rows">
                <ElTable :data="filtered_data" style="width: 100%">
                    <ElTableColumn label="Conteúdo" prop="content">
                        <template slot-scope="{ row }">
                            <code v-html="truncate(row.content, 200)" />
                        </template>
                    </ElTableColumn>
                    <ElTableColumn align="right" width="250">
                        <template slot="header" slot-scope="scope">
                            <ElInput v-model="search_filter" size="mini" placeholder="Pesquisar ..." clearable />
                        </template>
                        <template slot-scope="{ row }">
                            <ElButton icon="el-icon-search" size="mini" @click="view_details(row)" />
                        </template>
                    </ElTableColumn>
                </ElTable>
            </template>
            <template v-else>
                <div class="text-center my-5">
                    <small class="text-muted">Seleciona o arquivo que deseja visualizar</small>
                </div>
            </template>
        </div>
        <ElDialog :visible.sync="dialog_visible" width="80%">
            <code v-html="selected_node" />
        </ElDialog>
    </div>
</template>
<script>
import VueJsonPretty from "vue-json-pretty";
import "vue-json-pretty/lib/styles.css";
export default {
    props: ["tree"],
    data() {
        return {
            is_loading: false,
            selected_node_content_rows: null,
            search: "",
            search_filter: "",
            interval: null,
            dialog_visible: false,
            selected_node: "",
        };
    },
    components: {
        VueJsonPretty,
    },
    watch: {
        search_filter(val) {
            clearInterval(this.interval);
            this.interval = setInterval(() => {
                this.search = val;
                clearInterval(this.interval);
            }, 500);
        },
    },
    computed: {
        filtered_data() {
            let search = this.search;
            return this.selected_node_content_rows.filter(
                (data) => !search || data.content.toLowerCase().includes(search.toLowerCase())
            );
        },
    },
    methods: {
        view_details(node) {
            this.selected_node = node.content;
            this.dialog_visible = true;
        },
        handleNodeClick(node) {
            if (node.type == "file") {
                this.getContent(node);
            }
        },
        getContent(node) {
            this.is_loading = true;
            this.$http.post(`/admin/log-viewer/get-content`, node).then(({ data }) => {
                this.selected_node_content_rows = data;
                this.is_loading = false;
            });
        },
        truncate(text, len) {
            if (text.length > len) {
                return text.substring(0, len) + "...";
            }
            return text;
        },
    },
};
</script>
<style lang="scss">
.tree-logs {
    .is-current {
        background-color: #f5f7fa;
    }
}

.shimmer.is-loading {
    height: 800px;
    width: 100%;
}

.el-table__row {
    .is-right {
    }
}
</style>
