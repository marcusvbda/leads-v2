<template>
    <div class="row">
        <div class="col-12">
            <div class="card" v-loading="isLoading">
                <div class="card-header">
                    <b>{{ status }}</b>
                </div>
                <div class="card-body">
                    <template v-if="status === 'notLogged'">
                        <img :src="qr_code_data.base64Qrimg" />
                    </template>
                    <template v-else-if="status === 'logged'">
                        <input v-model="message" />
                        <button>Enviar</button>
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
    },
    methods: {
        ...mapActions("wpp", ["initSocket"]),
    },
};
</script>
<style lang="scss">
.advanced-iframe {
    width: 100%;
}
</style>
