<template>
    <div class="row mb-2">
        <div class="col-12 my-3 d-flex">
            <el-select
                v-model="polo_id"
                filterable
                reserve-keyword
                placeholder="Selecione um polo"
                class="w-50 ml-auto"
                clearable
                @clear="makeFilter"
            >
                <el-option v-for="polo in polos" :key="polo.id" :label="polo.name" :value="polo.id" />
            </el-select>
        </div>
    </div>
</template>
<script>
export default {
    props: ["polos"],
    data() {
        return {
            polo_id: Number(this.$getUrlParams().polo_id) || "",
            page: this.$getUrlParams().settings_page || 1,
        };
    },
    watch: {
        polo_id() {
            this.makeFilter();
        },
    },
    computed: {
        route() {
            if (!this.polo_id) {
                return `${window.location.pathname}`;
            }
            return `${window.location.pathname}?polo_id=${this.polo_id}&settings_page=${this.page}`;
        },
    },
    methods: {
        makeFilter() {
            this.$loading({ text: "Aguarde ..." });
            window.location.href = this.route;
        },
    },
};
</script>
