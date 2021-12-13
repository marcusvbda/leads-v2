<template>
    <GrapesEditor :blocks="blocks" v-model="content" />
</template>
<script>
export default {
    data() {
        return {
            started: false,
            iframeWindow: null,
            content: `<style>* { box-sizing: border-box; } body {margin: 0;}*{box-sizing:border-box;}
            body{margin-top:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;}
            #iw3y{font-family:Helvetica, sans-serif;font-size:39px;color:rgb(22, 57, 186);}
            .row{display:table;padding-top:10px;padding-right:10px;padding-bottom:10px;padding-left:10px;width:100%;}
            .cell{width:8%;display:table-cell;height:75px;}#i87o{padding:10px;color:#e022b9;}
            @media (max-width: 768px){.cell{width:100%;display:block;}}</style>
            <h1 id="iw3y">Hello World TESTE</h1><div class="row"><div class="cell"></div>
            </div><div id="i87o">Insert your text here</div>`,
        };
    },
    computed: {
        blocks() {
            let blocks = [];
            blocks.hello_world = {
                label: "Hello World",
                attributes: { class: "gjs-fonts gjs-f-text" },
                content: `<h1>Hello World</h1>`,
            };
            blocks.teste = {
                label: "Teste",
                attributes: { class: "gjs-fonts gjs-f-text" },
                content: `<b>teste teste</b>`,
            };
            return blocks;
        },
    },
};
</script>
