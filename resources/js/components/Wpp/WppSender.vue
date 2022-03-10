<template>
    <div class="row">
        <div class="col-12">
            <div class="card" v-loading="isLoading">
                <div class="card-header">
                    <b>{{ translated_status }}</b>
                </div>
                <div class="card-body">
                    <template v-if="status === 'notLogged'">
                        <img :src="qr_code_data.base64Qrimg" />
                    </template>
                    <template v-else-if="status === 'logged'">
                        <input v-model="message" />
                        <input v-model="phone" />
                        <button @click="sendTextMessage">Enviar</button>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapActions, mapGetters } from "vuex";

export default {
    props: ["session"],
    data() {
        return {
            message: "",
            phone: "5514996766177@c.us",
        };
    },
    created() {
        this.initSocket(this.session);
    },
    computed: {
        ...mapGetters("wpp", ["status", "qr_code_data"]),
        isLoading() {
            return ["initializing"].includes(this.status);
        },
        translated_status() {
            const options = {
                notLogged: "Não logado",
                initializing: "Inicializando",
                logged: "Logado",
            };
            return options[this.status] ?? this.status;
        },
    },
    methods: {
        ...mapActions("wpp", ["initSocket", "sendMessage"]),
        sendTextMessage() {
            this.sendMessage({ message: this.message, to: this.phone, type: "text" });
        },
    },
};
</script>
