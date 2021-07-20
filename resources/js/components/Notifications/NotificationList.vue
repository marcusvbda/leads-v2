<template>
    <div id="notification-view" class="f-12">
        <div class="row" v-if="has_new">
            <div class="col-12" @click="update_page">
                <el-alert
                    class="new-notifications"
                    title="Você Possui Novas Notificações"
                    type="warning"
                    description="Clique aqui para atualizar a página e ve-las"
                    show-icon
                    :closable="false"
                />
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <p class="mb-2 text-center mb-3"><b>Notificações</b></p>
                        <p class="text-center" v-if="has_new">
                            <a href="/admin/notificacoes">Ver Novas Notificações</a>
                        </p>
                        <p class="text-center" v-else>Você Não Possui Novas Notificações</p>
                    </div>
                    <div class="card-footer">
                        <p class="text-center">Listagem de Notificações em ordem decrescente</p>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-sm-12">
                <div class="row" v-for="(note, i) in notifications" :key="i">
                    <div class="col-12">
                        <div class="card mb-1 pointer-hover" @click="goTo(note.data.url)">
                            <div class="card-body row">
                                <div class="col-sm-12 col-md-1 d-flex align-items-center justify-content-center">
                                    <b><span :class="`${note.data.icon} mr-4`" style="font-size: 30px" /></b>
                                </div>
                                <div class="col-sm-12 col-md-9 d-flex align-items-center justify-content-center">
                                    <span v-html="note.data.message" />
                                </div>
                                <div class="col-sm-12 col-md-2 d-flex align-items-center justify-content-end">
                                    <small v-html="note.f_created_at" class="ml-auto" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row my-3">
                    <div class="col-12 align-items-center justify-content-center d-flex flex-column">
                        <span class="text-muted" v-if="loading"> <span class="el-icon-loading mr-3" />Carregando </span>
                        <template v-else>
                            <b v-if="canShowMore">
                                <a href="#" @click.prevent="load">Carregar mais... <span class="el-icon-caret-bottom ml-3" /> </a>
                            </b>
                            <b v-else class="text-muted">
                                <template v-if="notifications.length <= 0">Sem Notificações</template>
                                <template v-else>Sem mais notificações antigas</template>
                            </b>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['user'],
    data() {
        return {
            qty: 0,
            has_new: false,
            notifications: [],
            loading: false,
            current_page: 0,
            last_page: -1,
        }
    },
    computed: {
        noMore() {
            return this.last_page == this.current_page
        },
        canShowMore() {
            return !this.noMore
        },
    },
    created() {
        this.getPaginatedNotifications()
        this.getNotificationQty()
        this.initiatPusherListenUser()
    },
    methods: {
        goTo(route) {
            window.location.href = route
        },
        load() {
            this.getPaginatedNotifications()
        },
        update_page() {
            window.location.reload()
        },
        initiatPusherListenUser() {
            if (laravel.user.id && laravel.chat.pusher_key) {
                this.$echo.private(`App.User.${laravel.user.id}`).listen('.notifications.user', (n) => {
                    this.qty = n.qty
                    this.has_new = true
                    document.title = `(${this.qty}) - Notificações`
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
        getPaginatedNotifications() {
            this.loading = true
            this.$http
                .post(`/admin/notificacoes/paginated`, { page: ++this.current_page })
                .then(({ data }) => {
                    this.current_page = data.current_page
                    this.last_page = data.last_page
                    this.notifications = _.concat(this.notifications, data.data)
                    this.loading = false
                })
                .catch((er) => {
                    console.log(er)
                    this.loading = false
                })
        },
    },
}
</script>
<style lang="scss" scoped >
#notification-view {
    .new-notifications {
        margin-bottom: 20px;
        border: 1px solid;
        opacity: 0.8;
        transition: 0.5s;
        cursor: pointer;
        &:hover {
            opacity: 1;
        }
    }
}
</style>