<template>
    <div class="row">
        <div class="col-md-3 col-sm-12">
            <ElTree class="tree-logs" :data="tree" @node-click="handleNodeClick" />
        </div>
        <div class="col-md-9 col-sm-12">
            <div v-if="is_loading" class="shimmer is-loading" />
            <template v-else-if="selected_node_content">
                <PrismEditor
                    class="my-editor"
                    v-model="selected_node_content"
                    :highlight="highlighter"
                    :readonly="true"
                    :lineNumbers="true"
                />
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
import { PrismEditor } from "vue-prism-editor";
import "vue-prism-editor/dist/prismeditor.min.css";
import { highlight, languages } from "prismjs/components/prism-core";
import "prismjs/components/prism-clike";
import "prismjs/components/prism-javascript";
import "prismjs/themes/prism-tomorrow.css";

export default {
    props: ["tree"],
    data() {
        return {
            is_loading: false,
            selected_node_content: null,
            dialog_visible: false,
            selected_node: "",
        };
    },
    components: {
        PrismEditor,
    },
    methods: {
        highlighter(code) {
            return highlight(code, languages.js);
        },
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
            this.$http
                .post(`/admin/log-viewer/get-content`, node)
                .then(({ data }) => {
                    this.selected_node_content = data;
                    this.is_loading = false;
                })
                .catch((er) => {
                    this.$message.error(er.message);
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
.my-editor {
    background: white;
    font-family: Fira code, Fira Mono, Consolas, Menlo, Courier, monospace;
    font-size: 14px;
    line-height: 1.5;
    padding: 5px;
}

// optional
.prism-editor__textarea:focus {
    outline: none;
}

// not required:
.height-300 {
    height: 300px;
}
</style>
