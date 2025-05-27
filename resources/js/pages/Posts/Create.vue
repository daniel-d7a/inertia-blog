<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { LoaderCircle } from 'lucide-vue-next';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import TagsDropDown from '@/features/posts/components/TagsDropDown.vue';
import { Tag } from '@/types/AppTypes';
import { ref } from 'vue';

defineOptions({ layout: AppLayout })

const form = useForm({
    title: '',
    body: '',
});

const selectedTags = ref<(Tag | string)[]>([])

function onRemoveTag(tag: Tag | string) {

    const index = selectedTags.value.indexOf(tag)
    if (index !== -1)
        selectedTags.value.splice(index, 1)
}
function onSelectTag(tag: Tag | string) {
    if (!selectedTags.value.includes(tag))
        selectedTags.value.push(tag)
}

const submit = () => {
    const tagsData = selectedTags.value.reduce((acc, curr) => {
        if (typeof curr === 'string') {
            acc.newTags.push(curr)
        } else {
            acc.existingTags.push(curr)
        }
        return acc;
    }, { newTags: [] as string[], existingTags: [] as Tag[] })

    form
        .transform(data => ({
            ...data,
            ...tagsData
        }))
        .post('/posts');
};


</script>

<template>

    <Head title="Create a Post" />

    <form @submit.prevent="submit" class="flex flex-col gap-6 max-w-md w-full mx-auto">
        <div class="grid gap-2">
            <Label for="title">Title</Label>
            <Input id="title" type="text" required autofocus :tabindex="1" v-model="form.title"
                placeholder="Full name" />
            <InputError :message="form.errors.title" />
        </div>
        <div class="grid gap-2">
            <Label for="body">Body</Label>
            <Input id="body" type="text" required autofocus :tabindex="2" v-model="form.body" placeholder="Full name" />
            <InputError :message="form.errors.body" />
        </div>

        <TagsDropDown @select-tag="onSelectTag" @remove-tag="onRemoveTag" :selected-tags="selectedTags" />

        <Button class="w-full" tabindex="5" :disabled="form.processing">
            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
            Create Post
        </Button>
    </form>

</template>