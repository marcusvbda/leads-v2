<template>
    <div class="status-row">
        <a href="#" @click.prevent="logSection" v-if="consulting == false">Consultar Status</a>
        <template v-else>
            <template v-if="loading"> Consultando <span v-html="getloadingGif" /> </template>
            <template v-else>
                <div class="d-flex flex-column">
                    <div class="status-row">
                        {{ connected ? "Ativo" : "Inativo" }} <span v-html="$getEnabledIcons(connected)" />
                    </div>
                    <WppSendMessage v-if="connected" :session="session" :socket="socket" />
                </div>
            </template>
        </template>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
export default {
    props: ["session"],
    data() {
        return {
            loading: false,
            consulting: false,
            interval: null,
            connected: false,
            socket: null,
        };
    },
    computed: {
        ...mapGetters("wpp", ["event_list"]),
        getloadingGif() {
            return `
				<div class="d-flex flex-row align-items-center py-2">
					<div class="spinner-grow spinner-grow-sm text-muted mr-2" style="zoom:.5;" role="status">
						<span class="sr-only">Loading...</span>
					</div>
					<div class="spinner-grow spinner-grow-sm text-muted mr-2" style="zoom:.5;" role="status">
						<span class="sr-only">Loading...</span>
					</div>
					<div class="spinner-grow spinner-grow-sm text-muted mr-2" style="zoom:.5;" role="status">
						<span class="sr-only">Loading...</span>
					</div>
				</div>
			`;
        },
    },
    methods: {
        ...mapActions("wpp", ["initSocket", "checkSection"]),
        async logSection() {
            this.loading = true;
            this.consulting = true;

            this.socket = await this.initSocket({ code: this.session });

            this.socket.on("authenticated", () => {
                this.connected = true;
                this.loading = false;
            });

            this.socket.on("qr", () => {
                this.connected = false;
                this.loading = false;
            });
        },
    },
};
</script>

<style lang="scss">
.status-row {
    display: flex;
    justify-items: center;

    span {
        margin-left: 10px;
    }
}
</style>
