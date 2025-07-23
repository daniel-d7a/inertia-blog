<script setup lang="ts">
import { getInitials } from '@/composables/useInitials';
import { useTypedPage } from '@/composables/useTypedPage';
import AppLayout from '@/layouts/app/AppLayout.vue';
import { SharedData } from '@/types';
import { Link, Tag } from '@/types/AppTypes';
import { usePage } from '@inertiajs/vue3';
import { MessageSquareMore } from 'lucide-vue-next';
import { TabsContent, TabsList, TabsRoot, TabsTrigger } from 'reka-ui';
import { computed, onMounted, ref, watch } from 'vue';

defineOptions({ layout: AppLayout });

interface Props {
    followersCount: number;
    followingCount: number;
    links: Link[];
    tags: Tag[];
    postsCount: number;
    commentsCount: number;
    votesCount: number;
    userId: number;
}

type Tab = { name: string; count: number };

const page = usePage<SharedData>();
const user = computed(() => page.props.auth.user);

const userData = useTypedPage<Props>().props.responseData;
console.log('userData', userData);
console.log('id', userData.userId, user.value.id);

const initials = getInitials(user.value.name);

const tabs = ref<Tab[]>([
    { name: 'posts', count: userData.postsCount },
    { name: 'comments', count: userData.commentsCount },
    { name: 'votes', count: userData.votesCount },
    { name: 'followers', count: userData.followersCount },
    { name: 'following', count: userData.followingCount },
]);
const tabRef = ref();
const triggerRef = ref();
const indicator = ref({
    width: '80px',
    left: '0px',
});

function moveIndicatorToTab(tabName: string) {
    const index = tabs.value.findIndex((tab) => tab.name === tabName);
    if (index !== -1) {
        const width = `${triggerRef.value[index].clientWidth}px`;
        const left = `${triggerRef.value[index].offsetLeft}px`;
        indicator.value = { width, left };
    }
}

onMounted(() => {
    tabs.value = [
        { name: 'posts', count: userData.postsCount },
        { name: 'comments', count: userData.commentsCount },
        { name: 'votes', count: userData.votesCount },
        { name: 'followers', count: userData.followersCount },
        { name: 'following', count: userData.followingCount },
        // { name: 'threads', count: 45 },
        // { name: 'bookmarks', count: 25 },
        // { name: 'awards', count: 0 },
    ];

    if (tabRef.value) {
        moveIndicatorToTab(tabRef.value);
    }
});
watch(
    () => tabRef.value,
    (newValue) => {
        moveIndicatorToTab(newValue);
    },
    { immediate: true },
);
</script>

<!-- TODO: make a custom pill as it is used many times -->
<template>
    <div class="flex items-start gap-8">
        <div class="grid h-60 w-80 place-content-center bg-gray-100 text-9xl">{{ initials }}</div>
        <div class="max-w-xl">
            <div class="flex justify-between">
                <div>
                    <p class="shrink text-4xl font-semibold capitalize">{{ user.display_name }}</p>
                    <p class="text-sm font-light">@{{ user.name }}</p>
                </div>
                <div v-if="userData.userId !== user.id" class="flex gap-2">
                    <span class="grid size-10 cursor-pointer place-content-center rounded-full bg-gray-100 text-sm"><MessageSquareMore /></span>
                    <span class="grid h-10 w-24 cursor-pointer place-content-center rounded-full bg-gray-100 font-semibold">Follow</span>
                </div>
                <div v-else class="flex gap-2">
                    <span class="grid h-10 shrink-0 cursor-pointer place-content-center rounded-full bg-gray-100 px-2 font-semibold"
                        >Edit profile</span
                    >
                </div>
            </div>
            <p class="mt-4">{{ user.bio || '/* This bio is very empty */' }}</p>
            <div class="mt-4 flex flex-wrap gap-2">
                <span v-for="tag in userData.tags" :key="tag.id" class="cursor-pointer rounded-full bg-gray-100 px-3 py-1 text-sm">{{
                    tag.name
                }}</span>
            </div>
            <div class="mt-4 flex gap-4">
                <!-- TODO: add number formatting -->
                <p>
                    <span class="font-semibold">{{ userData.followersCount }}</span> followers
                </p>
                <p>
                    <span class="font-semibold">{{ userData.followingCount }}</span> following
                </p>
            </div>
            <div class="text-app-blue mt-4 flex items-baseline gap-4 text-lg">
                <div v-for="link in userData.links" :key="link.link" class="flex">
                    <a :href="link.link" class="underline">{{ link.name }}</a>
                    <span
                        :class="{ hidden: link.link === userData.links[userData.links.length - 1].link }"
                        class="ms-3 -translate-y-2 text-4xl text-black no-underline"
                        >•</span
                    >
                </div>
            </div>
        </div>
    </div>
    <TabsRoot v-model:model-value="tabRef" :default-value="tabs[0].name" orientation="vertical" class="mt-8">
        <TabsList aria-label="tabs example" class="relative mb-4 flex w-full items-center gap-12 overflow-x-auto border-b">
            <TabsTrigger v-for="tab in tabs" :value="tab.name" :key="tab.name" class="flex shrink-0 gap-1 capitalize">
                <p ref="triggerRef" class="mb-1">{{ tab.name }} ({{ tab.count || 0 }})</p></TabsTrigger
            >
            <div
                class="bg-app-blue absolute bottom-0 h-1 transition-all"
                :style="{ width: indicator.width, transform: `translateX(${indicator.left})` }"
            ></div>
        </TabsList>
        <TabsContent v-for="tab in tabs" :value="tab.name" :key="tab.name"> {{ tab.name }} content </TabsContent>
    </TabsRoot>
</template>
