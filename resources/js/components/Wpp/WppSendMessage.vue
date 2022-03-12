<template>
    <div class="mt-2">
        <a href="#" @click.prevent="showDialog">Enviar uma mensagem</a>
        <el-dialog title="Mensagem de texto WhatsApp" :visible.sync="visible" width="30%" v-loading="loading">
            <div class="d-flex flex-column">
                <label>Telefone</label>
                <input class="form-control" v-mask="[phone_mask]" v-model="phone" />
                <label class="mt-3">Mensagem</label>
                <textarea class="form-control" v-model="message" rows="5" />
            </div>
            <span slot="footer" class="dialog-footer">
                <button class="btn btn-secondary px-4" :disabled="!canSend" @click="sendTextMessage">Enviar Mensagem</button>
            </span>
        </el-dialog>
    </div>
</template>
<script>
export default {
    props: ["session", "socket"],
    data() {
        return {
            visible: false,
            loading: false,
            message: "",
            phone: "",
            phone_mask: "+## (##) #####-####",
        };
    },
    computed: {
        canSend() {
            return this.message.length > 0 && this.phone.length === this.phone_mask.length;
        },
    },
    methods: {
        showDialog() {
            this.loading = false;
            this.visible = true;
            this.message = "";
            this.phone = "";
        },
        sendTextMessage() {
            const _uid = this.$uid();
            this.loading = true;
            const phone = this.phone.replace(/[^0-9]/g, "");
            this.socket.emit("message", {
                code: this.session,
                message: this.message,
                number: phone,
                type: "text",
                _uid,
            });

            this.socket.on("sent_message", (data) => {
                if (data._uid === _uid) {
                    this.visible = false;
                    this.$message.success("Mensagem enviada com sucesso !!");
                }
            });
        },
    },
};
</script>
