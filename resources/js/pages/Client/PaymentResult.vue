<script setup>
import { computed, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import ClientLayout from '@/layouts/ClientLayout.vue';

const props = defineProps({
    status: {
        type: String,
        default: 'failed',
    },
    plan: {
        type: String,
        default: '',
    },
    reason: {
        type: String,
        default: '',
    },
    txnRef: {
        type: String,
        default: '',
    },
});

const isSuccess = computed(() => props.status === 'success');

const planLabel = computed(() => {
    const map = {
        vip: 'VIP',
        svip: 'SVIP',
    };

    return map[props.plan] || 'Không xác định';
});

const failureMessage = computed(() => {
    const map = {
        payment: 'Giao dịch chưa thành công hoặc đã bị hủy tại VNPay.',
        signature: 'Xác thực bảo mật không hợp lệ. Vui lòng thử lại.',
        amount: 'Số tiền thanh toán không khớp.',
        session: 'Phiên thanh toán đã hết hạn. Vui lòng thanh toán lại.',
        save: 'Thanh toán thành công nhưng hệ thống chưa kích hoạt được gói. Vui lòng liên hệ hỗ trợ.',
        config: 'Cấu hình thanh toán chưa hoàn chỉnh.',
        plan: 'Gói thanh toán không hợp lệ.',
    };

    return map[props.reason] || 'Đã xảy ra lỗi trong quá trình xử lý thanh toán.';
});

onMounted(() => {
    axios.post('/payment/result/consume').catch(() => {
        // No-op: if consume fails, user can still see the result page.
    });
});
</script>

<template>
    <ClientLayout>
        <div class="min-h-screen bg-gray-50 px-4 py-10 text-slate-900 sm:px-6">
            <div class="mx-auto max-w-3xl">
                <div
                    class="rounded-3xl border bg-white p-6 shadow-sm sm:p-8"
                    :class="isSuccess ? 'border-emerald-200' : 'border-rose-200'"
                >
                    <div
                        class="mx-auto grid h-18 w-18 place-content-center rounded-full text-3xl"
                        :class="isSuccess ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600'"
                    >
                        {{ isSuccess ? '✓' : '!' }}
                    </div>

                    <h1 class="mt-5 text-center text-2xl font-black sm:text-3xl">
                        {{ isSuccess ? 'Thanh toán thành công' : 'Thanh toán chưa thành công' }}
                    </h1>

                    <p class="mx-auto mt-3 max-w-xl text-center text-sm text-slate-600 sm:text-base">
                        <span v-if="isSuccess">
                            Bạn đã kích hoạt gói <strong>{{ planLabel }}</strong>. Hệ thống đã ghi nhận giao dịch và cập nhật quyền đăng tin.
                        </span>
                        <span v-else>
                            {{ failureMessage }}
                        </span>
                    </p>

                    <div class="mt-6 rounded-2xl bg-slate-50 p-4 text-sm">
                        <div class="flex items-center justify-between gap-4 border-b border-slate-200 pb-2">
                            <span class="text-slate-500">Gói</span>
                            <span class="font-semibold text-slate-900">{{ planLabel }}</span>
                        </div>
                        <div class="mt-2 flex items-center justify-between gap-4">
                            <span class="text-slate-500">Mã giao dịch</span>
                            <span class="font-semibold text-slate-900">{{ props.txnRef || 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="mt-7 flex flex-wrap justify-center gap-3">
                        <Link
                            href="/package"
                            class="rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50"
                        >
                            Quay lại bảng gói
                        </Link>
                        <Link
                            v-if="!isSuccess"
                            :href="`/thanh-toan${props.plan ? `?plan=${props.plan}` : ''}`"
                            class="rounded-xl bg-[#0A61C8] px-5 py-3 text-sm font-bold text-white transition hover:bg-[#084D9D]"
                        >
                            Thử thanh toán lại
                        </Link>
                        <Link
                            v-else
                            href="/"
                            class="rounded-xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-emerald-700"
                        >
                            Về trang chủ
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </ClientLayout>
</template>
