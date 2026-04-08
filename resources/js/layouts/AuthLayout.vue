<script setup lang="ts">
import AuthLayout from '@/layouts/auth/AuthSimpleLayout.vue';
import Footer from '@/components/btbcomponents/Footer.vue';
import Header from '@/components/btbcomponents/Header.vue';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <Header />
    <AuthLayout :title="title" :description="description">
        <slot />
    </AuthLayout>
    <Footer />
</template>
