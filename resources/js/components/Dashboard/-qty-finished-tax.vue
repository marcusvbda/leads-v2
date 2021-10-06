<template>
    <div class="col-md-6 col-sm-12 dash-card flex-fill">
        <loading-shimmer :loading="loading" :h="120" class="h-100">
            <div class="card shadow h-100">
                <div class="container py-3">
                    <div class="d-flex flex-column">
                        <b class="mb-1">Taxa de Finalização</b>
                        <div class="d-flex flex-row align-items-end">
                            <div class="number">{{ percentage }}%</div>
                            <small class="text-muted ml-3"
                                >{{ finished }} de {{ total }}</small
                            >
                        </div>
                        <small class="description"
                            >Leads criados e finalizados no periodo considerado
                            no filtro</small
                        >
                    </div>
                </div>
            </div>
        </loading-shimmer>
    </div>
</template>
<script>
import { mapActions, mapGetters } from 'vuex';
export default {
    data() {
        return {
            loading: true,
            timeout: null,
            results: []
        };
    },
    computed: {
        ...mapGetters("dashboard",["filter"]),
        finished() {
            return this.results
                .filter(x => x.status == "finished")
                .map(x => x.qty)
                .reduce((a, b) => a + b, 0);
        },
        total() {
            return this.results.map(x => x.qty).reduce((a, b) => a + b, 0);
        },
        percentage() {
            return this.$avoidNaN(
                +((this.finished / this.total) * 100).toFixed(2)
            );
        }
    },
    watch: {
        filter: {
            handler(val) {
                clearTimeout(this.timeout);
                this.timeout = setTimeout(() => {
                    this.loading = true;
                    this.getData();
                });
            },
            deep: true
        }
    },
    created() {
        this.getData();
    },
    methods: {
        ...mapActions("dashboard",["getDashboardContent"]),
        getData() {
            this.getDashboardContent({ action: "getFinishedTax" })
                .then(data => {
                    this.results = data;
                    this.loading = false;
                });
        }
    }
};
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
