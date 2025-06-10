<script setup lang="ts">
import { route } from '@/helpers/route';
import { Comment, Post } from '@/types/AppTypes';
import { router } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import { Trash2 } from 'lucide-vue-next';

interface Props {
    comment: Comment;
    post: Post;
}

const { comment, post } = defineProps<Props>();

// const voteHref = `/posts/{post.id}/comments/${comment.id}/vote`;

const deleteHref = route('comment.delete', { comment: comment.id });

// TODO:change this to a modal
// function deleteCommentConfirmation() {
//     return confirm('are you sure?');
// }

function deleteComment() {
    if (confirm('are you sure?')) {
        router.delete(deleteHref, {
            preserveScroll: true,
            onSuccess: () => router.get(route('blog.show', { post: post.slug }), {}, { preserveScroll: true }),
        });
    }
}
</script>

<template>
    <div class="flex items-start gap-4">
        <!-- <PostVotes :vote-href="voteHref" /> -->
        <p class="text-lg font-bold">{{ comment.votes_count }}</p>
        <div class="w-full">
            <div class="mb-2 flex items-center gap-4 text-sm">
                <p class="font-semibold">{{ comment.user.name }}</p>
                <p>{{ dayjs(comment.created_at).fromNow() }}</p>
            </div>
            <div class="grid grid-cols-10">
                <p class="col-span-9 text-gray-500">{{ comment.body }}</p>
                <!-- <Link :href="deleteHref" method="delete" as="button" @before="deleteCommentConfirmation" class="cursor-pointer">
                </Link> -->
                <Trash2
                    @click="deleteComment"
                    :size="20"
                    class="cursor-pointer justify-self-end text-gray-500 transition-colors hover:text-gray-700 active:text-gray-900"
                />
            </div>
        </div>
    </div>
</template>
