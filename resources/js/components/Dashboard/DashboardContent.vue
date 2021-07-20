<template>
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
                <dash-trend v-if="is_head" title="Polos" description="Tendência de novos polos no periôdo selecionado" action="new_polos" />
                <dash-trend title="Oportunidades" description="Tendência de novos leads no periôdo selecionado" action="new_leads" />
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
        'dash-trend': require('./-dash-trend.vue').default,
    },
}
</script>