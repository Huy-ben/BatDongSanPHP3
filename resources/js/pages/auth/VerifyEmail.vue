<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { logout } from '@/routes';
import { send } from '@/routes/verification';

defineProps<{
    status?: string;
}>();
</script>

<template>
    <AuthLayout
        title="Xác minh email"
        description="Vui lòng xác minh email bằng liên kết đã được gửi đến hộp thư của bạn."
    >
        <Head title="Xác minh email" />

        <div class="relative mb-6 overflow-hidden rounded-3xl bg-gradient-to-br from-orange-500 via-orange-400 to-amber-300 p-6 text-white shadow-lg">
            <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-white/15 blur-2xl"></div>
            <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-orange-50/90">
                Kích hoạt tài khoản
            </p>
            <h2 class="mt-2 text-2xl font-semibold leading-tight">
                Xác minh email để mở khóa toàn bộ tính năng
            </h2>
            <p class="mt-3 max-w-sm text-sm leading-6 text-orange-50">
                Một email xác minh đã được gửi đến hộp thư của bạn. Vui lòng mở email và nhấn liên kết bên trong để hoàn tất.
            </p>
        </div>

        <div
            v-if="status === 'verification-link-sent'"
            class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700"
        >
            Liên kết xác minh mới đã được gửi đến email bạn đã đăng ký.
        </div>

        <Form
            v-bind="send.form()"
            class="space-y-4 text-center"
            v-slot="{ processing }"
        >
            <Button
                :disabled="processing"
                class="h-11 w-full rounded-xl bg-[#FF9C22] text-white shadow-sm hover:bg-[#f28f13]"
            >
                <Spinner v-if="processing" />
                Gửi lại email xác minh
            </Button>

            <div class="rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-3 text-sm text-zinc-600">
                Không phải tài khoản của bạn?
                <TextLink
                    :href="logout()"
                    as="button"
                    class="ml-1 font-semibold text-orange-600 hover:text-orange-700"
                >
                    Đăng xuất
                </TextLink>
            </div>
        </Form>
    </AuthLayout>
</template>
