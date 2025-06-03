<script setup lang="ts">
import { createUrlWithParams } from '@/helpers/url';
import { router, usePage } from '@inertiajs/vue3';
import { useDebounceFn, useUrlSearchParams } from '@vueuse/core';
import { ref, useTemplateRef } from 'vue';

const pageUrl = usePage().url;
const baseUrl = pageUrl.split('?')[0];

const searchParams = useUrlSearchParams<{ q: string; tag: string }>();
const q = ref(searchParams.q);
const inputRef = useTemplateRef<HTMLInputElement>('inputRef');
const cancelToken = ref<{ cancel: VoidFunction }>();

const debouncedSearch = useDebounceFn(() => {
    cancelToken.value?.cancel();

    const url = createUrlWithParams(baseUrl, {
        q: q.value,
        tag: searchParams.tag,
    });

    router.get(
        url,
        {},
        {
            fresh: true,
            onCancelToken: (token) => {
                cancelToken.value = token;
            },
        },
    );
}, 500);

function onInput(e: Event) {
    q.value = (e.target as HTMLInputElement).value;
    debouncedSearch();
}

function clearSearch() {

    if (inputRef.value) {
        inputRef.value.value = '';
    }
    if (pageUrl === baseUrl) return;
    router.get(baseUrl, {}, { fresh: true });
}

function clearTag() {
    const url = createUrlWithParams(baseUrl, { q: q.value });
    router.get(
        url,
        {},
        {
            fresh: true,
        },
    );
}
</script>

<template>
    <div class="flex items-center gap-2">
        <span
            v-if="inputRef?.value"
            @click="clearSearch()"
            class="cursor-pointer rounded-md px-2 py-1 transition-colors hover:bg-gray-200 active:bg-gray-300"
            >X</span
        >
        <input
            class="w-full rounded-full bg-gray-100 px-3 py-2"
            :value="q"
            @input="onInput"
            id="q"
            type="text"
            :tabindex="1"
            autocomplete="q"
            placeholder="Search"
            ref="inputRef"
        />
    </div>

    <!-- TODO: remove the tagsfrom here and add a dedicated search page or a tag cloud -->
    <!-- <div>
        <span v-show="searchParams.tag" class="inline-flex items-center gap-1 rounded-full bg-gray-600 px-2 py-1 text-white"
            >{{ searchParams.tag }}
            <X @click="clearTag()" class="cursor-pointer" :size="16" />
        </span>
    </div> -->
</template>
