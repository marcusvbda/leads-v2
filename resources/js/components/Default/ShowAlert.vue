<template></template>
<script>
export default {
    props: ['messages'],
    mounted() {
        for (let i in this.messages) {
            this.$message({ dangerouslyUseHTMLString: true, showClose: true, message: this.messages[i].message, type: this.messages[i].type })
        }
    },
}
</script>