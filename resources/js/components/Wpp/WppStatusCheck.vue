<template>
    <div class="status-row">
        <a href="#" @click.prevent="startConsult" v-if="consulting == false">Consultar Status</a>
        <template v-else>
            <template v-if="loading"> Consultando <span v-html="getloadingGif" /> </template>
            <template v-else>
                <div class="d-flex flex-column">
                    <div class="status-row">
                        {{ connected ? "Ativo" : "Inativo" }} <span v-html="$getEnabledIcons(connected)" />
                    </div>
                    <WppSendMessage v-if="connected" :session="session" />
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
            counter: 0,
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
        startConsult() {
            this.loading = true;
            this.consulting = true;
            this.checkSection({ code: this.session }).then(({ data }) => {
                if (data !== "connected") {
                    this.logSection();
                } else {
                    this.connected = true;
                    this.loading = false;
                }
            });
        },
        logSection() {
            const actionEvents = {
                authenticated: () => {
                    this.connected = true;
                    this.loading = false;
                },
                qr: () => {
                    this.connected = false;
                    this.loading = false;
                },
            };
            this.initSocket({ code: this.session, action_events: actionEvents });
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
