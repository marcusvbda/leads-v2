<template></template>
<script>
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
        initSocket(event, uid) {
            const socket = this.$io(laravel.chat.uri, {
                query: {
                    uid: `${laravel.chat.uid}#${uid}`,
                    token: laravel.chat.token,
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
