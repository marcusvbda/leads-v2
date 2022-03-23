<template>
    <ul class="navbar-nav">
        <li :class="`nav-item dropdown bell-note mx-0 ${active ? 'active' : ''}`">
            <el-tooltip class="item" effect="dark" content="Notificações" placement="bottom">
                <a class="nav-link dropdown-toggle text-center bell-notification" href="/admin/notificacoes">
                    <div class="bell-container">
                        <span class="el-icon-message-solid" style="font-size: 20px"></span>
                        <span class="qty_badge" v-if="qty > 0">{{ qty }}</span>
                    </div>
                </a>
            </el-tooltip>
        </li>
    </ul>
</template>
<script>
import io from "socket.io-client";
export default {
    props: ["polo_code", "active", "socket_settings"],
    data() {
        return {
            qty: 0,
        };
    },
    created() {
        this.getNotificationQty();
        this.initSocket("Notification.Add.User", "user@" + laravel.user.code);
        this.initSocket("Notification.Add.Polo", "polo@" + this.polo_code);
        this.initSocket("Notification.Add.Tenant", "tenant@" + laravel.tenant.code);
    },
    methods: {
        initSocket(event, uid) {
            const socket = io(this.socket_settings.uri, {
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
                this.qty = data.qty;
            });
        },
        getNotificationQty() {
            this.$http
                .post(`/admin/notificacoes/get-qty`)
                .then(({ data }) => {
                    this.qty = data.qty;
                })
                .catch((er) => {
                    console.log(er);
                });
        },
    },
};
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
