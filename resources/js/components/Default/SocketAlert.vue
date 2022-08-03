<template></template>
<script>
import io from "socket.io-client";
export default {
    props: ["user_code", "polo_code"],
    data() {
        return {
            qty: 0,
        };
    },
    created() {
        this.initSocket("Alert.User", "user@" + laravel.user.code);
        this.initSocket("Alert.Polo", "polo@" + this.polo_code);
        this.initSocket("Alert.Tenant", "tenant@" + laravel.tenant.code);
    },
    methods: {
        initSocket(event, channel) {
            if (laravel.chat.enabled) {
                const route = `${laravel.chat.uri}:${laravel.chat.port}`;
                const socket = io(route);
                socket.on("connected", () => {
                    socket.emit("join", channel);
                });

                socket.on(event, (data) => {
                    this.$message({ dangerouslyUseHTMLString: true, showClose: true, ...data });
                });
            }
        },
    },
};
</script>
