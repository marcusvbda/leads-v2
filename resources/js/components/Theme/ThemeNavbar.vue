<!-- eslint-disable max-len -->
<template>
    <header class="bg-white border dark:bg-gray-800 dark:border-none">
        <nav
            class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8 flex-col"
            aria-label="Global"
        >
            <div class="flex w-full items-center">
                <div class="flex mr-8">
                    <a href="/admin" class="-m-1.5 p-1.5">
                        <text-logo size="25" />
                    </a>
                </div>
                <div class="flex lg:hidden ml-auto">
                    <button
                        type="button"
                        class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-700"
                        @click="visible = !visible"
                    >
                        <svg
                            class="h-6 w-6 dark:text-white"
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
                        v-for="(item, i) in items.filter(
                            (x) => x.visible && x.position == 'center'
                        )"
                        :key="i"
                        :item="item"
                        a_class="flex items-center gap-x-1 text-sm font-semibold leading-6 text-gray-900 mt-0"
                    />
                </div>
                <div
                    class="hidden lg:flex lg:flex-1 lg:justify-end items-center gap-5 ml-auto"
                >
                    <slot />
                    <NavbarItem
                        v-for="(item, i) in items.filter(
                            (x) => x.visible && x.position == 'right'
                        )"
                        :key="i"
                        :item="item"
                        a_class="flex items-center gap-x-1 text-sm font-semibold leading-6 text-gray-900 mt-0"
                    />
                </div>
            </div>
            <div
                v-if="visible"
                class="md:hidden w-full md:w-auto"
                id="navbar-default"
            >
                <ul
                    class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700"
                >
                    <li>
                        <NavbarItem
                            v-for="(item, i) in items.filter((x) => x.visible)"
                            :key="i"
                            :item="item"
                            a_class="block flex py-2 pl-3 pr-4 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent"
                        />
                    </li>
                </ul>
            </div>
        </nav>
    </header>
</template>
<script>
import NavbarItem from './-navbar-item.vue';

export default {
    props: ['items'],
    data() {
        return {
            visible: false,
        };
    },
    components: {
        NavbarItem,
    },
};
</script>
