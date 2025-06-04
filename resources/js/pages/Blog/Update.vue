<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { useTypedPage } from '@/composables/useTypedPage';
import AppLayout from '@/layouts/app/AppLayout.vue';
import { Post } from '@/types/AppTypes';
import { Head, router, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineOptions({ layout: AppLayout });

interface Props {
    post: Post;
}

const { post } = useTypedPage<Props>().props.responseData;

const form = useForm({
    title: post.title,
    body: post.body,
});

const postUrl = `/blog/${post.slug}`;

const submit = () => {
    form.patch(postUrl, {
        onFinish: () => router.get(postUrl, {}, { replace: true, fresh: true }),
    });
};
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
            <Input id="body" type="text" required autofocus :tabindex="1" v-model="form.body" placeholder="Full name" />
            <InputError :message="form.errors.body" />
        </div>

        <!-- TODO:add tags dropdown for edit -->

        <Button type="submit" class="bg-app-blue w-full" tabindex="5" :disabled="form.processing">
            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
            Update Post
        </Button>
    </form>
</template>
