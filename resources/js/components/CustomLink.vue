<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { HTMLAttributes } from 'vue';

interface Props {
    enabled: boolean;
    href: string;
    class?: HTMLAttributes['class'];
}

const props = withDefaults(defineProps<Props>(), {
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
