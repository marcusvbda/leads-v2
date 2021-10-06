<template>
    <div class="col-md-6 col-sm-12 dash-card flex-fill">
        <loading-shimmer :loading="loading" :h="120" class="h-100">
            <div class="card shadow h-100">
                <div class="container py-3">
                    <div class="d-flex flex-column">
                        <b class="mb-1">Leads Finalizados</b>
                        <div class="d-flex flex-row align-items-end">
                            <div class="number">{{ qty }}</div>
                        </div>
                        <small class="description">Finalizados no periodo do filtro</small>
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
            timeout: null,
            qty: 0,
        }
    },
    computed: {
        ...mapGetters("dashboard",["filter"]),
    },
    watch: {
        filter: {
            handler(val) {
                clearTimeout(this.timeout)
                this.timeout = setTimeout(() => {
                    this.loading = true
                    this.getData()
                })
            },
            deep: true,
        },
    },
    created() {
        this.getData()
    },
    methods: {
        ...mapActions("dashboard",["getDashboardContent"]),        
        getData() {
            this.getDashboardContent({ action: 'getLeadFinishedData' }).then((data) => {
                this.qty = data
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