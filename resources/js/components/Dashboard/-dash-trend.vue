<template>
    <div class="col-md-3 col-sm-12 dash-card flex-fill">
        <loading-shimmer :loading="loading" :h="218" class="h-100">
            <div class="card shadow h-100">
                <div class="container py-3">
                    <div class="d-flex flex-column">
                        <b v-if="title" v-html="title" class="mb-1" />
                        <div class="d-flex flex-row align-items-end">
                            <div class="number">{{ data.qty }}</div>
                            <small :class="`trend ${trend_class}`"> <span :class="`${trend_icon}`" /> </small>
                        </div>
                        <small class="description" v-if="!loading && description" v-html="description" />
                        <area-chart class="mt-2" height="80px" :discrete="true" :data="data.rows" :library="chartOptions" />
                    </div>
                </div>
            </div>
        </loading-shimmer>
    </div>
</template>
<script>
export default {
    props: ['description', 'title', 'action'],
    data() {
        return {
            loading: true,
            data: {
                percentage: 0,
                qty: 0,
                rows: {},
            },
            chartOptions: {
                elements: {
                    point: {
                        radius: 0,
                    },
                },
                layout: {
                    padding: {
                        left: 5,
                        right: 5,
                        top: 5,
                        bottom: 5,
                    },
                },
                scales: {
                    xAxes: [{ display: false }],
                    yAxes: [{ display: false }],
                },
                tooltips: {
                    enabled: false,
                },
            },
        }
    },
    watch: {
        date_range(val) {
            if (!val) return
            if (val.length == 2) return this.getData()
        },
        polo_ids(val) {
            this.getData()
        },
    },
    computed: {
        filter() {
            return {
                date_range: this.date_range,
                polo_ids: this.polo_ids,
            }
        },
        polo_ids() {
            return this.state.polo_ids
        },
        date_range() {
            return this.state.date_range
        },
        state() {
            return this.$store.state
        },
        trend_class() {
            if (this.data.trend == 'down') return 'text-danger'
            if (this.data.trend == 'keep') return 'text-warning'
            return 'text-success'
        },
        trend_icon() {
            if (this.data.trend == 'down') return `el-icon-bottom-right text-danger`
            if (this.data.trend == 'keep') return `el-icon-right text-warning`
            return `el-icon-top-right text-success`
        },
    },
    methods: {
        getData() {
            this.loading = true
            this.$http
                .post(`/admin/dashboard/get-data/${this.action}`, this.filter)
                .then(({ data }) => {
                    this.data = data
                    this.loading = false
                })
                .catch((er) => {
                    console.log(er)
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