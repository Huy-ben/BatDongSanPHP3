<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { update } from '@/routes/password';

const props = defineProps<{
    token: string;
    email: string;
}>();

const inputEmail = ref(props.email);
</script>

<template>
    <AuthLayout
        title="Đặt lại mật khẩu"
        description="Vui lòng nhập mật khẩu mới để tiếp tục"
    >
        <Head title="Đặt lại mật khẩu" />

        <div class="mb-5 rounded-2xl border border-orange-100 bg-orange-50/80 p-4">
            <p class="text-[11px] font-semibold tracking-[0.18em] text-orange-600 uppercase">
                Xác thực tài khoản
            </p>
            <p class="mt-1 text-sm text-zinc-700">
                Đảm bảo mật khẩu đủ mạnh để bảo vệ tài khoản của bạn.
            </p>
        </div>

        <Form
            v-bind="update.form()"
            :transform="(data) => ({ ...data, token, email })"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
        >
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="email" class="text-zinc-800">Email</Label>
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        autocomplete="email"
                        v-model="inputEmail"
                        class="h-11 w-full rounded-xl border-zinc-200 bg-zinc-50 px-4 text-zinc-600"
                        readonly
                    />
                    <InputError :message="errors.email" class="mt-2" />
                </div>

                <div class="grid gap-2">
                    <Label for="password" class="text-zinc-800">Mật khẩu mới</Label>
                    <PasswordInput
                        id="password"
                        name="password"
                        autocomplete="new-password"
                        class="h-11 w-full rounded-xl border-zinc-200 bg-white px-4 focus-visible:border-orange-400 focus-visible:ring-orange-200"
                        autofocus
                        placeholder="Tối thiểu 8 ký tự"
                    />
                    <InputError :message="errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">
                        Xác nhận mật khẩu mới
                    </Label>
                    <PasswordInput
                        id="password_confirmation"
                        name="password_confirmation"
                        autocomplete="new-password"
                        class="h-11 w-full rounded-xl border-zinc-200 bg-white px-4 focus-visible:border-orange-400 focus-visible:ring-orange-200"
                        placeholder="Nhập lại mật khẩu"
                    />
                    <InputError :message="errors.password_confirmation" />
                </div>

                <Button
                    type="submit"
                    class="mt-2 h-11 w-full rounded-xl bg-[#FF9C22] text-white shadow-sm hover:bg-[#f28f13]"
                    :disabled="processing"
                    data-test="reset-password-button"
                >
                    <Spinner v-if="processing" />
                    Cập nhật mật khẩu
                </Button>
            </div>
        </Form>
    </AuthLayout>
</template>
