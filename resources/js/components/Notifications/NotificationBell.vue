<template>
    <ul class="navbar-nav">
        <li :class="`nav-item dropdown bell-note mx-0 ${active ? 'active' : ''}`">
            <a class="nav-link dropdown-toggle text-center bell-notification" href="/admin/notificacoes">
                <div class="bell-container">
                    <span class="el-icon-message-solid" style="font-size: 20px"></span>
                    <span class="qty_badge" v-if="qty > 0">{{ qty }}</span>
                </div>
            </a>
        </li>
    </ul>
</template>
<script>
export default {
    props: ['polo_id', 'active'],
    data() {
        return {
            qty: 0,
        }
    },
    created() {
        this.getNotificationQty()
        this.initiatPusherListenUser()
    },
    methods: {
        initiatPusherListenUser() {
            if (laravel.user.id && laravel.chat.pusher_key) {
                this.$echo.private(`App.User.${laravel.user.id}`).listen('.notifications.user', (n) => {
                    this.qty = n.qty
                })
            }
        },
        getNotificationQty() {
            this.$http
                .post(`/admin/notificacoes/get-qty`)
                .then(({ data }) => {
                    this.qty = data.qty
                })
                .catch((er) => {
                    console.log(er)
                })
        },
    },
}
</script>
<style lang="scss" scoped>
.bell-note {
    width: 60px;
    .bell-notification {
        &::after {
            display: none;
        }
        .bell-container {
            position: relative;
            .qty_badge {
                background-color: #db5565;
                padding: 3px 5px 2px;
                position: absolute;
                top: -9px;
                right: 0;
                display: inline-block;
                min-width: 10px;
                font-size: 10px;
                font-weight: bold;
                color: #ffffff;
                line-height: 1;
                vertical-align: baseline;
                white-space: nowrap;
                text-align: center;
                border-radius: 10px;
            }
        }
    }
}
</style>