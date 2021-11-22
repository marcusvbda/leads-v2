<template>
    <div class="d-flex flex-column">
        <el-select v-model="type" filterable class="w-100" clearable>
            <el-option label="Customizado" value="custom" />
            <el-option label="Todos" value="todos" />
            <el-option label="Hoje" value="hoje" />
            <el-option label="Ontem" value="ontem" />
            <el-option label="Últimos 7 Dias" value="ultimos 7 dias" />
            <el-option label="Últimos 14 Dias" value="ultimos 14 dias" />
            <el-option label="Últimos 30 Dias" value="ultimos 30 dias" />
            <el-option label="Esta Semana" value="esta semana" />
            <el-option label="Este Mês" value="este mes" />
            <el-option label="Este Ano" value="este ano" />
        </el-select>
        <el-date-picker
            v-if="type === 'custom'"
            class="mt-3 w-100"
            v-model="custom_dates"
            type="daterange"
            align="right"
            unlink-panels
            format="dd/MM/yyyy"
            value-format="yyyy-MM-dd"
            end-placeholder="Data Fim"
            start-placeholder="Data início"
        />
    </div>
</template>
<script>
export default {
    props: ["filter", "index", "submit"],
    data() {
        return {
            type: this.getParams().type,
            custom_dates: this.getParams().custom_dates
        };
    },
    watch: {
        type(val) {
            this.custom_dates = [];
            if (["custom"].includes(val)) return;
            return this.appendFilter(val);
        },
        custom_dates(val) {
            if (val.length < 2) return;
            return this.appendFilter(val.join(","));
        }
    },
    methods: {
        getParams() {
            let type = "todos";
            let custom_dates = [];
            let dates = this.filter[this.index].split(",");
            if (dates.length == 2) {
                type = "custom";
                custom_dates = dates;
            } else {
                type = dates[0] ? dates[0] : "todos";
                custom_dates = [];
            }
            return { type, custom_dates };
        },
        appendFilter(val) {
            this.$set(this.filter, this.index, val);
            this.$nextTick(() => this.$emit("on-submit"));
        },
        changeCode() {
            if (!this.code) return;
            this.$set(this.filter, this.index, `${this.type},${this.code}`);
            this.$nextTick(() => this.$emit("on-submit"));
        }
    }
};
</script>
