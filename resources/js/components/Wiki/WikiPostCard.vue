<template>
    <div class="col-md-4 col-sm-12 wiki-post-card">
        <loading-shimmer :loading="loading" :h="150" class="h-100">
            <div class="card" @click="goTo(page.url)">
                <div class="card-body">
                    <h4 class="text-center m-0">{{ page.title }}</h4>
                    <div class="my-3 text-center" v-html="page.description" />
                </div>
            </div>
        </loading-shimmer>
    </div>
</template>
<script>
export default {
    props: ["row_id"],
    data() {
        return {
            loading: true,
            page: {}
        };
    },
    created() {
        this.$http
            .post("/vstack/json-api", {
                model: "\\App\\Http\\Models\\WikiPage",
                filters: {
                    where: [["id", "=", this.row_id]]
                }
            })
            .then(({ data }) => {
                this.page = data[0];
                this.loading = false;
            });
    },
    methods: {
        goTo(url) {
            this.$loading({ text: "Aguarde ..." });
            window.location.href = url;
        }
    }
};
</script>
<style lang="scss">
.wiki-post-card {
    margin-bottom: 20px;

    .card {
        cursor: pointer;
        opacity: 0.8;
        transition: 0.4;

        &:hover {
            opacity: 1;
        }
    }
}
</style>
