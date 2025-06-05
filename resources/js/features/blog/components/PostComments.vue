<script setup lang="ts">
import CommentEntry from '@/features/blog/components/CommentEntry.vue';
import { Comment } from '@/types/AppTypes';

interface Props {
    comments: Comment[];
}

const { comments } = defineProps<Props>();
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
