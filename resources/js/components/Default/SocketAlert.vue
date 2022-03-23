<template></template>
<script>
export default {
    props: ["user_code", "socket_settings", "polo_code"],
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
        initSocket(event, uid) {
            const socket = this.$io(this.socket_settings.uri, {
                query: {
                    uid: `${this.socket_settings.uid}#${uid}`,
                    username: this.socket_settings.username,
                    password: this.socket_settings.password,
                },
                reconnection: true,
                reconnectionDelay: 500,
                reconnectionAttempts: 10,
            });

            socket.on(event, (data) => {
                this.$message({ dangerouslyUseHTMLString: true, showClose: true, ...data });
            });
        },
    },
};
</script>
