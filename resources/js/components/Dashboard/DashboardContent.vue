<template>
    <div class="dashboard-content">
        <div class="row" v-if="is_head">
            <qty-leads-today class="mb-3" />
            <qty-polos class="mb-3" />
            <qty-users class="mb-3" />
            <qty-departments class="mb-3" />
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-12 d-flex flex-row justify-content-between align-items-center">
                                <h5 class="mb-0"><span class="el-icon-data-analysis mr-3" />{{ title }}</h5>
                                <dash-filters :user_id="user_id" :selected_polo_ids="selected_polo_ids" />
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row mt-3 d-flex flex-wrap">
                            <div class="col-md-6 col-sm-12">
                                <div class="row">
                                    <qty-leads class="mb-3" />
                                    <qty-leads-finished class="mb-3" />
                                    <qty-canceled-tax class="mb-3" />
                                    <qty-finished-tax class="mb-3" />
                                    <chart-status class="mb-3" />
                                    <chart-objection class="mb-3" />
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12">
                                <div class="row">
                                    <top-departments class="mb-3" />
                                    <top-users class="mb-3" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ["title", "selected_polo_ids", "user_id", "is_head"],
    components: {
        "dash-filters": require("./-dash-filters.vue").default,
        "qty-polos": require("./-qty-polos.vue").default,
        "qty-users": require("./-qty-users.vue").default,
        "qty-departments": require("./-qty-departments.vue").default,
        "qty-leads-today": require("./-qty-leads-today.vue").default,
        "qty-leads": require("./-qty-leads.vue").default,
        "qty-leads-finished": require("./-qty-leads-finished.vue").default,
        "top-departments": require("./-top-departments.vue").default,
        "top-users": require("./-top-users.vue").default,
        "qty-canceled-tax": require("./-qty-canceled-tax.vue").default,
        "qty-finished-tax": require("./-qty-finished-tax.vue").default,
        "chart-status": require("./-chart-status.vue").default,
        "chart-objection": require("./-chart-objection.vue").default,
    },
};
</script>
