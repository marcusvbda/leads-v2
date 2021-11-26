<template>
    <div class="row mb-2">
        <div class="col-12 d-flex justify-content-end">
            <el-radio-group v-model="filter.visibility" class="mr-3">
                <el-radio-button label="hidden">Ocultos</el-radio-button>
                <el-radio-button label="visible">Visíveis</el-radio-button>
            </el-radio-group>
            <el-radio-group v-model="filter.status">
                <el-radio-button label="all">Todos Status</el-radio-button>
                <el-radio-button label="waiting">Aguardando</el-radio-button>
                <el-radio-button label="approved">Aprovado</el-radio-button>
            </el-radio-group>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            filter: {
                status: this.$getUrlParams().request_status || "all",
                visibility: this.$getUrlParams().visibility || "visible"
            }
        };
    },
    watch: {
        filter: {
            deep: true,
            handler() {
                this.$loading({ text: "Aguarde ..." });
                window.location.href =
                    window.location.pathname + "?request_status=" + this.filter.status + `&visibility=${this.filter.visibility}`;
            }
        }
    }
};
</script>
