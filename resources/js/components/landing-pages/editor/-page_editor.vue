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
            content: `<style>* { box-sizing: border-box; } body {margin: 0;}*{box-sizing:border-box;}body{margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;}#iw3y{font-family:Helvetica, sans-serif;font-size:39px;color:rgb(22, 57, 186);}.row{display:table;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;width:100%;}.cell{width:8%;display:table-cell;height:75px;}#i87o{padding:10px;color:#e022b9;}@media (max-width: 768px){.cell{width:100%;display:block;}}</style><h1 id="iw3y">Hello World TESTE</h1><div class="row"><div class="cell"></div></div><div id="i87o">Insert your text here</div>`,
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
            this.iframeWindow.grapesEditor.setComponents(this.content);
        },
        createModel() {
            this.iframeWindow.grapesEditor.on("change:changesCount", () => {
                if (this.started) {
                    let css = this.iframeWindow.grapesEditor.getCss();
                    let html = this.iframeWindow.grapesEditor.getHtml();
                    this.content = this.minify(`<style>${css}</style>${html}`);
                }
            });
        },
        minify(content) {
            content = content
                .replace(/\>[\r\n ]+\</g, "><")
                .replace(/(<.*?>)|\s+/g, (m, $1) => ($1 ? $1 : " "))
                .trim();
            return content;
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
