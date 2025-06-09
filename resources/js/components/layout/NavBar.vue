<script lang="ts" setup>
import SearchInput from '@/components/layout/SearchInput.vue';
import { getInitials } from '@/composables/useInitials';
import { route } from '@/helpers/route';
import { NavItem, SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { Bell, Bookmark } from 'lucide-vue-next';
import { computed } from 'vue';

const links: NavItem[] = [
    { title: 'Home', href: route('home') },
    { title: 'Blog', href: route('blog.index') },
    { title: 'Create', href: route('blog.create') },
    { title: 'About', href: route('about') },
];

const page = usePage<SharedData>();
const auth = computed(() => page.props.auth);

const initials = getInitials(auth.value?.user?.name);
</script>

<template>
    <nav class="my-4 flex justify-between px-8">
        <div class="flex items-center gap-8">
            <p class="text-3xl font-extrabold">Encode</p>
            <ul class="flex items-center gap-4">
                <li v-for="link in links" :key="link.title">
                    <a :href="link.href">{{ link.title }}</a>
                </li>
            </ul>
        </div>
        <div class="flex items-center gap-4">
            <SearchInput />
            <Bell :size="40" class="rounded-full bg-gray-100 p-3" />
            <Bookmark :size="40" class="rounded-full bg-gray-100 p-3 text-xl" />
            <span class="flex size-10 items-center justify-center rounded-full bg-gray-100 p-3 text-lg">{{ initials }}</span>
        </div>
    </nav>
</template>
