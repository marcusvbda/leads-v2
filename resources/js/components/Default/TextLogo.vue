<template>
    <h1 class="logo" :style="{ fontSize: `${size}px` }">
        {{ app_name }}
        <small v-if="complete">
            <b>{{ app_description }}</b>
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
    },
    computed: {
        app_name() {
            return laravel.config.name
        },
        app_description() {
            return laravel.config.description
        },
    },
}
</script>
<style lang="scss" scoped>
@import '../../../sass/variables';

.logo {
    letter-spacing: -2px;
    color: inherit;
    display: flex;
    align-items: center;
    position: relative;
    color: #001737;
    // text-transform: lowercase;
    font-weight: bolder;
    margin-bottom: 0;
    b {
        color: $primary;
        font-size: 50%;
        margin-left: 10%;
    }
}
</style>