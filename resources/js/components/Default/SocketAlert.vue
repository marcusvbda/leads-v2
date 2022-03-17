<template></template>
<script>
export default {
    props: ["polo_id", "active", "event", "channel"],
    data() {
        return {
            qty: 0,
        };
    },
    created() {
        this.initiatPusherListenUser();
    },
    methods: {
        initiatPusherListenUser() {
            if (laravel.user.id && laravel.chat.pusher_key) {
                this.startChannel(`${this.channel}.User.${laravel.user.id}`);
            }
            if (laravel.user.id && laravel.chat.pusher_key) {
                this.startChannel(`${this.channel}.Polo.${this.polo_id}`);
            }
            if (laravel.tenant.id && laravel.chat.pusher_key) {
                this.startChannel(`${this.channel}.Tenant.${laravel.tenant.id}`);
            }
        },
        startChannel(channel) {
            const event = `.${this.event}`;
            this.$echo.private(channel).listen(event, (event) => {
                this.$message({ dangerouslyUseHTMLString: true, showClose: true, ...event });
            });
        },
    },
};
</script>
