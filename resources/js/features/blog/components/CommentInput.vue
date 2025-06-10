<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { route } from '@/helpers/route';
import { router, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

interface Props {
    postSlug: string;
}

const { postSlug } = defineProps<Props>();

const form = useForm({
    body: '',
});

function submit() {
    form.post(route('comment.store', { post: postSlug }), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            router.replace({
                url: route('blog.show', { post: postSlug }),
                preserveScroll: true,
            });
        },
    });
}
</script>

<template>
    <h3 class="text-app-blue my-6 text-2xl font-bold">Comments</h3>

    <form @submit.prevent="submit" class="mb-4 flex flex-col gap-2">
        <div class="grid gap-2">
            <textarea
                class="rounded-xl bg-gray-100 p-2"
                id="body"
                required
                rows="6"
                :tabindex="1"
                v-model="form.body"
                placeholder="What do you think..."
            />
            <InputError :message="form.errors.body" />
        </div>

        <Button type="submit" class="bg-app-blue w-24 cursor-pointer justify-self-start" tabindex="5" :disabled="form.processing">
            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
            Submit
        </Button>
    </form>
</template>
