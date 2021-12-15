<template>
    <iframe class="advanced-iframe" :src="src" frameborder="0" scrolling="no" ref="iframe" @load="resizeIframe" />
</template>
<script>
export default {
    props: ["src"],
    methods: {
        resizeIframe() {
            let height = this.$refs.iframe.contentWindow.document.body.scrollHeight + "px";
            this.$refs.iframe.style.height = height;
        },
    },
};
</script>
<style lang="scss">
.advanced-iframe {
    width: 100%;
}
</style>
