<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import RichTextEditor from '@/features/blog/components/RichTextEditor.vue';
import TagsDropDown from '@/features/blog/components/TagsDropDown.vue';
import { useTagDropDown } from '@/features/blog/composables/useTagDropDown';
import { handleBannerUpload } from '@/helpers/image';
import { route } from '@/helpers/route';
import AppLayout from '@/layouts/app/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineOptions({ layout: AppLayout });

const form = useForm({
    title: '',
    body: '',
    banner: null as File | null,
});

const { selectedTags, onRemoveTag, onSelectTag, getTagsData } = useTagDropDown();

const submit = () => {
    form.transform((data) => ({
        ...data,
        ...getTagsData(),
    })).post(route('blog.store'));
};

function getImageSrc() {
    return form.banner ? URL.createObjectURL(form.banner) : undefined;
}
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

        <div class="grid gap-2">
            <Label class="text-xl font-bold" for="banner">Banner Image</Label>
            <Input id="banner" type="file" @change="(e: Event) => handleBannerUpload(e, form)" accept="image/*" />
            <InputError :message="form.errors.banner" />
            <img v-if="form.banner" :src="getImageSrc()" alt="Banner Preview" class="mt-4 h-auto w-full rounded-md" />
        </div>

        <TagsDropDown @select-tag="onSelectTag" @remove-tag="onRemoveTag" :selected-tags="selectedTags" />

        <Button class="bg-app-blue w-full" tabindex="5" :disabled="form.processing">
            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
            Create Post
        </Button>
    </form>
</template>
