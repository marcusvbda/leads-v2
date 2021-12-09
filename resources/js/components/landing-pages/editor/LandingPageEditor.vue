<template>
    <el-tabs v-model="tab" class="h-100">
        <el-tab-pane name="editor" label="Editor">Editor aqui</el-tab-pane>
        <el-tab-pane name="settings" label="Configurações">
            <slot name="resource-crud" />
        </el-tab-pane>
        <el-tab-pane name="publish" label="Publicação">Infos de Publicação</el-tab-pane>
    </el-tabs>
</template>
<script>
export default {
    props: ["data"],
    data() {
        return {
            tab: this.$getUrlParams().tab || "editor"
        };
    },
    created() {
        console.log(this.data);
    }
};
</script>
