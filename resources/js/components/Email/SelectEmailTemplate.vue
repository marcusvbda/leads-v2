<template>
    <div>
        <div class="row">
            <div class="col-12">
                <h1>Selecione um modelo de Email</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-4" v-for="(chunk, c) in chunked_templates" :key="c">
                            <template-card v-for="(template, i) in chunk" :key="i" :template="template" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import _ from 'lodash'
export default {
    props: ['templates'],
    computed: {
        chunked_templates() {
            let templates = this.templates
            return _.chunk(templates, 4)
        },
    },
    components: {
        'template-card': require('./partials/-template-card').default,
    },
}
</script>