<template>
    <el-dialog :title="title" :visible.sync="showing" width="30%" center>
        <div class="flex flex-col">
            <span class="mb-3 text-neutral-600" v-if="description">
                {{ description }}
            </span>
            <el-select v-model="value" filterable placeholder="" class="w-full">
                <el-option
                    v-for="item in options"
                    :key="item.key"
                    :label="item.label"
                    :value="item.key"
                />
            </el-select>
        </div>
        <span slot="footer" class="el-dialog__footer flex justify-end p-1">
            <button
                class="vstack-btn primary"
                :disabled="!value"
                @click="confirm"
            >
                {{ btn_text ? btn_text : 'Confirmar' }}
            </button>
        </span>
    </el-dialog>
</template>
<script>
export default {
    props: ['title', 'description', 'default', 'btn_text'],
    data() {
        return {
            showing: false,
            options: [],
            value: null,
        };
    },
    methods: {
        open() {
            this.value = this.default ? this.default : null;
            this.showing = true;
        },
        confirm() {
            this.showing = false;
            return this.$emit('selected', this.value);
        },
    },
};
</script>
