<script setup lang="ts">
import { cn } from '@/lib/utils';
import { Link } from '@inertiajs/vue3';
import { HTMLAttributes } from 'vue';


const props = withDefaults(defineProps<{ enabled: boolean, href: string, class?: HTMLAttributes['class'] }>(), {
    enabled: true,
    class: ""
})


</script>

<template>

    <Link v-if="props.enabled" :href="props.href" :class="props.class">
    <slot />
    </Link>
    <p v-else="!props.enabled" :class="cn('text-gray-400', props.class)">
        <slot />
    </p>

</template>