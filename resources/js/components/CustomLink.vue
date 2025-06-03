<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { HTMLAttributes } from 'vue';

const props = withDefaults(defineProps<{ enabled: boolean; href: string; class?: HTMLAttributes['class'] }>(), {
    enabled: true,
    class: '',
});
</script>

<template>
    <Link v-if="props.enabled" :href="props.href" class="hover:underline" :class="props.class">
        <slot />
    </Link>
    <p v-else class="text-gray-400" :class="props.class">
        <slot />
    </p>
</template>
