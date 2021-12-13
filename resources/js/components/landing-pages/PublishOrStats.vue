<template>
    <div>
        <el-button plain type="primary" size="small" @click="handle">{{ btnText }}</el-button>
    </div>
</template>
<script>
export default {
    props: ["code", "published"],
    computed: {
        btnText() {
            const options = {
                status: "Ver Estatísticas",
                publish: "Publicar"
            };
            return options[this.action];
        },
        action() {
            return this.published ? "status" : "publish";
        }
    },
    methods: {
        handle() {
            console.log(this.action, "handle", this.code);
        }
    }
};
</script>
