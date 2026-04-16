<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { email } from '@/routes/password';

defineProps<{
    status?: string;
}>();
</script>

<template>
    <AuthLayout
        title="Quên mật khẩu"
        description="Nhập email để nhận liên kết đặt lại mật khẩu"
    >
        <Head title="Quên mật khẩu" />

        <div class="relative mb-6 overflow-hidden rounded-3xl bg-gradient-to-br from-orange-500 via-orange-400 to-amber-300 p-6 text-white shadow-lg">
            <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full bg-white/15 blur-2xl"></div>
            <div class="absolute -bottom-8 -left-8 h-24 w-24 rounded-full bg-white/10 blur-xl"></div>
            <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-orange-50/90">
                Khôi phục tài khoản
            </p>
            <h2 class="mt-2 text-2xl font-semibold leading-tight">
                Lấy lại quyền truy cập chỉ trong vài bước
            </h2>
            <p class="mt-3 max-w-sm text-sm leading-6 text-orange-50">
                Nhập email bạn đã dùng để đăng ký. Chúng tôi sẽ gửi liên kết đặt lại mật khẩu ngay sau khi xác nhận.
            </p>
            <div class="mt-5 grid gap-3 sm:grid-cols-3">
                <div class="rounded-2xl border border-white/20 bg-white/10 p-3 backdrop-blur-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-orange-50/90">Bước 1</p>
                    <p class="mt-1 text-sm text-white">Nhập email</p>
                </div>
                <div class="rounded-2xl border border-white/20 bg-white/10 p-3 backdrop-blur-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-orange-50/90">Bước 2</p>
                    <p class="mt-1 text-sm text-white">Kiểm tra hộp thư</p>
                </div>
                <div class="rounded-2xl border border-white/20 bg-white/10 p-3 backdrop-blur-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-orange-50/90">Bước 3</p>
                    <p class="mt-1 text-sm text-white">Đặt mật khẩu mới</p>
                </div>
            </div>
        </div>

        <div
            v-if="status"
            class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700"
        >
            {{ status }}
        </div>

        <div class="space-y-6">
            <Form v-bind="email.form()" v-slot="{ errors, processing }">
                <div class="rounded-2xl border border-zinc-200 bg-white p-4 shadow-sm">
                    <div class="grid gap-2">
                        <Label for="email" class="text-zinc-800">Email</Label>
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        autocomplete="off"
                        autofocus
                        placeholder="example@email.com"
                        class="h-11 rounded-xl border-zinc-200 bg-white px-4 focus-visible:border-orange-400 focus-visible:ring-orange-200"
                    />
                    <InputError :message="errors.email" />
                    </div>

                    <div class="mt-6 rounded-2xl border border-orange-100 bg-orange-50 px-4 py-3 text-sm text-orange-900">
                        Mật khẩu mới sẽ giúp bạn bảo vệ tài khoản tốt hơn. Hãy dùng mật khẩu mạnh và không chia sẻ với người khác.
                    </div>

                    <div class="mt-6 flex items-center justify-start">
                        <Button
                            class="h-11 w-full rounded-xl bg-[#FF9C22] text-white shadow-sm hover:bg-[#f28f13]"
                            :disabled="processing"
                            data-test="email-password-reset-link-button"
                        >
                            <Spinner v-if="processing" />
                            Gửi liên kết đặt lại mật khẩu
                        </Button>
                    </div>
                </div>
            </Form>

            <div class="rounded-2xl border border-zinc-200 bg-zinc-50 px-4 py-3 text-center text-sm text-zinc-600">
                <span>Hoặc quay lại </span>
                <TextLink :href="login()" class="font-semibold text-orange-600 hover:text-orange-700">đăng nhập</TextLink>
            </div>
        </div>
    </AuthLayout>
</template>
