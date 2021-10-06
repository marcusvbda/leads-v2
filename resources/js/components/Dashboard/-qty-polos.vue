<template>
    <div class="col-md-3 col-sm-12 dash-card flex-fill">
        <loading-shimmer :loading="loading" :h="120" class="h-100">
            <div class="card shadow h-100">
                <div class="container py-3">
                    <div class="d-flex flex-column">
                        <b class="mb-1">Polos</b>
                        <div class="d-flex flex-row align-items-end">
                            <div class="number">{{ polos_qty }}</div>
                        </div>
                        <small class="description">Polos atualmente ativos no sistema</small>
                    </div>
                </div>
            </div>
        </loading-shimmer>
    </div>
</template>
<script>
import { mapActions, mapGetters } from 'vuex'
export default {
    data() {
        return {
            loading: true,
        }
    },
    computed: {
       ...mapGetters("dashboard",["polos_qty"])
    },
    created() {
        this.init()
    },
    methods: {
        ...mapActions("dashboard",["getPolosQty"]),        
        init() {
            this.getPolosQty().then(() => {
                this.loading = false
            })
        },
    },
}
</script>
<style lang="scss" scoped>
.dash-card {
    .number {
        font-weight: 600;
        font-size: 30px;
    }
    .trend {
        margin-bottom: 15px;
        margin-left: 10px;
        font-size: 12px;
    }
    .description {
        font-size: 11px;
        color: gray;
    }
}
</style>