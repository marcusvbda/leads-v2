<template>
    <div class="col-md-3 col-sm-12">
        <div class="card card h-100">
            <div class="card-header text-center">
                {{ template.name }}
            </div>
            <div class="card-body d-flex align-items-center justify-content-center">
                <div class="thumbnail" :style="{ backgroundImage: `url('${template.thumbnail}')` }" />
            </div>
            <div class="card-footer">
                <div class="d-flex flex-column justify-content-center align-items-center">
                    <a :class="`${template.slug == 'em_branco' ? 'btn disabled' : 'btn link'}`" href="#" @click.prevent="dialogVisible = true">
                        Pré-Visualizar
                    </a>
                    <el-button @click="goTo(`/admin/emails/create?template=${template.slug}`)" type="primary" class="mt-3" plain> Selecionar </el-button>
                </div>
            </div>
            <el-dialog :title="template.name" :visible.sync="dialogVisible" fullscreen>
                <div id="content-preview" v-html="template.body.body" />

                <div class="preview">
                    <div class="overlay-block" />
                    <component is="style" scoped> {{ template.body.css }} </component>
                </div>
            </el-dialog>
        </div>
    </div>
</template>
<script>
export default {
    props: ['template'],
    data() {
        return {
            dialogVisible: false,
        }
    },
    methods: {
        goTo(url) {
            this.$loading({ text: 'Carregando Template ...' })
            window.location.href = url
        },
    },
}
</script>
<style lang="scss" scoped>
.thumbnail {
    height: 190px;
    width: 160px;
    background-position: center;
    background-repeat: no-repeat;
}
.overlay-block {
    width: 100%;
    height: 100%;
    position: absolute;
    z-index: 1;
    cursor: no-drop;
}
.thumbnail {
    position: relative;
}
</style>