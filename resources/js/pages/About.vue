<script setup lang="ts">
import AppLayout from '@/layouts/app/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});
</script>

<template>
    <div class="about-page mx-auto w-2/3">
        <h1 class="mb-4 text-center text-5xl font-bold">About Me</h1>
        <p class="mb-4">
            Welcome to my blog! I'm passionate about sharing my thoughts and experiences on various topics. Here, you'll find articles on technology,
            lifestyle, travel, and more.
        </p>
        <p class="mb-4">
            I believe in the power of storytelling and aim to connect with readers through engaging content. Whether you're here for inspiration or
            just a good read, I hope you find something that resonates with you.
        </p>
        <p>Thank you for visiting my blog! Feel free to reach out if you have any questions or just want to say hi.</p>
    </div>
</template>
