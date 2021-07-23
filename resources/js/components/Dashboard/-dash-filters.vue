<template>
    <div class="d-flex row align-items-center mr-1">
        <el-radio-group v-model="filter.selected_range" size="small">
            <el-radio-button v-if="Object.keys(this.date_ranges).includes('_this_year_')" label="_this_year_" border>Este Ano</el-radio-button>
            <el-radio-button v-if="Object.keys(this.date_ranges).includes('_this_month_')" label="_this_month_" border>Este Mês</el-radio-button>
            <el-radio-button v-if="Object.keys(this.date_ranges).includes('_this_week_')" label="_this_week_" border>Esta Semana</el-radio-button>
            <el-radio-button v-if="Object.keys(this.date_ranges).includes('_today_')" label="_today_" border>Hoje</el-radio-button>
        </el-radio-group>
        <a href="#" @click.prevent="dialog_polo_visible = !dialog_polo_visible" class="f-12 ml-3">Selecionar os Polos</a>
        <el-dialog title="Selecionar o polo" :visible.sync="dialog_polo_visible" width="50%">
            <el-select v-model="filter.polo_ids" filterable multiple placeholder="" class="w-100">
                <el-option v-for="(polo, i) in polos" :key="i" :label="polo.name" :value="polo.id" />
            </el-select>
        </el-dialog>
    </div>
</template>
<script>
export default {
    props: ['user_id', 'selected_polo_ids'],
    data() {
        return {
            dialog_polo_visible: false,
        }
    },
    created() {
        this.$store.dispatch('getPolos', this.user_id)
        this.$store.dispatch('getDateRanges')
        this.filter.polo_ids = this.selected_polo_ids
    },
    computed: {
        polos() {
            return this.$store.state.polos
        },
        date_ranges() {
            return this.$store.state.date_ranges
        },
        filter: {
            get() {
                return this.$store.state.filter
            },
            set(val) {
                return this.$store.commit('setFilter', val)
            },
        },
    },
}
</script>