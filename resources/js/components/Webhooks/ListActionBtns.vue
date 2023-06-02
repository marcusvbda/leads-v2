<template>
    <div class="d-flex flex-row flex-wrap cursor-pointer align-items-center justify-content-center" style="min-height: 25px">
        <el-button-group>
            <el-tooltip class="item" effect="dark" content="Excluir" placement="top">
                <el-button size="small" plain type="danger" icon="el-icon-delete" @click.prevent="destroy" />
            </el-tooltip>
        </el-button-group>
    </div>
</template>
<script>
export default {
    props: ["campaign_code", "row_id", "resource"],
    methods: {
        destroy() {
            this.$confirm(`Confirma Exclusão deste registro ?`, "Confirmação", {
                confirmButtonText: "Sim",
                cancelButtonText: "Não",
                type: "error"
            })
                .then(() => {
                    let loading = this.$loading({ text: "Aguarde ..." });
                    this.$http
                        .post(`/admin/webhooks/${this.campaign_code}/actions/destroy-${this.resource}`, { id: this.row_id })
                        .then(({ data }) => {
                            if (data.success) {
                                return window.location.reload();
                            }
                        })
                        .catch(er => {
                            loading.close();
                            this.$message({
                                message: er.response.data.message,
                                type: "error"
                            });
                        });
                })
                .catch(() => false);
        }
    }
};
</script>
