<script setup>
import { computed, ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import ClientLayout from '@/layouts/ClientLayout.vue';

const plans = {
    
    vip: {
        key: 'vip',
        name: 'VIP',
        duration: '3 tháng',
        price: 179000,
        displayPrice: '179.000đ',
        theme: 'orange',
        features: ['5 bài / ngày', 'Đẩy tin 1 lần/ngày', 'Ưu tiên phản hồi'],
    },
    svip: {
        key: 'svip',
        name: 'Super VIP',
        duration: '3 tháng',
        price: 349000,
        displayPrice: '349.000đ',
        theme: 'slate',
        features: [
            'Đăng bài không giới hạn',
            'Tự động đẩy tin mỗi 6 giờ',
            'Hỗ trợ hotline riêng',
        ],
    },
};

const query = new URLSearchParams(window.location.search);
const initialPlan = query.get('plan')?.toLowerCase();
const selectedPlan = ref(plans[initialPlan] ? initialPlan : 'vip');
const isRenewMode = query.get('renew') === '1';
const isAutoRenewMode = query.get('auto_renew') === '1';
const paymentStatus = query.get('status');
const paymentReason = query.get('reason');

const currentPlan = computed(() => plans[selectedPlan.value]);
const total = computed(() => currentPlan.value.price);
const canPayWithVnpay = computed(() => ['vip', 'svip'].includes(selectedPlan.value));

const paymentForm = useForm({
    plan: selectedPlan.value,
    renew: isRenewMode,
});

const paymentFeedback = computed(() => {
    if (paymentStatus === 'success') {
        return {
            type: 'success',
            text: isRenewMode
                ? 'Thanh toán VNPay thành công. Gói hiện tại đã được gia hạn thêm 3 tháng.'
                : 'Thanh toán VNPay thành công. Gói của bạn đã được kích hoạt.',
        };
    }

    if (paymentStatus === 'failed') {
        const reasonMap = {
            payment: 'Giao dịch chưa thành công hoặc đã bị hủy.',
            signature: 'Xác thực chữ ký VNPay không hợp lệ.',
            amount: 'Số tiền giao dịch không khớp.',
            session: 'Phiên thanh toán đã hết hạn. Vui lòng thử lại.',
            save: 'Thanh toán thành công nhưng chưa kích hoạt được gói. Vui lòng liên hệ hỗ trợ.',
            renew_invalid: 'Không thể gia hạn vì gói hiện tại không hợp lệ hoặc đã hết hạn.',
            config: 'Cấu hình VNPay chưa đầy đủ.',
            plan: 'Gói thanh toán không hợp lệ.',
        };

        return {
            type: 'error',
            text: reasonMap[paymentReason] || 'Thanh toán thất bại. Vui lòng thử lại.',
        };
    }

    return null;
});

function handlePayWithVnpay() {
    if (!canPayWithVnpay.value) {
        return;
    }

    paymentForm.plan = selectedPlan.value;
    paymentForm.renew = isRenewMode;
    paymentForm.post('/payment/vnpay');
}

const formatCurrency = (value) => new Intl.NumberFormat('vi-VN').format(value) + 'đ';
</script>

<template>
    <ClientLayout>
        <div class="min-h-screen bg-gray-50 text-slate-900">
            <section class="border-b border-orange-100 bg-gradient-to-br from-white via-[#FFF8EF] to-[#FFE7C7]">
                <div class="mx-auto max-w-6xl px-4 py-10 sm:px-6">
                    <p class="text-xs font-semibold tracking-[0.2em] text-orange-600 uppercase">Thanh toán gói đăng tin</p>
                    <h1 class="mt-3 text-3xl font-black text-slate-950 sm:text-4xl">Hoàn tất thanh toán trong 2 bước</h1>
                    <p class="mt-3 max-w-3xl text-sm text-slate-600 sm:text-base">
                        Chọn đúng gói phù hợp nhu cầu và thanh toán qua VNPay. Hệ thống sẽ kích hoạt ngay sau khi giao dịch thành công.
                    </p>
                    <p v-if="isRenewMode" class="mt-3 inline-flex rounded-full border border-orange-200 bg-white px-4 py-1 text-xs font-semibold text-orange-700">
                        Chế độ gia hạn: thanh toán sẽ cộng thêm 3 tháng cho gói hiện tại.
                    </p>
                    <p
                        v-if="isAutoRenewMode"
                        class="mt-3 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-medium text-amber-700"
                    >
                        Tài khoản đang có gói còn hạn, hệ thống đã tự chuyển sang chế độ gia hạn để đảm bảo đúng logic.
                    </p>
                </div>
            </section>

            <main class="mx-auto grid max-w-6xl gap-6 px-4 py-8 sm:px-6 lg:grid-cols-[1fr_360px]">
                <section class="space-y-6">
                    <div
                        v-if="paymentFeedback"
                        class="rounded-2xl border p-4 text-sm"
                        :class="
                            paymentFeedback.type === 'success'
                                ? 'border-emerald-200 bg-emerald-50 text-emerald-700'
                                : 'border-rose-200 bg-rose-50 text-rose-700'
                        "
                    >
                        {{ paymentFeedback.text }}
                    </div>

                    <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <div class="flex items-center justify-between gap-4">
                            <h2 class="text-lg font-extrabold text-slate-900">1. Chọn gói thanh toán</h2>
                            <Link href="/package" class="text-sm font-semibold text-orange-600 hover:text-orange-700">Xem bảng giá chi tiết</Link>
                        </div>

                        <div class="mt-4 grid gap-4 md:grid-cols-3">
                            <button
                                v-for="plan in Object.values(plans)"
                                :key="plan.key"
                                type="button"
                                class="rounded-2xl border p-4 text-left transition"
                                :class="[
                                    selectedPlan === plan.key
                                        ? 'border-orange-400 bg-orange-50 shadow-sm'
                                        : 'border-slate-200 bg-white hover:border-orange-200',
                                ]"
                                :disabled="isRenewMode"
                                @click="!isRenewMode && (selectedPlan = plan.key)"
                            >
                                <p class="text-xs font-bold tracking-[0.15em] text-slate-500 uppercase">{{ plan.duration }}</p>
                                <h3 class="mt-1 text-xl font-extrabold text-slate-900">{{ plan.name }}</h3>
                                <p class="mt-2 text-2xl font-black text-orange-600">{{ plan.displayPrice }}</p>
                                <ul class="mt-3 space-y-1 text-xs text-slate-600">
                                    <li v-for="feature in plan.features" :key="feature">• {{ feature }}</li>
                                </ul>
                            </button>
                        </div>
                    </article>

                    <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <h2 class="text-lg font-extrabold text-slate-900">2. Cổng thanh toán</h2>

                        <div class="mt-4 overflow-hidden rounded-2xl border border-[#0A61C8]/25 bg-gradient-to-br from-[#F4F9FF] to-white p-5">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-xs font-bold tracking-[0.2em] text-[#0A61C8] uppercase">Phương thức duy nhất</p>
                                    <h3 class="mt-2 text-2xl font-black text-slate-900">Thanh toán VNPay</h3>
                                    <p class="mt-1 text-sm text-slate-600">Hệ thống sẽ chuyển bạn đến cổng VNPay để hoàn tất giao dịch bảo mật.</p>
                                </div>
                                <span class="rounded-xl border border-[#0A61C8]/25 bg-white px-4 py-2 text-sm font-extrabold text-[#0A61C8] shadow-sm">VNPAY</span>
                            </div>

                          
                        </div>

                        <p v-if="!canPayWithVnpay" class="mt-4 text-sm font-medium text-orange-600">
                            Gói Trial không thanh toán qua VNPay. Vui lòng chọn gói VIP hoặc SVIP.
                        </p>
                    </article>
                </section>

                <aside class="h-fit rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6 lg:sticky lg:top-24">
                    <h2 class="text-lg font-extrabold text-slate-900">Tóm tắt đơn hàng</h2>

                    <div class="mt-4 rounded-2xl border border-orange-100 bg-orange-50 p-4">
                        <p class="text-xs font-semibold tracking-[0.15em] text-orange-600 uppercase">Gói đã chọn</p>
                        <p class="mt-1 text-xl font-extrabold text-slate-900">{{ currentPlan.name }}</p>
                        <p class="text-sm text-slate-600">Chu kỳ: {{ currentPlan.duration }}</p>
                    </div>

                    <div class="mt-5 space-y-3 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-slate-600">Giá gói</span>
                            <span class="font-semibold text-slate-900">
                                {{ currentPlan.price === 0 ? 'Miễn phí' : formatCurrency(currentPlan.price) }}
                            </span>
                        </div>
                        <div class="border-t border-dashed border-slate-200 pt-3">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-900">Tổng thanh toán</span>
                                <span class="text-xl font-black text-orange-600">
                                    {{ total === 0 ? 'Miễn phí' : formatCurrency(total) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <button
                        class="mt-6 w-full rounded-xl px-4 py-3 text-sm font-bold text-white transition"
                        :class="
                            canPayWithVnpay
                                ? 'bg-[#0A61C8] hover:bg-[#084D9D]'
                                : 'cursor-not-allowed bg-slate-300'
                        "
                        :disabled="!canPayWithVnpay || paymentForm.processing"
                        @click="handlePayWithVnpay"
                    >
                        Thanh toán với VNPay
                    </button>
                    <p class="mt-3 text-center text-xs text-slate-500">Bằng việc xác nhận, bạn đồng ý với điều khoản sử dụng và chính sách thanh toán.</p>

                    <div class="mt-5 rounded-2xl bg-slate-50 p-4 text-xs text-slate-600">
                        <p class="font-semibold text-slate-800">Bảo mật giao dịch</p>
                        <p class="mt-1">Thanh toán được mã hóa SSL và kiểm tra trạng thái theo thời gian thực.</p>
                    </div>
                </aside>
            </main>
        </div>
    </ClientLayout>
</template>
