<template>
    <div class="card h-100 h-auto">
        <div class="card-body h-auto">
            <el-tabs tab-position="left" class="h-auto">
                <el-tab-pane label="Todos">
                    <template-list :items="templates" />
                </el-tab-pane>
                <el-tab-pane label="Geração de Leads">
                    <template-list :items="lead_templates" />
                </el-tab-pane>
                <el-tab-pane label="Agradecimento">
                    <template-list :items="thanks_templates" />
                </el-tab-pane>
            </el-tabs>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            templates: [
                {
                    id: 1,
                    name: "Template 1",
                    group: "lead",
                    image: "https://via.placeholder.com/487x285"
                },
                {
                    id: 2,
                    name: "Template 2",
                    group: "lead",
                    image: "https://via.placeholder.com/487x285"
                },
                {
                    id: 3,
                    name: "Template 3",
                    group: "thanks",
                    image: "https://via.placeholder.com/487x285"
                },
                {
                    id: 4,
                    name: "Template 4",
                    group: "thanks",
                    image: "https://via.placeholder.com/487x285"
                },
                {
                    id: 5,
                    name: "Template 5",
                    group: "lead",
                    image: "https://via.placeholder.com/487x285"
                }
            ]
        };
    },
    components: {
        "template-list": require("./-template-list.vue").default
    },
    computed: {
        lead_templates() {
            return this.templates.filter(template => template.group == "lead");
        },
        thanks_templates() {
            return this.templates.filter(template => template.group == "thanks");
        }
    }
};
</script>
