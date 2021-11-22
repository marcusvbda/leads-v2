<template>
    <div class="d-flex row align-items-center mr-1">
        <el-radio-group v-model="default_filter.selected_range" size="small">
            <el-radio-button v-if="Object.keys(date_ranges).includes('_this_year_')" label="_this_year_" border>
                Este Ano
            </el-radio-button>
            <el-radio-button v-if="Object.keys(date_ranges).includes('_this_month_')" label="_this_month_" border>
                Este Mês
            </el-radio-button>
            <el-radio-button v-if="Object.keys(date_ranges).includes('_this_week_')" label="_this_week_" border>
                Esta Semana
            </el-radio-button>
            <el-radio-button v-if="Object.keys(date_ranges).includes('_today_')" label="_today_" border>
                Hoje
            </el-radio-button>
        </el-radio-group>
        <a href="#" @click.prevent="dialog_polo_visible = !dialog_polo_visible" class="f-12 ml-3">Selecionar os Polos</a>
        <el-dialog title="Selecionar o polo" :visible.sync="dialog_polo_visible" width="50%">
            <el-select-all
                v-model="default_filter.polo_ids"
                filterable
                multiple
                collapse-tags
                placeholder=""
                class="w-100"
                clearable
                label="Todos os Polos"
                :options="polos.map(x => ({ label: x.name, value: x.id }))"
            />
        </el-dialog>
    </div>
</template>
<script>
import { mapActions, mapGetters, mapMutations } from "vuex";
export default {
    props: ["user_id", "selected_polo_ids"],
    data() {
        return {
            dialog_polo_visible: false,
            default_filter: {
                selected_range: "_this_month_",
                polo_ids: this.selected_polo_ids
            }
        };
    },
    watch: {
        default_filter: {
            handler(val) {
                this.setFilter(val);
            },
            deep: true
        }
    },
    created() {
        this.getPolos(this.user_id);
        this.getDateRanges();
        this.setFilter(this.default_filter);
    },
    computed: {
        ...mapGetters("dashboard", ["polos", "date_ranges", "filter"])
    },
    methods: {
        ...mapActions("dashboard", ["getPolos", "getDateRanges"]),
        ...mapMutations("dashboard", ["setFilter"])
    }
};
</script>
