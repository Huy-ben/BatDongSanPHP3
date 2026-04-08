<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { store } from '@/routes/password/confirm';
</script>

<template>
    <AuthLayout
        title="Xác nhận mật khẩu"
        description="Đây là khu vực bảo mật. Vui lòng nhập lại mật khẩu để tiếp tục."
    >
        <Head title="Xác nhận mật khẩu" />

        <div class="mb-5 rounded-2xl border border-orange-100 bg-orange-50/80 p-4">
            <p class="text-[11px] font-semibold tracking-[0.18em] text-orange-600 uppercase">
                Bảo mật nâng cao
            </p>
            <p class="mt-1 text-sm text-zinc-700">
                Bước xác nhận này giúp bảo vệ thông tin quan trọng của bạn.
            </p>
        </div>

        <Form
            v-bind="store.form()"
            reset-on-success
            v-slot="{ errors, processing }"
        >
            <div class="space-y-6">
                <div class="grid gap-2">
                    <Label htmlFor="password" class="text-zinc-800">Mật khẩu</Label>
                    <PasswordInput
                        id="password"
                        name="password"
                        class="h-11 w-full rounded-xl border-zinc-200 bg-white px-4 focus-visible:border-orange-400 focus-visible:ring-orange-200"
                        required
                        autocomplete="current-password"
                        autofocus
                        placeholder="Nhập mật khẩu hiện tại"
                    />

                    <InputError :message="errors.password" />
                </div>

                <div class="flex items-center">
                    <Button
                        class="h-11 w-full rounded-xl bg-[#FF9C22] text-white shadow-sm hover:bg-[#f28f13]"
                        :disabled="processing"
                        data-test="confirm-password-button"
                    >
                        <Spinner v-if="processing" />
                        Xác nhận
                    </Button>
                </div>
            </div>
        </Form>
    </AuthLayout>
</template>
