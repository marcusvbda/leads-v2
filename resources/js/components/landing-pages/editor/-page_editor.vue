<template>
    <div class="preview-window">
        <iframe v-if="editor_visible" :src="`/admin/landing-pages/editor/${code}`" width="100%" frameborder="0" />
    </div>
</template>
<script>
export default {
    props: ["code"],
    data() {
        return {
            editor_visible: false,
        };
    },
    created() {
        window.__code__ = this.code;
        this.$nextTick(() => {
            this.editor_visible = true;
        });
    },
};
</script>
<style lang="scss">
.preview-window {
    height: 1000px;
    iframe {
        height: calc(100% - 109px);
        overflow: hidden;
    }
}
</style>
