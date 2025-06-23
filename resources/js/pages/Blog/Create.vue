<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import RichTextEditor from '@/features/blog/components/RichTextEditor.vue';
import TagsDropDown from '@/features/blog/components/TagsDropDown.vue';
import { route } from '@/helpers/route';
import AppLayout from '@/layouts/app/AppLayout.vue';
import { Tag } from '@/types/AppTypes';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';

defineOptions({ layout: AppLayout });

const form = useForm({
    title: '',
    body: '',
});

const selectedTags = ref<(Tag | string)[]>([]);

function onRemoveTag(tag: Tag | string) {
    const index = selectedTags.value.indexOf(tag);
    if (index !== -1) selectedTags.value.splice(index, 1);
}
function onSelectTag(tag: Tag | string) {
    if (!selectedTags.value.includes(tag)) selectedTags.value.push(tag);
}

const submit = () => {
    const tagsData = selectedTags.value.reduce(
        (acc, curr) => {
            if (typeof curr === 'string') {
                acc.newTags.push(curr);
            } else {
                acc.existingTags.push(curr);
            }
            return acc;
        },
        { newTags: [] as string[], existingTags: [] as Tag[] },
    );

    form.transform((data) => ({
        ...data,
        ...tagsData,
    })).post(route('blog.store'));
};
</script>

<template>
    <Head title="Create a Post" />

    <form @submit.prevent="submit" class="mx-auto flex w-2/3 flex-col gap-6">
        <div class="grid gap-2">
            <Label class="text-xl font-bold" for="title">Title</Label>
            <Input id="title" type="text" required autofocus maxlength="120" :tabindex="1" v-model="form.title" placeholder="My awesome post" />
            <InputError :message="form.errors.title" />
        </div>
        <div class="grid gap-2">
            <Label class="text-xl font-bold" for="body">Body</Label>
            <RichTextEditor @change="(html) => (form.body = html)" />
            <InputError :message="form.errors.body" />
        </div>

        <TagsDropDown @select-tag="onSelectTag" @remove-tag="onRemoveTag" :selected-tags="selectedTags" />

        <Button class="bg-app-blue w-full" tabindex="5" :disabled="form.processing">
            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
            Create Post
        </Button>
    </form>
</template>
