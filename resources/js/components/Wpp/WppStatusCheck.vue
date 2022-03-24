<template>
    <div>
        <span v-html="content_status" />
    </div>
</template>
<script>
export default {
    props: ["status", "id", "current_status"],
    data() {
        return {
            content_status: this.status,
        };
    },
    created() {
        if (["sent", "error"].includes(this.current_status)) {
            this.initSocket("WppMessage.StatusChange", `WppMessages@Tenant:${laravel.tenant.code}`);
        }
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

            socket.on(event, ({ ids, status }) => {
                if (ids.includes(this.id)) {
                    this.content_status = status;
                }
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
