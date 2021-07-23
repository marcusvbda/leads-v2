<template>
    <div class="d-flex flex-row py-3 px-1 lead-card f-12" @click="select">
        <h3 class="el-icon-circle-plus" />
        <div class="d-flex flex-column ml-2 flex-grow-1">
            <div class="d-flex flex-row justify-content-between">
                <b>{{ lead.name }}</b>
                <v-runtime-template :template="lead.f_rating" />
            </div>
            <div class="d-flex flex-row justify-content-between">
                <span class="text-muted">{{ lead.email }}</span>
                <span class="text-muted">{{ lead.status.name }}</span>
            </div>
            <div class="d-flex flex-row justify-content-end">
                <span class="text-muted">{{ lead.f_created_at }}</span>
            </div>
        </div>
    </div>
</template>
<script>
import VRuntimeTemplate from 'v-runtime-template'
export default {
    props: ['lead'],
    components: {
        VRuntimeTemplate,
    },
    methods: {
        select() {
            this.$store.commit('setLead', this.lead)
        },
    },
}
</script>
<style lang="scss">
.lead-card {
    cursor: pointer;
    border-bottom: 1px solid #eeeeee;
    &:hover {
        background-color: #f5f5f5;
        transition: 0.3s;
    }
}
</style>