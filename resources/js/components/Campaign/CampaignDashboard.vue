<template>
    <div class="flex gap-5 flex-col md:flex-row">
        <div class="w-full md:w-3/12">
            <dashboard-card
                title="Novos Leads"
                :qty="12"
                subtitle="Considerando todos cadastrados no sistema com data de cadastro"
            />
        </div>
        <div class="w-full md:w-3/12">
            <dashboard-card
                title="Novos Leads"
                :qty="12"
                subtitle="Considerando todos cadastrados no sistema com data de cadastro"
            />
        </div>
        <div class="w-full md:w-3/12">
            <dashboard-card
                title="Novos Leads"
                :qty="12"
                subtitle="Considerando todos cadastrados no sistema com data de cadastro"
            />
        </div>
        <div class="w-full md:w-3/12">
            <dashboard-card
                title="Novos Leads"
                :qty="12"
                subtitle="Considerando todos cadastrados no sistema com data de cadastro"
            />
        </div>
    </div>
</template>
<script>
export default {
    props: ['campaign'],
    created() {
        console.log(this.campaign);
    },
};
</script>
