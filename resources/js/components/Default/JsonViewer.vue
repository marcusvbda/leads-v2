<template>
    <div>
        <a href="#" @click.prevent="showModal">Ver Conteúdo</a>
        <el-dialog title="Conteúdo do Request" :visible.sync="visible" width="60%">
            <div :class="`${!approved ? 'clickable' : ''}`">
                <vue-json-pretty :data="content" @click="handleClick" />
            </div>
        </el-dialog>
    </div>
</template>
<script>
import VueJsonPretty from 'vue-json-pretty'
import 'vue-json-pretty/lib/styles.css'
export default {
    props: ['content', 'approved'],
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
            if (this.approved) return
            alert('chave ' + val)
        },
    },
}
</script>
<style lang="scss">
.clickable {
    .vjs-key {
        cursor: pointer;
        &:hover {
            transition: 0.3s;
            border: 3px dashed #6060c5;
        }
    }
}
</style>