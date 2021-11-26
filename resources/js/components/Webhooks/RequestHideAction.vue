<template>
    <el-radio-group v-model="value" size="mini">
        <el-radio-button label="hide" border>Esconder</el-radio-button>
        <el-radio-button label="show" border>Mostrar</el-radio-button>
    </el-radio-group>
</template>
<script>
export default {
    props: ["code", "row"],
    data() {
        return {
            value: this.row.hide ? "hide" : "show"
        };
    },
    watch: {
        value() {
            this.sendValue();
        }
    },
    methods: {
        sendValue() {
            this.$loading({ text: "Aguarde ..." });
            this.$http
                .post(`/admin/webhooks/${this.code}/actions/set-hide-value`, {
                    row_id: this.row.id,
                    value: this.value
                })
                .then(({ data }) => {
                    if (data.success) {
                        window.location.href = "/admin/webhooks/BLJORW";
                    }
                });
        }
    }
};
</script>
