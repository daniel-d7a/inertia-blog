<script lang="ts" setup>
import SearchInput from '@/components/layout/SearchInput.vue';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { getInitials } from '@/composables/useInitials';
import { route } from '@/helpers/route';
import { NavItem, SharedData } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { Bell, Bookmark, CircleUserRound, LogOut } from 'lucide-vue-next';
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
            <DropdownMenu>
                <DropdownMenuTrigger>
                    <span class="cursor-pointer flex size-10 items-center justify-center rounded-full bg-gray-100 p-3 text-lg">{{ initials }}</span>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end">
                    <DropdownMenuItem>
                        <div class="mx-2 flex items-center gap-2 py-2">
                            <span class="flex size-8 items-center justify-center rounded-full bg-gray-100 px-2 py-2 text-lg">{{ initials }}</span>
                            <div class="flex flex-col items-start text-xs">
                                <p class="font-semibold">{{ auth.user.name }}</p>
                                <p class="font-light">{{ auth.user.email }}</p>
                            </div>
                        </div>
                    </DropdownMenuItem>
                    <DropdownMenuSeparator />
                    <DropdownMenuItem>
                        <a
                            :href="`profile/${auth.user.id}`"
                            class="flex cursor-pointer items-center gap-2 px-3 py-1 text-sm text-gray-600 transition hover:text-gray-800 hover:underline"
                        >
                            <CircleUserRound :size="16" color="" />
                            Profile
                        </a>
                    </DropdownMenuItem>
                    <DropdownMenuSeparator />
                    <DropdownMenuItem>
                        <a
                            class="flex cursor-pointer items-center gap-2 px-3 py-1 text-sm text-gray-600 transition hover:text-gray-800 hover:underline"
                        >
                            <LogOut :size="16" />
                            Logout
                        </a>
                    </DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>
        </div>
    </nav>
</template>
