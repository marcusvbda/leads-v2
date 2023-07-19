<template>
    <h1
        class="text-neutral-900 font-bold text-lg flex items-baseline"
        :style="{ fontSize: `${size}px`, color }"
    >
        <b class="dark:text-neutral-100">{{ app_name }}</b>
        <small v-if="complete" class="font-semibold text-blue-700 text-sm">
            {{ app_description }}
        </small>
    </h1>
</template>
<script>
export default {
    props: {
        size: {
            type: String,
            default: '60',
        },
        complete: {
            type: Boolean,
            default: true,
        },
        color: {
            type: String,
            default: 'black',
        },
    },
    computed: {
        app_name() {
            return laravel.config.name;
        },
        app_description() {
            return laravel.config.description;
        },
    },
};
</script>
