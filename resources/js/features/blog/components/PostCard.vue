<script setup lang="ts">
import Tag from '@/features/blog/components/Tag.vue';
import { route } from '@/helpers/route';
import { getPreviewText } from '@/helpers/text';
import { Post } from '@/types/AppTypes';
import { Link } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import { Calendar } from 'lucide-vue-next';
import Votes from './Votes.vue';

const { post } = defineProps<{ post: Post }>();

const previewBody = getPreviewText(post.body, 200);

const voteHref = route('blog.vote', { post: post.slug });
const postHref = route('blog.show', { post: post.slug });
</script>

<template>
    <div class="group my-8 flex items-start gap-4">
        <Votes :vote-href="voteHref" :current-vote="post.votes[0]?.vote_type" :votes="post.votes_count" />
        <Link :prefetch="true" :href="postHref">
            <div>
                <h2 class="text-app-blue mb-2 text-xl font-bold group-hover:underline">{{ post.title }}</h2>
                <div class="my-2 flex items-center gap-4 text-xs">
                    <p>{{ post.user.name }}</p>
                    <span class="h-4 w-1 border-l-2 border-gray-300" />
                    <p class="flex items-center gap-2"><Calendar :size="16" /> {{ dayjs(post.created_at).format('DD MMMM YYYY') }}</p>
                    <span class="h-4 w-1 border-l-2 border-gray-300" />
                    <!-- TODO:add time to read to posts -->
                    <p>time to read</p>
                </div>
                <p class="text-gray-400">{{ previewBody }}</p>
                <div class="mt-4 flex gap-6" v-if="post.tags">
                    <Tag v-for="tag in post.tags" :key="tag.id" :tag="tag" />
                </div>
            </div>
        </Link>
    </div>
</template>
