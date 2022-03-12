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
                    <WppSendMessage v-if="connected" :session="session" :code="code" />
                </div>
            </template>
        </template>
    </div>
</template>

<script>
import { mapActions } from "vuex";
export default {
    props: ["session", "code"],
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
        ...mapActions("wpp", ["logSection", "checkSection"]),
        startConsult() {
            this.loading = true;
            this.consulting = true;
            this.checkSection(this.code).then(({ data }) => {
                if (data !== "connected") {
                    this.createSessionAndConsult();
                } else {
                    this.connected = true;
                    this.loading = false;
                }
            });
        },
        createSessionAndConsult() {
            this.logSection({ ...this.session.token, code: this.code });
            this.counter = 0;
            this.handleCheckSection(this.code);
        },
        handleCheckSection() {
            clearInterval(this.interval);
            this.interval = setInterval(() => {
                this.checkSection(this.code).then(({ data }) => {
                    if (data === "connected") {
                        this.connected = true;
                        this.loading = false;
                        return clearInterval(this.interval);
                    } else {
                        if (this.counter === 10) {
                            this.connected = false;
                            this.loading = false;
                            return clearInterval(this.interval);
                        }
                    }
                    this.counter++;
                });
            }, 5000);
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
