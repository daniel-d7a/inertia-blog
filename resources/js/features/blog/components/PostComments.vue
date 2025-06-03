<script setup lang="ts">
import { Comment } from '@/types/AppTypes';
import CommentEntry from '@/features/blog/components/CommentEntry.vue';

const { comments } = defineProps<{
    comments: Comment[];
}>();
</script>

<template>
    <div class="flex flex-col gap-4">
        <template v-if="comments.length">
            <CommentEntry v-for="comment in comments" :key="comment.id" :comment="comment" />
        </template>
        <template v-else>
            <p class="my-6 text-center text-2xl text-gray-400">Be the first to comment.</p>
        </template>
    </div>
</template>
