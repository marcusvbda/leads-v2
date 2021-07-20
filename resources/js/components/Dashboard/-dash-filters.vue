<template>
    <div class="d-flex row align-items-center mr-1">
        <el-radio-group v-model="predefined_filter" size="small">
            <el-radio-button v-if="Object.keys(this.date_ranges).includes('_this_year_')" label="_this_year_" border>Este Ano</el-radio-button>
            <el-radio-button v-if="Object.keys(this.date_ranges).includes('_this_month_')" label="_this_month_" border>Este Mês</el-radio-button>
            <el-radio-button v-if="Object.keys(this.date_ranges).includes('_this_week_')" label="_this_week_" border>Esta Semana</el-radio-button>
            <el-radio-button v-if="Object.keys(this.date_ranges).includes('_today_')" label="_today_" border>Hoje</el-radio-button>
            <el-radio-button label="_custom_" border>Customizado</el-radio-button>
        </el-radio-group>
        <el-date-picker
            v-model="date_range"
            type="daterange"
            class="mx-3"
            range-separator="-"
            size="small"
            value-format="yyyy-MM-dd"
            format="dd/MM/yyyy"
            :disabled="predefined_filter != '_custom_'"
        />
        <a href="#" @click.prevent="dialog_polo_visible = !dialog_polo_visible" class="f-12">Selecionar os Polos</a>
        <el-dialog title="Selecionar o polo" :visible.sync="dialog_polo_visible" width="50%">
            <el-select v-model="polo_ids" filterable multiple placeholder="" class="w-100">
                <el-option v-for="(polo, i) in polos" :key="i" :label="polo.name" :value="polo.id" />
            </el-select>
        </el-dialog>
    </div>
</template>
<script>
export default {
    props: ['default_filter', 'user_id', 'selected_polo_ids'],
    data() {
        return {
            dialog_polo_visible: false,
        }
    },
    created() {
        this.$store.commit('setPredefinedFilter', this.default_filter)
        this.$store.dispatch('getDateRanges')
        this.$store.dispatch('getPolos', this.user_id)
        this.polo_ids = this.selected_polo_ids
    },
    watch: {
        predefined_filter(val) {
            if (val == '_custom_') return this.$store.commit('setDateRange', [])
            else {
                return this.$store.commit('setDateRange', this.date_ranges[val])
            }
        },
    },
    computed: {
        state() {
            return this.$store.state
        },
        polos() {
            return this.state.polos || []
        },
        date_ranges() {
            return this.state.date_ranges || {}
        },
        date_range: {
            get() {
                return this.state.date_range
            },
            set(val) {
                return this.$store.commit('setDateRange', val)
            },
        },
        polo_ids: {
            get() {
                return this.state.polo_ids
            },
            set(val) {
                return this.$store.commit('setPoloIds', val)
            },
        },
        predefined_filter: {
            get() {
                return this.state.predefined_filter
            },
            set(val) {
                return this.$store.commit('setPredefinedFilter', val)
            },
        },
    },
}
</script>