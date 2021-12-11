<template>
    <div class="preview-window">
        <iframe v-show="started" @load="onLoadIframe" ref="iframe" src="/vstack/grapes-editor" width="100%" frameborder="0" />
        <div class="shimmer w-100" style="height: 1000px" v-if="!started" />
    </div>
</template>
<script>
import presetWebPage from "grapesjs-preset-webpage";

export default {
    props: ["code"],
    data() {
        return {
            started: false,
            iframeWindow: null,
            content: {
                html: '<h1 id="iw3y">Hello World TESTE</h1>',
                css: `{ box-sizing: border-box; } body
                {margin: 0;}#iw3y{font-family:Helvetica, sans-serif;font-size:39px;color:#1639ba;}`,
            },
        };
    },
    methods: {
        onLoadIframe() {
            this.initiVariables();
            this.setGrapesOptions();
            Promise.all([this.startEditor(), this.createExtraBlocks(), this.setInitialValues(), this.createModel()]).then(() => {
                this.started = true;
            });
        },
        setGrapesOptions() {
            this.iframeWindow.grapesOptions = {
                showOffsets: 1,
                noticeOnUnload: 0,
                fromElement: true,
                storageManager: { autoload: 0 },
                plugins: [presetWebPage],
                assetManager: {
                    headers: { "X-CSRF-TOKEN": laravel.general.csrf_token ? laravel.general.csrf_token : "" },
                    upload: laravel.vstack.default_upload_route,
                },
            };
        },
        initiVariables() {
            this.iframeWindow = this.$refs.iframe.contentWindow;
        },
        startEditor() {
            this.iframeWindow.startEditor();
        },
        createExtraBlocks() {
            // this.iframeWindow.grapesEditor.BlockManager.add("Hello-World", {
            //     label: "Hello World",
            //     attributes: { class: "gjs-fonts gjs-f-text" },
            //     content: `<h1>Hello World</h1>`,
            // });
        },
        setInitialValues() {
            this.iframeWindow.grapesEditor.setComponents(this.content.html);
            this.iframeWindow.grapesEditor.setStyle(this.content.css);
        },
        createModel() {
            this.iframeWindow.grapesEditor.on("change:changesCount", () => {
                if (this.started) {
                    this.$set(this.content, "html", this.iframeWindow.grapesEditor.getHtml());
                    this.$set(this.content, "css", this.iframeWindow.grapesEditor.getCss());
                }
            });
        },
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
