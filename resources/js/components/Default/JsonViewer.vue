<template>
    <div>
        <a href="#" @click.prevent="showModal">Ver Conteúdo</a>
        <el-dialog title="Aprovação de Request" :visible.sync="visible" width="60%">
            <vue-json-pretty :data="content" @click="handleClick" />
        </el-dialog>
    </div>
</template>
<script>
import VueJsonPretty from 'vue-json-pretty'
import 'vue-json-pretty/lib/styles.css'
export default {
    props: ['content'],
    data() {
        return {
            visible: false,
        }
    },
    components: {
        VueJsonPretty,
    },
    methods: {
        showModal() {
            this.visible = true
        },
        handleClick(val) {
            alert('chave ' + val)
        },
    },
}
</script>
<style lang="scss">
.vjs-key {
    cursor: pointer;
    &:hover {
        transition: 0.3s;
        border: 3px dashed #6060c5;
    }
}
</style>