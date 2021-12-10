<template>
    <div :class="`d-flex flex-row py-3 px-1 lead-card f-12 ${selected_lead.id == lead.id ? 'selected' : ''}`" @click="select">
        <h3 class="el-icon-circle-plus" />
        <div class="d-flex flex-column ml-2 flex-grow-1">
            <div class="d-flex flex-row justify-content-between">
                <VRuntimeTemplate :template="lead.label" />
            </div>
            <div class="d-flex flex-row justify-content-between">
                <span class="text-muted">{{ lead.email }}</span>
                <span class="text-muted">{{ lead.status.name }}</span>
            </div>
            <div class="d-flex flex-row justify-content-end">
                <div class="d-flex flex-column mr-auto">
                    <span class="text-muted d-flex flex-row">{{ lead.cellphone_number }}</span>
                    <span class="text-muted d-flex flex-row">{{ lead.phone_number }}</span>
                </div>
                <div class="d-flex flex-column">
                    <span class="text-muted d-flex flex-row"><b class="mr-auto pr-1">Criação : </b>{{ lead.f_created_at }}</span>
                    <span class="text-muted d-flex flex-row" v-if="lead.f_schedule"><b class="mr-auto pr-1">Agendamento : </b> {{ lead.f_schedule }}</span>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import VRuntimeTemplate from "v-runtime-template";
import { mapMutations } from "vuex";
export default {
    props: ["lead"],
    data() {
        return {
            row: {
                content: this.lead,
            },
        };
    },
    components: {
        VRuntimeTemplate,
    },
    computed: {
        selected_lead() {
            return this.$store.state.lead;
        },
    },
    methods: {
        ...mapMutations("lead", ["setLead"]),
        select() {
            if (this.selected_lead.id == this.lead.id) {
                return this.setLead({});
            }
            this.setLead(this.lead);
        },
    },
};
</script>
<style lang="scss">
.lead-card {
    cursor: pointer;
    border-bottom: 1px solid #eeeeee;
    &:hover {
        background-color: #f5f5f5;
        transition: 0.3s;
    }
    &.selected {
        background-color: #dff3ff;
        &:hover {
            background-color: #0074d921;
        }
    }
}
</style>
