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

        <div class="mb-5 rounded-2xl border border-orange-100 bg-orange-50/80 p-4">
            <p class="text-[11px] font-semibold tracking-[0.18em] text-orange-600 uppercase">
                Kích hoạt tài khoản
            </p>
            <p class="mt-1 text-sm text-zinc-700">
                Kiểm tra email để hoàn tất đăng ký và sử dụng đầy đủ tính năng.
            </p>
        </div>

        <div
            v-if="status === 'verification-link-sent'"
            class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700"
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

            <div class="rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-3 text-sm text-zinc-600">
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
