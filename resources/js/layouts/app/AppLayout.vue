<script lang="ts" setup>
import Banner from '@/components/layout/Banner.vue';
import Footer from '@/components/layout/Footer.vue';
import NavBar from '@/components/layout/NavBar.vue';
</script>

<template>
    <div class="exo-2-normal">
        <Banner />
        <NavBar />
        <main class="m-8">
            <slot />
        </main>
        <Footer />
    </div>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,100..900;1,100..900&display=swap');
</style>
