<template>
    <div>
        <div
            class="p-6 bg-white border border-gray-200 rounded-lg shadow h-full flex flex-col dark:bg-gray-950 dark:border-none"
        >
            <b class="mb-1 text-lg dark:text-neutral-100">{{ title }}</b>
            <div class="number text-3xl font-bold dark:text-neutral-300">
                {{ qty }}
            </div>
            <small class="text-neutral-400 mt-auto">
                {{ subtitle }}
            </small>
        </div>
    </div>
</template>
<script>
export default {
    props: ['title', 'qty', 'subtitle'],
};
</script>
