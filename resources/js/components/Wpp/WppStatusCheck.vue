<template>
    <div>
        <span v-html="content_status" />
    </div>
</template>
<script>
export default {
    props: ["status", "code"],
    data() {
        return {
            content_status: this.status,
        };
    },
    created() {
        this.initSocket("WppMessage.StatusChange", `WppMessage@${this.code}`);
    },
    methods: {
        initSocket(event, uid) {
            const socket = this.$io(laravel.config.socket_service.uri, {
                query: {
                    uid: `${laravel.config.socket_service.uid}#${uid}`,
                    username: laravel.config.socket_service.username,
                    password: laravel.config.socket_service.password,
                },
                reconnection: true,
                reconnectionDelay: 500,
                reconnectionAttempts: 10,
            });

            socket.on(event, (data) => {
                this.content_status = data;
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
