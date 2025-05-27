<script setup lang="ts">

import CustomLink from '@/components/CustomLink.vue';
import { useTypedPage } from '@/composables/useTypedPage';
import PostCard from '@/features/posts/components/PostCard.vue';
import SearchInput from '@/features/posts/components/SearchInput.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Paginated, Post } from '@/types/AppTypes';

defineOptions({ layout: AppLayout })

interface Props {
    posts: Paginated<Post>
}

const { posts } = useTypedPage<Props>().props.responseData;

const hasNext = posts.current_page !== posts.last_page
const hasPrev = posts.current_page !== 1

</script>

<template>

    <SearchInput />

    <PostCard v-for="post in posts.data" :key="post.id" :post="post" />

    <hr class="my-4" />

    <div class="flex justify-between">
        <CustomLink :enabled="hasPrev" :href="posts.prev_page_url || ''">&laquo; Prev</CustomLink>
        <p>page {{ posts.current_page }} of {{ posts.last_page }}</p>
        <CustomLink :enabled="hasNext" :href="posts.next_page_url || ''">Next &raquo;</CustomLink>
    </div>

</template>