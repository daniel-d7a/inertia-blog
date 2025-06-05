<script setup lang="ts">
import { route } from '@/helpers/route';
import { Tag } from '@/types/AppTypes';
import { Link } from '@inertiajs/vue3';
import { useUrlSearchParams } from '@vueuse/core';

const { tag } = defineProps<{
    tag: Tag;
}>();

const params = useUrlSearchParams<{ q: string; tag: string }>();
const href = route('blog.index', {
    _query: {
        q: params.q,
        tag: tag.name,
    },
});
</script>

<template>
    <Link :href="href" class="rounded-full border border-gray-600 bg-gray-50 px-2 text-sm transition-colors hover:bg-gray-700 hover:text-white">{{
        tag.name
    }}</Link>
</template>
