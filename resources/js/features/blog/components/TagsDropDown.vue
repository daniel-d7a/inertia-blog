<script lang="ts" setup>
import { Label } from '@/components/ui/label';
import { useTypedPage } from '@/composables/useTypedPage';
import { Tag } from '@/types/AppTypes';
import { onClickOutside } from '@vueuse/core';
import { ChevronDown, X } from 'lucide-vue-next';
import { ref, useTemplateRef } from 'vue';

interface ResponseData {
    tags: Tag[];
}

interface Props {
    selectedTags: (Tag | string)[];
}

const emit = defineEmits<{
    (e: 'selectTag', tag: Tag | string): void;
    (e: 'removeTag', tag: Tag | string): void;
}>();

const { selectedTags } = defineProps<Props>();

const tags = useTypedPage<ResponseData>().props.responseData.tags;

const filteredTags = ref<(Tag | string)[]>(tags);
const dropDownOpen = ref(false);

const dropDownRef = useTemplateRef('dropDown');
const inputRef = useTemplateRef('input');

onClickOutside(dropDownRef, () => {
    dropDownOpen.value = false;
});

function isTag(val: unknown): val is Tag {
    return typeof val === 'object' && val !== null && 'name' in val && typeof val.name === 'string';
}

function selectTag(tag: Tag | string) {
    if (!selectedTags.includes(tag)) emit('selectTag', tag);
    closeDropDown();
}

function removeSelectedTag(tag: Tag | string) {
    emit('removeTag', tag);
}

function tagInput(e: Event) {
    const input = e.target as HTMLInputElement;
    const value = input.value;

    filteredTags.value = tags.filter((v) => {
        const data = isTag(v) ? v.name : v;
        return data.includes(value);
    });

    dropDownOpen.value = true;
}

function createTag() {
    if (!inputRef?.value?.value) return;
    emit('selectTag', inputRef.value.value);
    closeDropDown();
}

function closeDropDown() {
    dropDownOpen.value = false;
    if (inputRef.value) {
        inputRef.value.value = '';
    }
    filteredTags.value = tags;
}
</script>

<template>
    <div class="grid gap-2">
        <Label class="text-xl font-bold" for="tags">Tags</Label>
        <div class="flex items-center gap-2">
            <input
                ref="input"
                class="w-full rounded-sm border px-2 py-2 placeholder:text-sm placeholder:font-semibold"
                @input="tagInput"
                id="tags"
                type="text"
                autofocus
                :tabindex="3"
                placeholder="Tag1, Tag2, ..."
            />

            <button @click="dropDownOpen = !dropDownOpen" class="transition-colors hover:bg-gray-200 active:bg-gray-300" type="button">
                <ChevronDown />
            </button>
        </div>
        <div class="relative h-0">
            <ul
                ref="dropDown"
                v-show="dropDownOpen"
                data-side="right"
                :data-state="dropDownOpen ? 'open' : 'closed'"
                class="data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 slide-in-from-right-2 absolute top-0 z-10 max-h-32 w-full overflow-y-scroll border-2 bg-white px-1 py-1 shadow-md transition-all"
            >
                <li
                    @click="selectTag(tag)"
                    v-for="tag in filteredTags"
                    :key="isTag(tag) ? tag.name : tag"
                    class="cursor-pointer rounded-md px-2 py-1 hover:bg-gray-200"
                >
                    {{ isTag(tag) ? tag.name : tag }}
                </li>
                <li @click="createTag" class="cursor-pointer rounded-md px-2 py-1 hover:bg-gray-200">
                    + create new <span class="inline-flex items-center rounded-full bg-gray-600 px-2 text-white">{{ inputRef?.value }}</span>
                </li>
            </ul>
        </div>
    </div>
    <div class="flex gap-2">
        <span v-for="tag in selectedTags" :key="isTag(tag) ? tag.name : tag" class="flex items-center gap-1 rounded-full bg-gray-600 px-2 text-white"
            >{{ isTag(tag) ? tag.name : tag }}
            <button type="button" class="cursor-pointer" @click="() => removeSelectedTag(tag)">
                <X :scale="0.7" :size="16" />
            </button>
        </span>
    </div>
</template>
