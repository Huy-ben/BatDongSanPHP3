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

        <div class="mb-5 rounded-2xl border border-orange-100 bg-orange-50/80 p-4">
            <p class="text-[11px] text-center font-semibold tracking-[0.18em] text-orange-600 uppercase">
                Hỗ trợ bảo mật
            </p>
            <p class="mt-1 text-sm text-zinc-700">
                Chúng tôi sẽ gửi một liên kết xác nhận đến email của bạn.
            </p>
        </div>

        <div
            v-if="status"
            class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700"
        >
            {{ status }}
        </div>

        <div class="space-y-6">
            <Form v-bind="email.form()" v-slot="{ errors, processing }">
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

                <div class="my-6 flex items-center justify-start">
                    <Button
                        class="h-11 w-full rounded-xl bg-[#FF9C22] text-white shadow-sm hover:bg-[#f28f13]"
                        :disabled="processing"
                        data-test="email-password-reset-link-button"
                    >
                        <Spinner v-if="processing" />
                        Gửi liên kết đặt lại mật khẩu
                    </Button>
                </div>
            </Form>

            <div class="rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-3 text-center text-sm text-zinc-600">
                <span>Hoặc quay lại </span>
                <TextLink :href="login()" class="font-semibold text-orange-600 hover:text-orange-700">đăng nhập</TextLink>
            </div>
        </div>
    </AuthLayout>
</template>
