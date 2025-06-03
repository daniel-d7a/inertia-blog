<script setup lang="ts">
import { Comment } from '@/types/AppTypes';
import dayjs from 'dayjs';
const { comment } = defineProps<{ comment: Comment }>();

// const voteHref = `/posts/{post.id}/comments/${comment.id}/vote`;
</script>

<template>
    <div class="flex items-start gap-4">
        <!-- <PostVotes :vote-href="voteHref" /> -->
        <p class="text-lg font-bold">{{ comment.votes_count }}</p>
        <div>
            <div class="mb-2 flex items-center gap-4 text-sm">
                <p class="font-semibold">{{ comment.user.name }}</p>
                <p>{{ dayjs(comment.created_at).fromNow() }}</p>
            </div>
            <p class="text-gray-500">{{ comment.body }}</p>
        </div>
    </div>
</template>
