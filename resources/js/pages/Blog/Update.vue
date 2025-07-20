<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useTypedPage } from '@/composables/useTypedPage';
import RichTextEditor from '@/features/blog/components/RichTextEditor.vue';
import TagsDropDown from '@/features/blog/components/TagsDropDown.vue';
import { useTagDropDown } from '@/features/blog/composables/useTagDropDown';
import { handleBannerUpload } from '@/helpers/image';
import { route } from '@/helpers/route';
import AppLayout from '@/layouts/app/AppLayout.vue';
import { Post, Tag } from '@/types/AppTypes';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineOptions({ layout: AppLayout });

interface Props {
    post: Post;
    tags: Tag[];
}

const { post } = useTypedPage<Props>().props.responseData;

const form = useForm({
    title: post.title,
    body: post.body,
    banner: null as File | null,
    _method: 'patch',
});

const { selectedTags, onRemoveTag, onSelectTag, getTagsData } = useTagDropDown(post.tags);

const submit = () => {
    const tagData = getTagsData();
    form.transform((data) => ({
        ...data,
        ...tagData,
    })).post(route('blog.update', { post: post.slug }));
};

function getImageSrc() {
    return form.banner ? URL.createObjectURL(form.banner) : undefined;
}
</script>

<template>
    <Head title="Update Post" />

    <form @submit.prevent="submit" class="mx-auto flex w-2/3 flex-col gap-6">
        <div class="grid gap-2">
            <Label class="text-xl font-bold" for="title">Title</Label>
            <Input id="title" type="text" required autofocus :tabindex="1" v-model="form.title" placeholder="Full name" />
            <InputError :message="form.errors.title" />
        </div>
        <div class="grid gap-2">
            <Label class="text-xl font-bold" for="body">Body</Label>
            <RichTextEditor :model-value="form.body" @change="(html) => (form.body = html)" />
            <InputError :message="form.errors.body" />
        </div>

        <div class="grid gap-2">
            <Label class="text-xl font-bold" for="banner">Banner Image</Label>
            <img
                v-if="post.image_banner_url && !form.banner"
                :src="post.image_banner_url"
                alt="Current Banner"
                class="mt-4 h-auto w-full rounded-md"
            />
            <Input id="banner" type="file" @change="(e: Event) => handleBannerUpload(e, form)" accept="image/*" />
            <InputError :message="form.errors.banner" />
            <img v-if="form.banner" :src="getImageSrc()" alt="Banner Preview" class="mt-4 h-auto w-full rounded-md" />
        </div>

        <TagsDropDown @select-tag="onSelectTag" @remove-tag="onRemoveTag" :selected-tags="selectedTags" />

        <Button type="submit" class="bg-app-blue w-full" tabindex="5" :disabled="form.processing">
            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
            Update Post
        </Button>
    </form>
</template>
