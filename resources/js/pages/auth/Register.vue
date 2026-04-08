<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { login } from '@/routes';
import { store } from '@/routes/register';
</script>

<template>
    <AuthBase
        title="Tạo tài khoản mới"
        description="Tham gia BTB để lưu tin bất động sản và quản lý nhu cầu của bạn"
    >
        <Head title="Đăng ký" />

        <div class="mb-5 rounded-2xl border border-orange-100 bg-orange-50/80 p-4">
            <p class="text-[11px] font-semibold tracking-[0.18em] text-orange-600 uppercase">
                Thành viên mới
            </p>
            <p class="mt-1 text-sm text-zinc-700">
                Hoàn tất thông tin bên dưới để bắt đầu đăng tin và theo dõi thị trường.
            </p>
        </div>

        <Form
            v-bind="store.form()"
            :reset-on-success="['password', 'password_confirmation']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="name" class="text-zinc-800">Họ và tên</Label>
                    <Input
                        id="name"
                        type="text"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="name"
                        name="name"
                        placeholder="Nguyen Van A"
                        class="h-11 rounded-xl border-zinc-200 bg-white px-4 focus-visible:border-orange-400 focus-visible:ring-orange-200"
                    />
                    <InputError :message="errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email" class="text-zinc-800">Email</Label>
                    <Input
                        id="email"
                        type="email"
                        required
                        :tabindex="2"
                        autocomplete="email"
                        name="email"
                        placeholder="ban@email.com"
                        class="h-11 rounded-xl border-zinc-200 bg-white px-4 focus-visible:border-orange-400 focus-visible:ring-orange-200"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password" class="text-zinc-800">Mật khẩu</Label>
                    <PasswordInput
                        id="password"
                        required
                        :tabindex="3"
                        autocomplete="new-password"
                        name="password"
                        placeholder="Tối thiểu 8 ký tự"
                        class="h-11 rounded-xl border-zinc-200 bg-white px-4 focus-visible:border-orange-400 focus-visible:ring-orange-200"
                    />
                    <InputError :message="errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation" class="text-zinc-800">Xác nhận mật khẩu</Label>
                    <PasswordInput
                        id="password_confirmation"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        name="password_confirmation"
                        placeholder="Nhập lại mật khẩu"
                        class="h-11 rounded-xl border-zinc-200 bg-white px-4 focus-visible:border-orange-400 focus-visible:ring-orange-200"
                    />
                    <InputError :message="errors.password_confirmation" />
                </div>

                <p class="rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-3 text-xs leading-relaxed text-zinc-500">
                    Bằng việc tạo tài khoản, bạn đồng ý với Điều khoản sử dụng và Chính sách bảo mật của BTB.
                </p>

                <Button
                    type="submit"
                    class="mt-2 h-11 w-full rounded-xl bg-[#FF9C22] text-white shadow-sm hover:bg-[#f28f13]"
                    tabindex="5"
                    :disabled="processing"
                    data-test="register-user-button"
                >
                    <Spinner v-if="processing" />
                    Tạo tài khoản
                </Button>
            </div>

            <div class="rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-3 text-center text-sm text-zinc-600">
                Đã có tài khoản?
                <TextLink
                    :href="login()"
                    class="font-semibold text-orange-600 hover:text-orange-700"
                    :tabindex="6"
                    >Đăng nhập</TextLink
                >
            </div>
        </Form>
    </AuthBase>
</template>
