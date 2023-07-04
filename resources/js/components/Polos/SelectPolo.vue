<template>
    <div>
        <el-tooltip
            v-if="has_more_polos"
            class="item"
            effect="dark"
            content="Clique para selecionar o polo que deseja visualizar"
            placement="bottom"
        >
            <a @click.prevent="showPolosList" class="nav-link mt-0" href="#">
                <span
                    :class="[
                        'inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs',
                        'font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10',
                    ]"
                >
                    {{ polo_name }}
                </span>
            </a>
        </el-tooltip>
        <span
            v-else
            :class="[
                'inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs',
                'font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10',
            ]"
        >
            {{ polo_name }}
        </span>
        <select-dialog
            ref="select-polo"
            title="Selecione o polo que deseja logar"
            btn_text="Selecionar"
            @selected="selectPolo"
            :default="logged_id"
        />
    </div>
</template>
<script>
export default {
    props: ['polo_name', 'user_id', 'logged_id', 'has_more_polos'],
    data() {
        return {
            visible: false,
        };
    },
    methods: {
        showPolosList() {
            let loading = this.$loading({ text: 'Carregando Polos ...' });
            this.$http
                .post('/vstack/json-api', {
                    model: '\\App\\User',
                    includes: ['polos'],
                    filters: {
                        where: [['id', '=', this.user_id]],
                    },
                })
                .then(({ data }) => {
                    let select_polo = this.$refs['select-polo'];
                    select_polo.options = data[0].polos.map((x) => ({
                        key: x.id,
                        label: x.name,
                    }));
                    select_polo.open();
                    loading.close();
                });
        },
        selectPolo(polo) {
            this.$loading({
                text: `Logando no polo selecionado ...`,
            });
            this.$http
                .post(`/admin/polos/change-logged`, { id: polo })
                .then(() => window.location.reload());
        },
    },
};
</script>
