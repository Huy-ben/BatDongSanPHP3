<script setup lang="ts">
import { Form, Head, router } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import AuthBase from '@/layouts/AuthLayout.vue';
import { register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
}>();

type GoogleCredentialResponse = {
    credential?: string;
};

type GoogleAccountId = {
    initialize: (options: {
        client_id: string;
        callback: (response: GoogleCredentialResponse) => void;
        auto_select?: boolean;
        ux_mode?: 'popup' | 'redirect';
    }) => void;
    renderButton: (
        element: HTMLElement,
        options: {
            type?: 'standard' | 'icon';
            theme?: 'outline' | 'filled_blue' | 'filled_black';
            size?: 'large' | 'medium' | 'small';
            text?: 'signin_with' | 'signup_with' | 'continue_with' | 'signin';
            shape?: 'rectangular' | 'pill' | 'circle' | 'square';
            locale?: string;
            width?: string;
        }
    ) => void;
};

declare global {
    interface Window {
        google?: {
            accounts?: {
                id?: GoogleAccountId;
            };
        };
    }
}

const googleButtonRef = ref<HTMLElement | null>(null);
const googleError = ref('');
const loginEmail = ref('');
const loginPassword = ref('');

const handleGoogleCredentialResponse = (response: GoogleCredentialResponse): void => {
    const credential = response.credential;

    if (!credential) {
        googleError.value = 'Khong nhan duoc ma xac thuc tu Google. Vui long thu lai.';
        return;
    }

    googleError.value = '';

    router.post(
        '/auth/google',
        { credential },
        {
            preserveScroll: true,
            onError: (errors) => {
                googleError.value = (errors.google as string | undefined) ?? 'Dang nhap Google that bai. Vui long thu lai.';
            },
        }
    );
};

const initGoogleSignIn = (): void => {
    const clientId = import.meta.env.VITE_GOOGLE_CLIENT_ID as string | undefined;
    const googleAccount = window.google?.accounts?.id;

    if (!clientId) {
        googleError.value = 'Chua cau hinh VITE_GOOGLE_CLIENT_ID.';
        return;
    }

    if (!googleAccount || !googleButtonRef.value) {
        return;
    }

    googleAccount.initialize({
        client_id: clientId,
        callback: handleGoogleCredentialResponse,
        auto_select: false,
        ux_mode: 'popup',
    });

    googleButtonRef.value.innerHTML = '';

    googleAccount.renderButton(googleButtonRef.value, {
        theme: 'outline',
        size: 'large',
        shape: 'pill',
        text: 'signin_with',
        locale: 'vi',
        width: '360',
    });
};

onMounted(() => {
    const scriptId = 'google-identity-services-script';
    const existingScript = document.getElementById(scriptId) as HTMLScriptElement | null;

    if (window.google?.accounts?.id) {
        initGoogleSignIn();
        return;
    }

    if (existingScript) {
        existingScript.addEventListener('load', initGoogleSignIn, { once: true });
        return;
    }

    const script = document.createElement('script');
    script.id = scriptId;
    script.src = 'https://accounts.google.com/gsi/client';
    script.async = true;
    script.defer = true;
    script.addEventListener('load', initGoogleSignIn, { once: true });
    document.head.appendChild(script);
});

</script>

<template>
    <AuthBase
        title="Đăng nhập tài khoản"
        description="Tiếp tục hành trình tìm kiếm bất động sản phù hợp với bạn"
    >
        <Head title="Đăng nhập" />


        <div
            v-if="status"
            class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700"
        >
            {{ status }}
        </div>

        <Form
            v-bind="store.form()"
            :reset-on-success="['password']"
            v-slot="{ errors, processing }"
            class="flex flex-col gap-6"
        >
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="email" class="text-zinc-800">Email</Label>
                    <Input
                        id="email"
                        v-model="loginEmail"
                        type="email"
                        name="email"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="email"
                        placeholder="example@email.com"
                        class="h-11 rounded-xl border-zinc-200 bg-white px-4 focus-visible:border-orange-400 focus-visible:ring-orange-200"
                    />
                    <InputError :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <div class="flex items-center justify-between">
                        <Label for="password" class="text-zinc-800">Mật khẩu</Label>
                        <TextLink
                            v-if="canResetPassword"
                            :href="request()"
                            class="text-sm font-semibold text-orange-600 hover:text-orange-700"
                            :tabindex="5"
                        >
                            Quên mật khẩu?
                        </TextLink>
                    </div>
                    <PasswordInput
                        id="password"
                        v-model="loginPassword"
                        name="password"
                        required
                        :tabindex="2"
                        autocomplete="current-password"
                        placeholder="Nhập mật khẩu"
                        class="h-11 rounded-xl border-zinc-200 bg-white px-4 focus-visible:border-orange-400 focus-visible:ring-orange-200"
                    />
                    <InputError :message="errors.password" />
                </div>

                <div class="flex items-center justify-between">
                    <Label for="remember" class="flex items-center space-x-3 text-zinc-700">
                        <Checkbox id="remember" name="remember" :tabindex="3" />
                        <span>Ghi nhớ đăng nhập</span>
                    </Label>
                </div>

                <Button
                    type="submit"
                    class="mt-2 h-11 w-full rounded-xl bg-[#FF9C22] text-white shadow-sm hover:bg-[#f28f13]"
                    :tabindex="4"
                    :disabled="processing"
                    data-test="login-button"
                >
                    <Spinner v-if="processing" />
                    Đăng nhập
                </Button>

                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <span class="w-full border-t border-zinc-200"></span>
                    </div>
                    <div class="relative flex justify-center text-xs uppercase">
                        <span class="bg-white px-3 font-semibold tracking-wide text-zinc-400">Hoac</span>
                    </div>
                </div>

                <div class="space-y-2">
                    <div ref="googleButtonRef" class="flex w-full justify-center"></div>
                    <p v-if="googleError" class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-700">
                        {{ googleError }}
                    </p>
                </div>
            </div>

            <div
                class="rounded-xl border border-zinc-200 bg-zinc-50 px-4 py-3 text-center text-sm text-zinc-600"
                v-if="canRegister"
            >
                Chưa có tài khoản?
                <TextLink :href="register()" :tabindex="5" class="font-semibold text-orange-600 hover:text-orange-700">
                    Đăng ký ngay
                </TextLink>
            </div>
        </Form>
    </AuthBase>
</template>
