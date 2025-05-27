<script setup lang="ts">
import { createUrlWithParams } from '@/helpers/url';
import { router, usePage } from '@inertiajs/vue3';
import { useDebounceFn, useUrlSearchParams } from '@vueuse/core';
import { X } from 'lucide-vue-next';
import { ref } from 'vue';

const pageUrl = usePage().url
const baseUrl = pageUrl.split('?')[0]

const searchParams = useUrlSearchParams<{ q: string, tag: string }>()
const q = ref(searchParams.q)
const cancelToken = ref<{ cancel: VoidFunction }>()

const debouncedSearch = useDebounceFn(() => {

    cancelToken.value?.cancel()

    const url = createUrlWithParams(baseUrl, {
        q: q.value,
        tag: searchParams.tag
    })

    router.get(url, {}, {
        fresh: true,
        onCancelToken: (token) => {
            cancelToken.value = token
        },
    })
}, 500);

function onInput(e: Event) {
    q.value = (e.target as HTMLInputElement).value;
    debouncedSearch();
}

function clearSearch() {

    if (pageUrl === baseUrl) return
    router.get(baseUrl, {}, {
        fresh: true,
    })
}

function clearTag() {
    const url = createUrlWithParams(baseUrl, { q: q.value })
    router.get(url, {}, {
        fresh: true,
    })
}

</script>

<template>
    <div class="flex gap-2 items-center">

        <input class="bg-gray-100 px-3 py-2 w-full" :value="q" @input="onInput" id="q" type="text" :tabindex="1"
            autocomplete="q" placeholder="Search" />
        <span @click="clearSearch()"
            class="hover:bg-gray-200 active:bg-gray-300 transition-colors px-2 py-1 rounded-md cursor-pointer">clear</span>
    </div>
    <div>
        <span v-show="searchParams.tag"
            class="text-white bg-gray-600 px-2 rounded-full py-1 inline-flex items-center gap-1">{{ searchParams.tag
            }}
            <X @click="clearTag()" class="cursor-pointer" :size="16" />
        </span>
    </div>
</template>