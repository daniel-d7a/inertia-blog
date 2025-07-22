<script setup lang="ts">
import { getInitials } from '@/composables/useInitials';
import AppLayout from '@/layouts/app/AppLayout.vue';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { MessageSquareMore } from 'lucide-vue-next';
import { TabsContent, TabsList, TabsRoot, TabsTrigger } from 'reka-ui';
import { computed, onMounted, ref, watch, watchEffect } from 'vue';

defineOptions({ layout: AppLayout });

const page = usePage<SharedData>();
const user = computed(() => page.props.auth.user);

const initials = getInitials(user.value.name);

const links = [
    { text: 'my channel', link: 'https://youtube1.com' },
    { text: 'my channel', link: 'https://youtube2.com' },
    { text: 'my channel', link: 'https://youtube3.com' },
    { text: 'my channel', link: 'https://youtube4.com' },
];

const tabs = [
    { name: 'posts', count: 120 },
    { name: 'threads', count: 45 },
    { name: 'likes', count: 30 },
    { name: 'bookmarks', count: 25 },
    { name: 'comments', count: 60 },
    { name: 'votes', count: 10 },
    { name: 'followers', count: 0 },
    { name: 'following', count: 0 },
    { name: 'awards', count: 0 },
];

const tabRef = ref();
const triggerRef = ref();
const indicator = ref({
    width: '80px',
    left: '0px',
});

watchEffect(() => {
    console.log(tabRef.value);
});

function moveIndicatorToTab(tabName: string) {
    const index = tabs.findIndex((tab) => tab.name === tabName);
    if (index !== -1) {
        const width = `${triggerRef.value[index].clientWidth}px`;
        const left = `${triggerRef.value[index].offsetLeft}px`;
        indicator.value = { width, left };
    }
}

onMounted(() => {
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
                    <p class="shrink text-4xl font-semibold capitalize">display name</p>
                    <p class="text-sm font-light">@username</p>
                </div>
                <div class="flex gap-2">
                    <span class="grid size-10 cursor-pointer place-content-center rounded-full bg-gray-100 text-sm"><MessageSquareMore /></span>
                    <span class="grid h-10 w-24 cursor-pointer place-content-center rounded-full bg-gray-100 font-semibold">Follow</span>
                </div>
            </div>
            <p class="mt-4">
                user description <br />
                laksdjfl;laskdjfl;aksjdfal;ksdjfla;skjdfl;aksjf alksdjfla;skjdf laskdjfl;aksjdf l;askdjfal;ksdjfl;askdjfl;aksjdf asdfkjlka
            </p>
            <div class="mt-4 flex flex-wrap gap-2">
                <span class="cursor-pointer rounded-full bg-gray-100 px-3 py-1 text-sm">web development</span>
                <span class="cursor-pointer rounded-full bg-gray-100 px-3 py-1 text-sm">web development</span>
                <span class="cursor-pointer rounded-full bg-gray-100 px-3 py-1 text-sm">web development</span>
                <span class="cursor-pointer rounded-full bg-gray-100 px-3 py-1 text-sm">web development</span>
                <span class="cursor-pointer rounded-full bg-gray-100 px-3 py-1 text-sm">web development</span>
                <span class="cursor-pointer rounded-full bg-gray-100 px-3 py-1 text-sm">web development</span>
                <span class="cursor-pointer rounded-full bg-gray-100 px-3 py-1 text-sm">web development</span>
            </div>
            <div class="mt-4 flex gap-4">
                <!-- TODO: add number formatting -->
                <p><span class="font-semibold">234</span> followers</p>
                <p><span class="font-semibold">923</span> following</p>
            </div>
            <div class="text-app-blue mt-4 flex items-baseline gap-4 text-lg">
                <div v-for="link in links" :key="link.link" class="flex">
                    <a :href="link.link" class="underline">{{ link.text }}</a>
                    <span :class="{ hidden: link.link === links[links.length - 1].link }" class="ms-3 -translate-y-2 text-4xl text-black no-underline"
                        >•</span
                    >
                </div>
            </div>
        </div>
    </div>
    <TabsRoot v-model:model-value="tabRef" :default-value="tabs[0]" orientation="vertical" class="mt-8">
        <TabsList aria-label="tabs example" class="relative mb-4 flex w-full items-center gap-12 overflow-x-scroll border-b">

            <TabsTrigger v-for="tab in tabs" :value="tab.name" :key="tab.name" class="flex shrink-0 gap-1 capitalize">
                <p ref="triggerRef" class="mb-1">{{ tab.name }} ({{ tab.count }})</p></TabsTrigger
            >
            <div
                class="bg-app-blue absolute bottom-0 h-1 transition-all"
                :style="{ width: indicator.width, transform: `translateX(${indicator.left})` }"
            ></div>
        </TabsList>
        <TabsContent v-for="tab in tabs" :value="tab.name" :key="tab.name"> {{ tab.name }} content </TabsContent>
    </TabsRoot>
</template>
