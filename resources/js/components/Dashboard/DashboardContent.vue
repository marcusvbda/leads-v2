<template>
    <div class="dashboard-content">
        <div class="flex gap-5 flex-col md:flex-row">
            <div class="w-full md:w-3/12">
                <qty-leads-today />
            </div>
            <div class="w-full md:w-3/12">
                <qty-polos />
            </div>
            <div class="w-full md:w-3/12">
                <qty-users />
            </div>
            <div class="w-full md:w-3/12">
                <qty-departments />
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['title', 'selected_polo_ids', 'user_id'],
    components: {
        'qty-polos': require('./-qty-polos.vue').default,
        'qty-users': require('./-qty-users.vue').default,
        'qty-departments': require('./-qty-departments.vue').default,
        'qty-leads-today': require('./-qty-leads-today.vue').default,
    },
};
</script>
