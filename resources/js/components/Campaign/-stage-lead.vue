<template>
    <div
        class="max-w-sm rounded overflow-hidden shadow-lg bg-white dark:bg-gray-950"
        draggable
        @dragstart="startDrag"
    >
        <div class="px-6 py-4">
            <p
                class="text-gray-700 dark:text-neutral-200 text-xs"
                v-for="(field, i) in campaign.fields.filter(
                    (x) => !['campaign_ids', 'id'].includes(x)
                )"
                :key="i"
            >
                <b v-html="makeLabel(field)" /> : {{ lead[field] }}
            </p>
        </div>
    </div>
</template>
<script>
import { mapGetters } from 'vuex';

export default {
    props: ['lead'],
    computed: {
        ...mapGetters('campaign', ['campaign', 'fields']),
    },
    methods: {
        startDrag(event) {
            this.$emit('dragstart', event);
        },
        makeLabel(field) {
            return this.fields.find((x) => x.id === field).value;
        },
    },
};
</script>
