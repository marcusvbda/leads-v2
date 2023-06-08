<!-- eslint-disable max-len -->
<template>
    <header class="bg-white">
        <nav
            class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8"
            aria-label="Global"
        >
            <div class="flex lg:flex-1">
                <a href="/admin" class="-m-1.5 p-1.5">
                    <text-logo size="50" />
                </a>
            </div>
            <div class="flex lg:hidden">
                <button
                    type="button"
                    class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700"
                >
                    <span class="sr-only">Open main menu</span>
                    <svg
                        class="h-6 w-6"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        aria-hidden="true"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"
                        />
                    </svg>
                </button>
            </div>
            <div class="hidden lg:flex lg:gap-x-12">
                <NavbarItem
                    v-for="(item, i) in items.filter((x) => x.visible)"
                    :key="i"
                    :item="item"
                />
            </div>
            <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                <!-- <NavbarItem title="Log in"/> -->
            </div>
        </nav>
    </header>
</template>
<script>
import NavbarItem from './-navbar-item.vue';
export default {
    props: ['items'],
    components: {
        NavbarItem,
    },
    mounted() {
        console.log('TESTE', this.items);
    },
};
</script>
