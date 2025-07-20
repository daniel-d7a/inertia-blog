<script setup lang="ts">
import Tag from '@/features/blog/components/Tag.vue';
import { route } from '@/helpers/route';
import { Post } from '@/types/AppTypes';
import { Link } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import { Calendar } from 'lucide-vue-next';
import Votes from './Votes.vue';

interface Props {
    post: Post;
}

const { post } = defineProps<Props>();

const postHref = route('blog.show', { post: post.slug });
</script>

<template>
    <div class="group my-8 flex items-center gap-4">
        <Votes :current-vote="post.current_user_vote" :votes="post.votes" voteable-type="Post" :voteable_id="post.id" />
        <Link :prefetch="true" :href="postHref" class="grow">
            <div>
                <h2 class="text-app-blue mb-2 text-3xl font-bold group-hover:underline">{{ post.title }}</h2>
                <div class="my-2 flex items-center gap-3 text-xs">
                    <p>{{ post.user.name }}</p>
                    <span class="h-4 w-1 border-l-2 border-gray-300" />
                    <p class="flex items-center gap-2"><Calendar :size="16" /> {{ dayjs(post.created_at).format('DD MMMM YYYY') }}</p>
                    <span class="h-4 w-1 border-l-2 border-gray-300" />
                    <p>{{ post.time_to_read }} minute read</p>
                </div>
                <!-- <p class="text-gray-400">{{ previewBody }}</p> -->
                <div class="mt-4 flex gap-6" v-if="post.tags">
                    <Tag v-for="tag in post.tags" :key="tag.id" :tag="tag" />
                </div>
            </div>
        </Link>
        <div class="grid max-w-48 place-content-center p-4">
            <img v-if="post.image_banner_url" :src="post.image_banner_url" alt="Post Banner" class="h-auto w-full rounded-md object-contain" />
        </div>
    </div>
    <div class="w-full border border-gray-100"></div>
</template>
