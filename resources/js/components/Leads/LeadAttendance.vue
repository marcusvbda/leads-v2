<template>
    <div id="attendance">
        <div class="row">
            <category-side :preset_date="preset_date" />
            <lead-painel />
        </div>
    </div>
</template>
<script>
import { mapMutations } from "vuex";

export default {
    props: ["user", "preset_date"],
    components: {
        "category-side": require("./partials/-category-side.vue").default,
        "lead-painel": require("./partials/painel/-lead-painel.vue").default
    },
    created() {
        this.init();
    },
    methods: {
        ...mapMutations("lead", ["setUser"]),
        init() {
            this.setUser(this.user);
        }
    }
};
</script>
