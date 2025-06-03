<script setup lang="ts">
import { getPreviewText } from '@/helpers/text';
import { createUrlWithParams } from '@/helpers/url';
import { Post } from '@/types/AppTypes';
import { Link } from '@inertiajs/vue3';
import { useUrlSearchParams } from '@vueuse/core';
import PostVotes from './PostVotes.vue';

const { post } = defineProps<{ post: Post }>();

const previewBody = getPreviewText(post.body);

const params = useUrlSearchParams<{ q: string; tag: string }>();

function tagUrl(tagName: string) {
    return createUrlWithParams('/posts', { q: params.q, tag: tagName });
}

const voteHref = `/posts/${post.id}/vote`;
</script>

<template>
    <div class="my-8 flex items-start gap-4">
        <PostVotes :vote-href="voteHref" :current-vote="post.votes[0]?.vote_type" :post-id="post.id" :votes="post.votes_count" />
        <Link :prefetch="true" :href="`/posts/${post.id}`">
            <div>
                <h2 class="mb-2 text-xl font-bold">{{ post.title }}</h2>
                <p class="text-sm font-semibold">{{ post.user.name }}</p>
                <p>{{ previewBody }}</p>
                <div class="mt-2 flex gap-6" v-if="post.tags">
                    <Link
                        :href="tagUrl(tag.name)"
                        class="rounded-full border border-black/60 px-2 py-1 transition-colors hover:bg-gray-700 hover:text-white"
                        v-for="tag in post.tags"
                        :key="tag.id"
                        >{{ tag.name }}</Link
                    >
                </div>
            </div>
        </Link>
    </div>
</template>
