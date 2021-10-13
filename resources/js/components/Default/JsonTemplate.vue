<template>
    <VueJsonPretty :data="content" />
</template>
<script>
import VueJsonPretty from "vue-json-pretty";
import "vue-json-pretty/lib/styles.css";
export default {
    props: ["content"],
    components: {
        VueJsonPretty
    }
};
</script>
