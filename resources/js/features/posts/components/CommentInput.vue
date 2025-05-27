<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { router, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

interface Props { postId: number }

const { postId } = defineProps<Props>();

const form = useForm({
    body: ""
})

function submit() {
    form.post(`/posts/${postId}/comments`, {
        onSuccess: () => {
            form.reset();
            router.get(`/posts/${postId}`, {});
        }
    })
}

</script>

<template>
    <form @submit.prevent="submit" class="flex flex-col gap-2 mb-4">
        <div class="grid gap-2">
            <textarea class="border-2 rounded-xl p-2 border-gray-300" id="body" required :tabindex="1"
                v-model="form.body" placeholder="What do you think..." />
            <InputError :message="form.errors.body" />
        </div>

        <Button type="submit" class="justify-self-start w-24 cursor-pointer" tabindex="5" :disabled="form.processing">
            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
            Submit
        </Button>
    </form>

</template>