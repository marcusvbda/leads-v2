<template>
    <CustomResourceComponent :label="label" :description="description" :class="status">
        <div class="qr-section" v-loading="isLoading" v-if="status === 'qr'">
            <img src="/assets/images/264.png" v-if="isLoading" />
            <VueQr :text="qr" v-else :size="264" />
        </div>
        <template v-else> Qr Code escaneado corretamente</template>
    </CustomResourceComponent>
</template>
<script>
import { mapActions, mapGetters, mapMutations } from "vuex";
import VueQr from "vue-qr/src/packages/vue-qr.vue";

export default {
    props: ["form", "data", "errors"],
    components: {
        VueQr,
    },
    data() {
        return {
            loading: true,
            uid :this.$uid()
        };
    },
    computed: {
        ...mapGetters("wpp", ["status", "qr", "token"]),
        label() {
            return this.field?.options?.label ?? false;
        },
        description() {
            return this.field?.options?.description ?? false;
        },
        card() {
            return this.data.fields.find((x) => x._uid == "auth_card");
        },
        field() {
            return this.card.inputs.find((x) => x.options._uid == "qrcode");
        },
        isLoading() {
            return this.loading || !this.qr;
        },
    },
    watch: {
        token(val) {
            this.$set(this.form, "string_token", val);
        },
        status(val) {
            if(val == "authenticated") {
                this.setActionBtnLoading(false);
            }
        }
    },
    created() {
        setTimeout(() => {
            if (!this._isDestroyed) {
                if (!this.form.string_token) {
                    this.initFields();
                } else {
                    this.loading = false;
                }
            }
        });
    },
    methods: {
        ...mapActions("wpp", ["createSession","initSocket"]),
        ...mapMutations("wpp", ["setStatus","setQr"]),
        ...mapMutations("resource", ["setActionBtnLoading"]),
        async initFields() {    
            this.loading = true;    
            this.setActionBtnLoading(true);
            this.createSession({code: this.uid});
            const onQr = ()=> {
                this.loading = false;
            }
            this.socket = await this.initSocket({code:this.uid,callback_qr:onQr});

            if (this.form[this.field.options.field] === undefined) {
                this.$set(this.form, this.field.options.field, null);
                this.$set(this.form, "string_token", "");
            }
        },
    },
};
</script>
<style lang="scss">
.qr-section {
    width: 264px;
    height: 264px;

    img {
        width: 100%;
        height: 100%;
    }
}
</style>
