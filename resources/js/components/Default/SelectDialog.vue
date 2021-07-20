<template>
    <el-dialog :title="title" :visible.sync="showing" width="30%" center>
        <div class="d-flex flex-column">
            <el-select v-model="value" filterable placeholder="">
                <el-option v-for="item in options" :key="item.key" :label="item.label" :value="item.key" />
            </el-select>
        </div>
        <span slot="footer" class="el-dialog__footer d-flex justify-content-end p-1">
            <button class="btn btn-primary" :disabled="!value" @click="confirm">{{ btn_text ? btn_text : 'Confirmar' }}</button>
        </span>
    </el-dialog>
</template>
<script>
export default {
    props: ['title', 'default', 'btn_text'],
    data() {
        return {
            showing: false,
            options: [],
            value: null,
        }
    },
    methods: {
        open() {
            this.value = this.default ? this.default : null
            this.showing = true
        },
        confirm() {
            this.showing = false
            return this.$emit('selected', this.value)
        },
    },
}
</script>