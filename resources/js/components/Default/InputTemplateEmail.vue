<template>
    <div class="row">
        <div class="col-12 d-flex flex-column">
            <h4 class="mb-4"><b>Envio de Email</b></h4>
            <el-tabs type="border-card" v-model="form.type">
                <el-tab-pane
                    label="Modelo de Email"
                    value="template"
                    name="template"
                >
                    <div class="shimmer select" v-if="loading.integrator" />
                    <el-select
                        v-else
                        class="w-full"
                        clearable
                        v-model="form.integrator_id"
                        filterable
                        placeholder="Selecione o integrador que deseja utilizar para enviar"
                        :popper-append-to-body="false"
                    >
                        <el-option
                            v-for="(item, i) in integrators"
                            :key="i"
                            :label="item.name"
                            :value="String(item.id)"
                        />
                    </el-select>

                    <div class="shimmer select mt-3" v-if="loading.template" />
                    <el-select
                        v-else
                        class="w-full mt-3"
                        clearable
                        v-model="form.template_id"
                        filterable
                        placeholder="Selecione o template que deseja enviar"
                        :popper-append-to-body="false"
                    >
                        <el-option
                            v-for="(item, i) in templates"
                            :key="i"
                            :label="item.name"
                            :value="String(item.id)"
                        />
                    </el-select>
                </el-tab-pane>
                <el-tab-pane label="Customizado" value="custom" name="custom">
                    <div
                        class="shimmer select mb-3"
                        v-if="loading.integrator"
                    />
                    <el-select
                        v-else
                        class="w-full mb-3"
                        clearable
                        v-model="form.integrator_id"
                        filterable
                        placeholder="Selecione o integrador que deseja utilizar para enviar"
                        :popper-append-to-body="false"
                    >
                        <el-option
                            v-for="(item, i) in integrators"
                            :key="i"
                            :label="item.name"
                            :value="String(item.id)"
                        />
                    </el-select>
                    <input
                        class="form-control mb-3"
                        v-model="form.subject"
                        placeholder="Assunto do Email"
                    />
                    <textarea
                        :rows="4"
                        class="form-control mb-3"
                        v-model="form.body"
                        placeholder="Corpo do Email"
                        style="resize: none"
                    />
                </el-tab-pane>
            </el-tabs>
        </div>
    </div>
</template>
<script>
export default {
    props: ['parent_form'],
    data() {
        return {
            loading: {
                templates: true,
                integrator: true,
            },
            templates: [],
            integrators: [],
            form: {
                type: 'template',
                subject: null,
                body: null,
                template_id: null,
                integrator_id: null,
            },
        };
    },
    watch: {
        form: {
            deep: true,
            handler(val) {
                this.$emit('input', val);
            },
        },
    },
    created() {
        this.init();
    },
    methods: {
        init() {
            this.getTemplates();
            this.getIntegrators();
        },
        getTemplates() {
            this.$http
                .post('/vstack/json-api', {
                    model: '\\App\\Http\\Models\\EmailTemplate',
                    order_by: ['name', 'desc'],
                })
                .then(({ data }) => {
                    console.log('TEMPLATE', data);
                    this.templates = data;
                    this.loading.templates = false;
                });
        },
        getIntegrators() {
            this.$http
                .post('/vstack/json-api', {
                    model: '\\App\\Http\\Models\\MailIntegrator',
                    order_by: ['name', 'desc'],
                })
                .then(({ data }) => {
                    console.log('INTEGRATOR', data);
                    this.integrators = data;
                    this.loading.integrator = false;
                });
        },
    },
};
</script>
