<script setup lang="ts">
import { Comment } from '@/types/AppTypes';
import PostVotes from './PostVotes.vue';

const props = defineProps<{ comment: Comment }>();
const comment = props.comment;

const voteHref = `/posts/{post.id}/comments/${comment.id}/vote`

</script>

<template>
    <div class="border rounded-xl shadow px-2 py-2">
        <p class="text-sm font-bold">{{ comment.user.name }}</p>
        <p>{{ comment.body }}</p>
        <PostVotes :vote-href="voteHref" />
        <p class="mt-2">{{ comment.votes_count }}</p>
    </div>
</template>