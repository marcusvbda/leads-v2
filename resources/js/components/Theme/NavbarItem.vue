<!-- eslint-disable max-len -->
<template>
    <div class="relative">
        <a
            type="button"
            class="flex items-center gap-x-1 text-sm font-semibold leading-6 text-gray-900"
            aria-expanded="false"
            href="#"
            @click.prevent="toggleClick"
        >
            {{ title }}
            <svg
                v-if="items.length"
                class="h-5 w-5 flex-none text-gray-400"
                viewBox="0 0 20 20"
                fill="currentColor"
                aria-hidden="true"
            >
                <path
                    fill-rule="evenodd"
                    d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                    clip-rule="evenodd"
                />
            </svg>
        </a>
        <div
            v-if="items.length && visible"
            :class="[
                'absolute -left-8 top-full z-10 mt-3 w-screen max-w-md overflow-hidden',
                'rounded-3xl bg-white shadow-lg ring-1 ring-gray-900/5',
            ]"
        >
            <div class="p-4">
                <div
                    class="group relative flex items-center gap-x-6 rounded-lg p-4 text-sm leading-6 hover:bg-gray-50"
                >
                    <div
                        class="flex h-11 w-11 flex-none items-center justify-center rounded-lg bg-gray-50 group-hover:bg-white"
                    >
                        <svg
                            class="h-6 w-6 text-gray-600 group-hover:text-indigo-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z"
                            />
                        </svg>
                    </div>
                    <div class="flex-auto">
                        <a href="#" class="block font-semibold text-gray-900">
                            Analytics
                            <span class="absolute inset-0"></span>
                        </a>
                        <p class="mt-1 text-gray-600">
                            Get a better understanding of your traffic
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
// import ClickOutside from 'vue-click-outside';
export default {
    props: {
        title: {
            type: String,
            default: '',
        },
        items: {
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            visible: false,
        };
    },
    // directives: {
    //     ClickOutside,
    // },
    methods: {
        hide() {
            this.visible = false;
        },
        clicked() {
            if (!this.items.length) {
                return this.$emit('clicked');
            }
            this.visible = !this.visible;
        },
    },
};
</script>
