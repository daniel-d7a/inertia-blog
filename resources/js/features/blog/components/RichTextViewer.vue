<script setup lang="ts">
interface Props {
    content: string;
}
const { content } = defineProps<Props>();
</script>

<template>
    <div class="prose max-w-none" v-dompurify-html="content"></div>
</template>
