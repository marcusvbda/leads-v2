<template>
    <div>
        <span v-html="content_status" />
    </div>
</template>
<script>
import io from "socket.io-client";
export default {
    props: ["status", "id", "current_status"],
    data() {
        return {
            content_status: this.status,
        };
    },
    created() {
        if (!["sent", "error"].includes(this.current_status)) {
            this.initSocket(`WppMessages@Tenant:${laravel.tenant.code}`,"WppMessage.StatusChange");
        }
    },
    methods: {
        initSocket(channel, event) {
            const route = `${laravel.chat.uri}:${laravel.chat.port}`;
            const socket = io(route);

            socket.on("connected", () => {
                socket.emit("join", channel);    
                socket.on(event, (response) => {
                    if(response.ids.includes(this.id)) {
                        this.content_status = response.status;
                    }
                });
            });           

        },
    }
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
