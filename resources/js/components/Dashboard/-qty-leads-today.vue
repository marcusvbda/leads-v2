<template>
    <div class="col-md-3 col-sm-12 dash-card flex-fill">
        <loading-shimmer :loading="loading" :h="218" class="h-100">
            <div class="card shadow h-100">
                <div class="container py-3">
                    <div class="d-flex flex-column">
                        <b class="mb-1">Novos Leads</b>
                        <div class="d-flex flex-row align-items-end">
                            <div class="number">{{ qty }}</div>
                        </div>
                        <small class="description">Considerando todos cadastrados no sistema com data de cadastro {{ today.format('DD/MM/YYYY') }}</small>
                    </div>
                </div>
            </div>
        </loading-shimmer>
    </div>
</template>
<script>
export default {
    data() {
        return {
            loading: true,
        }
    },
    computed: {
        qty() {
            return this.$store.state.new_leads_qty
        },
        today() {
            return this.$moment()
        },
    },
    created() {
        this.init()
    },
    methods: {
        init() {
            this.$store.dispatch('getNewLeadsQty', { today: this.today.format('YYYY-MM-DD') }).then(() => {
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