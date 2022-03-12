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
import { mapActions } from "vuex";
export default {
    props: ["session"],
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
        ...mapActions("wpp", ["sendDirectMessage"]),
        showDialog() {
            this.loading = false;
            this.visible = true;
            this.message = "";
            this.phone = "";
        },
        sendTextMessage() {
            this.loading = true;
            const phone = this.phone.replace(/[^0-9]/g, "") + "@c.us";
            this.sendDirectMessage({ session_code: this.session, message: this.message, to: phone }).then(({ data }) => {
                if (data.event === "message-sent") {
                    this.visible = false;
                    this.$message.success("Mensagem enviada com sucesso!");
                }
            });
        },
    },
};
</script>
