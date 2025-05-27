<script setup lang="ts">

import { useTypedPage } from '@/composables/useTypedPage';
import CommentEntry from '@/features/posts/components/CommentEntry.vue';
import CommentInput from '@/features/posts/components/CommentInput.vue';
import PostVotes from '@/features/posts/components/PostVotes.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { SharedData } from '@/types';
import { Comment, Post } from '@/types/AppTypes';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineOptions({ layout: AppLayout })

type Props = { post: Post, comments: Comment[] }

const { post, comments } = useTypedPage<Props>().props.responseData;
const authUser = computed(() => usePage<SharedData>().props.auth.user).value
console.log({ authUser, post });


function deletePostConfirmation() {
    return confirm("are you sure?")
}

const voteHref = `/posts/${post.id}/vote`

</script>

<template>

    <div class="my-8 flex items-start gap-4">

        <PostVotes :vote-href="voteHref" :votes="post.votes_count" :postId="post.id"
            :currentVote="post.votes[0]?.vote_type" />

        <div class="grow">
            <h2 class="text-xl font-bold mb-2">{{ post.title }}</h2>
            <div class="flex gap-6 mt-2" v-if="post.tags">
                <span class="border border-black/60 px-2 py-1 rounded-full" v-for="tag in post.tags" :key="tag.id">
                    {{ tag.name }}
                </span>
            </div>
            <p class="mt-4">{{ post.body }}</p>
            <div v-if="authUser.id === post.user.id" class="flex gap-3 items-baseline">
                <Link :href="`/posts/${post.id}/edit`">Update</Link>
                <Link :href="`/posts/${post.id}`" method="delete" as="button" @before="deletePostConfirmation"
                    class="cursor-pointer">
                Delete</Link>
            </div>
            <hr class="my-3" />
            <h3 class="text-xl font-bold my-6">Comments:</h3>

            <CommentInput :postId="post.id" />

            <div class="flex flex-col gap-4">
                <template v-if="comments.length">
                    <CommentEntry v-for="comment in comments" :key="comment.id" :comment="comment" />
                </template>
                <template v-else>
                    <p class="text-2xl text-gray-400 text-center my-6">No Comments Yet.</p>
                </template>
            </div>
        </div>
    </div>

</template>