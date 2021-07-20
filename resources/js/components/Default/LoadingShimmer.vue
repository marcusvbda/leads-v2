<template>
    <div class="my-1">
        <div v-if="loading" class="shimmer" :style="{ height: h ? h : 20 }"></div>
        <slot v-else></slot>
    </div>
</template>
<script>
export default {
    props: ['loading', 'h'],
}
</script>