<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    InputOTP,
    InputOTPGroup,
    InputOTPSlot,
} from '@/components/ui/input-otp';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { store } from '@/routes/two-factor/login';
import type { TwoFactorConfigContent } from '@/types';

const authConfigContent = computed<TwoFactorConfigContent>(() => {
    if (showRecoveryInput.value) {
        return {
            title: 'Mã khôi phục',
            description:
                'Xác nhận quyền truy cập bằng cách nhập một trong các mã khôi phục đã lưu.',
            buttonText: 'dùng mã xác thực',
        };
    }

    return {
        title: 'Mã xác thực',
        description:
            'Nhập mã xác thực được tạo từ ứng dụng bảo mật của bạn.',
        buttonText: 'dùng mã khôi phục',
    };
});

const showRecoveryInput = ref<boolean>(false);

const toggleRecoveryMode = (clearErrors: () => void): void => {
    showRecoveryInput.value = !showRecoveryInput.value;
    clearErrors();
    code.value = '';
};

const code = ref<string>('');
</script>

<template>
    <AuthLayout
        :title="authConfigContent.title"
        :description="authConfigContent.description"
    >
        <Head title="Xác thực hai lớp" />

        <div class="mb-5 rounded-2xl border border-orange-100 bg-orange-50/80 p-4">
            <p class="text-[11px] font-semibold tracking-[0.18em] text-orange-600 uppercase">
                Xác thực 2 lớp
            </p>
            <p class="mt-1 text-sm text-zinc-700">
                Thêm một lớp bảo vệ để đảm bảo an toàn cho tài khoản của bạn.
            </p>
        </div>

        <div class="space-y-6">
            <template v-if="!showRecoveryInput">
                <Form
                    v-bind="store.form()"
                    class="space-y-4"
                    reset-on-error
                    @error="code = ''"
                    #default="{ errors, processing, clearErrors }"
                >
                    <input type="hidden" name="code" :value="code" />
                    <div
                        class="flex flex-col items-center justify-center space-y-3 text-center"
                    >
                        <div class="flex w-full items-center justify-center">
                            <InputOTP
                                id="otp"
                                v-model="code"
                                :maxlength="6"
                                :disabled="processing"
                                autofocus
                            >
                                <InputOTPGroup>
                                    <InputOTPSlot
                                        v-for="index in 6"
                                        :key="index"
                                        :index="index - 1"
                                    />
                                </InputOTPGroup>
                            </InputOTP>
                        </div>
                        <InputError :message="errors.code" />
                    </div>
                    <Button type="submit" class="w-full" :disabled="processing"
                        >Tiếp tục</Button
                    >
                    <div class="rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-3 text-center text-sm text-zinc-600">
                        <span>Hoặc bạn có thể </span>
                        <button
                            type="button"
                            class="font-semibold text-orange-600 underline underline-offset-4 hover:text-orange-700"
                            @click="() => toggleRecoveryMode(clearErrors)"
                        >
                            {{ authConfigContent.buttonText }}
                        </button>
                    </div>
                </Form>
            </template>

            <template v-else>
                <Form
                    v-bind="store.form()"
                    class="space-y-4"
                    reset-on-error
                    #default="{ errors, processing, clearErrors }"
                >
                    <Input
                        name="recovery_code"
                        type="text"
                        placeholder="Nhập mã khôi phục"
                        class="h-11 rounded-xl border-zinc-200 bg-white px-4 focus-visible:border-orange-400 focus-visible:ring-orange-200"
                        :autofocus="showRecoveryInput"
                        required
                    />
                    <InputError :message="errors.recovery_code" />
                    <Button type="submit" class="w-full" :disabled="processing"
                        >Tiếp tục</Button
                    >

                    <div class="rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-3 text-center text-sm text-zinc-600">
                        <span>Hoặc bạn có thể </span>
                        <button
                            type="button"
                            class="font-semibold text-orange-600 underline underline-offset-4 hover:text-orange-700"
                            @click="() => toggleRecoveryMode(clearErrors)"
                        >
                            {{ authConfigContent.buttonText }}
                        </button>
                    </div>
                </Form>
            </template>
        </div>
    </AuthLayout>
</template>
