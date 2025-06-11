<script setup lang="ts">
import { route } from '@/helpers/route';
import { SharedData } from '@/types';
import { Comment, Post } from '@/types/AppTypes';
import { router, usePage } from '@inertiajs/vue3';
import dayjs from 'dayjs';
import { SquarePen, Trash2 } from 'lucide-vue-next';
import { computed, ref, useTemplateRef } from 'vue';

interface Props {
    comment: Comment;
    post: Post;
}

const { comment, post } = defineProps<Props>();
const page = usePage<SharedData>();
const user = computed(() => page.props.auth.user);

const isEditing = ref(false);
const commentTextRef = useTemplateRef<HTMLParagraphElement>('commentText');

// const voteHref = `/posts/{post.id}/comments/${comment.id}/vote`;

const deleteHref = route('comment.delete', { comment: comment.id });

function deleteComment() {
    if (confirm('are you sure?')) {
        router.delete(deleteHref, {
            preserveScroll: true,
            onSuccess: () => router.get(route('blog.show', { post: post.slug }), {}, { preserveScroll: true }),
        });
    }
}

function startEditComment() {
    isEditing.value = true;
}
function updateComment() {
    router.patch(
        route('comment.update', { comment: comment.id }),
        { body: commentTextRef.value!.innerText },
        {
            onSuccess: () => {
                isEditing.value = false;
            },
            preserveScroll: true,
        },
    );
}
function cancelUpdate() {
    isEditing.value = false;
    commentTextRef.value!.innerText = comment.body;
}
</script>

<template>
    <div class="flex items-start gap-4">
        <!-- <PostVotes :vote-href="voteHref" /> -->
        <p class="text-lg font-bold">{{ comment.votes_count }}</p>
        <div class="w-full">
            <div class="mb-2 flex items-center gap-4 text-sm">
                <p class="font-semibold">{{ comment.user.name }}</p>
                <p>{{ dayjs(comment.created_at).fromNow() }}</p>
            </div>
            <div class="grid grid-cols-10">
                <div class="col-span-9">
                    <p
                        ref="commentText"
                        :class="isEditing ? 'border-b-2 border-gray-700 text-gray-900' : 'text-gray-500'"
                        :contenteditable="isEditing"
                    >
                        {{ comment.body }}
                    </p>
                    <div v-if="isEditing" class="mt-2 flex gap-4">
                        <button
                            @click="updateComment"
                            class="cursor-pointer justify-self-end text-gray-500 transition-colors hover:text-gray-700 active:text-gray-900"
                        >
                            confirm
                        </button>
                        <button
                            @click="cancelUpdate"
                            class="cursor-pointer justify-self-end text-gray-500 transition-colors hover:text-gray-700 active:text-gray-900"
                        >
                            cancel
                        </button>
                    </div>
                </div>

                <div class="flex items-center gap-2" v-if="comment.user.id === user.id && !isEditing">
                    <SquarePen
                        @click="startEditComment"
                        :size="20"
                        class="cursor-pointer justify-self-end text-gray-500 transition-colors hover:text-gray-700 active:text-gray-900"
                    />
                    <Trash2
                        @click="deleteComment"
                        :size="20"
                        class="cursor-pointer justify-self-end text-gray-500 transition-colors hover:text-gray-700 active:text-gray-900"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
