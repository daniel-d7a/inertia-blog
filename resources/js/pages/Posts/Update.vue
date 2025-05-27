<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { LoaderCircle } from 'lucide-vue-next';
import { Head, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Post } from '@/types/AppTypes';
import { useTypedPage } from '@/composables/useTypedPage';

defineOptions({ layout: AppLayout })

interface Props {
    post: Post;
}

const { post } = useTypedPage<Props>().props.responseData;

const form = useForm({
    title: post.title,
    body: post.body
});

const postUrl = `/posts/${post.id}`

const submit = () => {
    form.patch(postUrl, {
        
        onFinish: () => router.get(postUrl, {}, { replace: true, fresh: true })
    });
};

</script>

<template>

    <Head title="Update Post" />

    <form @submit.prevent="submit" class="flex flex-col gap-6">
        <div class="grid gap-2">
            <Label for="title">Title</Label>
            <Input id="title" type="text" required autofocus :tabindex="1" v-model="form.title"
                placeholder="Full name" />
            <InputError :message="form.errors.title" />
        </div>
        <div class="grid gap-2">
            <Label for="body">Body</Label>
            <Input id="body" type="text" required autofocus :tabindex="1" v-model="form.body" placeholder="Full name" />
            <InputError :message="form.errors.body" />
        </div>

        <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
            Update Post
        </Button>
    </form>

</template>