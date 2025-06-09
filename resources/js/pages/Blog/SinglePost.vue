<script setup lang="ts">
import { useTypedPage } from '@/composables/useTypedPage';
import CommentInput from '@/features/blog/components/CommentInput.vue';
import PostComments from '@/features/blog/components/PostComments.vue';
import Tag from '@/features/blog/components/Tag.vue';
import Votes from '@/features/blog/components/Votes.vue';
import { route } from '@/helpers/route';
import AppLayout from '@/layouts/app/AppLayout.vue';
import { SharedData } from '@/types';
import { Comment, Post } from '@/types/AppTypes';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineOptions({ layout: AppLayout });

type Props = { post: Post; comments: Comment[] };

const { post, comments } = useTypedPage<Props>().props.responseData;
const authUser = computed(() => usePage<SharedData>().props.auth.user).value;

// TODO:change this to a modal
function deletePostConfirmation() {
    return confirm('are you sure?');
}
const voteHref = route('blog.vote', { post: post.slug });
const editHref = route('blog.edit', { post: post.slug });
const deleteHref = route('blog.destroy', { post: post.slug });
</script>

<template>
    <div class="mx-auto mt-16 mb-8 flex w-2/3 items-start gap-4">
        <Votes :vote-href="voteHref" :votes="post.votes_count" :currentVote="post.votes[0]?.vote_type" />

        <div class="grow">
            <h2 class="text-app-blue mb-2 text-3xl font-bold">{{ post.title }}</h2>
            <div class="mt-4 flex gap-6" v-if="post.tags">
                <Tag v-for="tag in post.tags" :key="tag.id" :tag="tag" />
            </div>
            <p class="mt-4">{{ post.body }}</p>
            <div v-if="authUser.id === post.user.id" class="flex items-baseline gap-3">
                <Link :href="editHref">Update</Link>
                <Link :href="deleteHref" method="delete" as="button" @before="deletePostConfirmation" class="cursor-pointer"> Delete</Link>
            </div>
            <br />
            <CommentInput :postSlug="post.slug" />
            <br />
            <br />
            <PostComments :comments="comments" />
        </div>
    </div>
</template>
