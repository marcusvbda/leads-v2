<template>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <el-tooltip
                class="item"
                effect="dark"
                content="Clique para selecionar o polo que deseja visualizar"
                placement="bottom"
            >
                <a @click.prevent="showPolosList" class="nav-link" href="#">
                    <span class="badge badge-default py-1 px-2">{{ polo_name }}</span>
                </a>
            </el-tooltip>
        </li>
        <select-dialog
            ref="select-polo"
            title="Selecione o polo que deseja logar"
            btn_text="Selecionar"
            @selected="selectPolo"
            :default="logged_id"
        />
    </ul>
</template>
<script>
export default {
    props: ["polo_name", "user_id", "logged_id"],
    data() {
        return {
            visible: false
        };
    },
    methods: {
        showPolosList() {
            let loading = this.$loading({ text: "Carregando Polos ..." });
            this.$http
                .post("/vstack/json-api", {
                    model: "\\App\\User",
                    includes: ["polos"],
                    filters: {
                        where: [["id", "=", this.user_id]]
                    }
                })
                .then(({ data }) => {
                    let select_polo = this.$refs["select-polo"];
                    select_polo.options = data[0].polos.map(x => ({ key: x.id, label: x.name }));
                    select_polo.open();
                    loading.close();
                });
        },
        selectPolo(polo) {
            let loading = this.$loading({ text: `Logando no polo selecionado ...` });
            this.$http.post(`/admin/polos/change-logged`, { id: polo }).then(({ data }) => {
                window.location.reload();
            });
        }
    }
};
</script>
