<script setup lang="ts">
import { getPreviewText } from '@/helpers/text';
import { Post } from '@/types/AppTypes';
import { Link, usePage } from '@inertiajs/vue3';
import PostVotes from './PostVotes.vue';
import { useUrlSearchParams } from '@vueuse/core';
import { createUrlWithParams } from '@/helpers/url';

const { post } = defineProps<{ post: Post }>()

const previewBody = getPreviewText(post.body)

const pageUrl = usePage().url;

const params = useUrlSearchParams<{ q: string, tag: string }>()

console.log(Object.entries(params));

function tagUrl(tagName: string) {
    return createUrlWithParams('/posts', { q: params.q, tag: tagName })
}

const voteHref = `/posts/${post.id}/vote`

</script>

<template>

    <div class="my-8 flex items-start gap-4">
        <PostVotes :vote-href="voteHref" :current-vote="post.votes[0]?.vote_type" :post-id="post.id"
            :votes="post.votes_count" />
        <Link :prefetch="true" :href="`/posts/${post.id}`">
        <div>
            <h2 class="text-xl font-bold mb-2">{{ post.title }}</h2>
            <p class="text-sm font-semibold">{{ post.user.name }}</p>
            <p>{{ previewBody }}</p>
            <div class="flex gap-6 mt-2" v-if="post.tags">
                <Link :href="tagUrl(tag.name)"
                    class="border border-black/60 px-2 py-1 rounded-full hover:bg-gray-700 hover:text-white transition-colors"
                    v-for="tag in post.tags" :key="tag.id">{{
                        tag.name }}</Link>
            </div>
        </div>
        </Link>
    </div>


</template>