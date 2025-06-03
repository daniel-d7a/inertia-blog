<script setup lang="ts">
import { tagUrl } from '@/helpers/url';
import { Tag } from '@/types/AppTypes';
import { Link } from '@inertiajs/vue3';
import { useUrlSearchParams } from '@vueuse/core';

const { tag } = defineProps<{
    tag: Tag;
}>();

const params = useUrlSearchParams<{ q: string; tag: string }>();
const href = tagUrl(tag.name, params);
</script>

<template>
    <Link :href="href" class="rounded-full border border-gray-600 bg-gray-50 px-2 text-sm transition-colors hover:bg-gray-700 hover:text-white">{{
        tag.name
    }}</Link>
</template>
