<template>
    <div>
        <div class="row">
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
                                <dash-filters :default_filter="default_filter" :user_id="user_id" :selected_polo_ids="selected_polo_ids" />
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row mt-3 d-flex flex-wrap">
                            <!-- <dash-trend title="Oportunidades" description="Tendência de novos leads no periôdo selecionado" action="new_leads" /> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import dashStore from '~/stores/dashboard'
export default {
    props: ['title', 'selected_polo_ids', 'user_id', 'default_filter', 'is_head'],
    store: dashStore,
    components: {
        'dash-filters': require('./-dash-filters.vue').default,
        'qty-polos': require('./-qty-polos.vue').default,
        'qty-users': require('./-qty-users.vue').default,
        'qty-departments': require('./-qty-departments.vue').default,
        'qty-leads-today': require('./-qty-leads-today.vue').default,
    },
}
</script>