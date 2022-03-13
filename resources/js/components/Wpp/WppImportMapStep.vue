<template>
    <div>
        <div class="row mb-3">
            <div class="col-12">
                <el-switch v-model="without_file" active-text="Continuar sem anexo" inactive-text="Anexar um arquivo" />
            </div>
        </div>
        <div class="row" v-if="!without_file">
            <div class="col-md-5 col-sm-12">Escolha um arquivo XLS do seu computador</div>
            <div class="col-md-6 col-sm-12">
                <template v-if="!file">
                    <input accept=".pdf, .png, .jpg" type="file" @change="fileChange" />
                    <div>
                        <small class="mt-2">Tamanho máximo: 50 MB</small>
                    </div>
                </template>
                <template v-else>
                    <div class="d-flex flex-row">
                        <div>{{ file.name }}</div>
                        <a href="#" class="ml-2 link text-danger" @click.prevent="file = null">Trocar de Arquivo</a>
                    </div>
                </template>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12">Selecione a sessão que deseja utilizar para efetuar os envios</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6 col-sm-12">
                <ElSelect
                    v-model="form.session_id"
                    placeholder="Selecione a sessão"
                    clearable
                    filterable
                    class="w-100"
                    @change="checkCanNext"
                >
                    <ElOption v-for="(sess, i) in sessions" :key="i" :label="sess.name" :value="sess.id" />
                </ElSelect>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ["form", "step_data", "title", "sessions"],
    data() {
        return {
            without_file: true,
            file: null,
        };
    },
    watch: {
        without_file() {
            this.checkCanNext();
        },
        file(val) {
            this.$set(this.form, "wpp_file", val);
        },
    },
    created() {
        this.$set(this.form, "session_id", null);
        this.step_data.can_next = false;
    },
    methods: {
        checkCanNext() {
            if (this.without_file && this.form.session_id) {
                this.step_data.can_next = true;
            } else {
                this.step_data.can_next = false;
                this.file = null;
                this.prevent();
            }
        },
        prevent() {
            this.without_file = true;
            this.file = null;
            this.$message("Função ainda não implementada");
        },
        fileChange(e) {
            var files = e.target.files || e.dataTransfer.files;
            if (!files.length) {
                return;
            }
            this.step_data.can_next = true;
            this.file = files[0];
        },
    },
};
</script>
