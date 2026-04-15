<script setup>
import ClientLayout from '@/layouts/ClientLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    hasUsedTrial: {
        type: Boolean,
        default: false,
    },
});

const openFaqIndexes = ref([]);
const showTrialModal = ref(false);
const trialForm = useForm({});

const faqs = [
    {
        question: 'Tôi có thể nâng cấp gói bất kỳ lúc nào không?',
        answer: 'Có, bạn có thể nâng cấp gói bất kỳ lúc nào. Phần còn lại của gói hiện tại sẽ được tính theo tỷ lệ và bù trừ vào gói mới.',
    },
    {
        question: 'Tin đăng của tôi có xuất hiện ngay không?',
        answer: 'Tin đăng thường được duyệt và hiển thị trong vòng 15-30 phút sau khi bạn gửi. Gói VIP và SVIP được ưu tiên duyệt nhanh hơn.',
    },
    {
        question: 'Phương thức thanh toán được hỗ trợ?',
        answer: 'Chúng tôi hỗ trợ thanh toán qua chuyển khoản ngân hàng, ví điện tử (MoMo, ZaloPay), thẻ tín dụng/ghi nợ và thanh toán tiền mặt tại đại lý.',
    },
    {
        question: 'Gói Trial có yêu cầu thẻ tín dụng không?',
        answer: 'Không. Gói Trial hoàn toàn miễn phí, không yêu cầu thẻ tín dụng hay bất kỳ thông tin thanh toán nào. Chỉ cần tạo tài khoản là bắt đầu được.',
    },
];

const toggleFaq = (index) => {
    if (openFaqIndexes.value.includes(index)) {
        openFaqIndexes.value = openFaqIndexes.value.filter(
            (item) => item !== index,
        );
        return;
    }

    openFaqIndexes.value = [...openFaqIndexes.value, index];
};

const isFaqOpen = (index) => openFaqIndexes.value.includes(index);

const openTrialModal = () => {
    if (props.hasUsedTrial) {
        return;
    }

    showTrialModal.value = true;
};

const closeTrialModal = () => {
    if (trialForm.processing) {
        return;
    }

    showTrialModal.value = false;
};

const confirmActivateTrial = () => {
    trialForm.post('/payment/trial', {
        preserveScroll: true,
        onSuccess: () => {
            showTrialModal.value = false;
        },
    });
};
</script>

<template>
    <ClientLayout>
        <body class="min-h-screen bg-gray-50 text-slate-900">
            <header class="mx-auto max-w-6xl px-4 pt-12 pb-14 sm:px-6 sm:pt-16">
                <div
                    class="overflow-hidden rounded-3xl bg-gradient-to-br from-brand-500 via-orange-500 to-amber-500 px-6 py-12 text-center text-white shadow-glow sm:px-10"
                >
                    <div
                        class="mx-auto mb-4 inline-flex rounded-full border border-white/35 bg-white/20 px-4 py-1 text-[11px] font-semibold tracking-[0.16em] uppercase"
                    >
                        Đăng tin bất động sản
                    </div>
                    <h1
                        class="text-3xl leading-tight font-extrabold sm:text-5xl"
                    >
                        Chọn gói phù hợp<br class="hidden sm:block" />cho bạn
                    </h1>
                    <p
                        class="mx-auto mt-4 max-w-2xl text-sm text-white/90 sm:text-base"
                    >
                        Tiếp cận hàng nghìn khách hàng tiềm năng mỗi ngày với
                        tin đăng nổi bật, hỗ trợ hình ảnh sắc nét và báo cáo
                        hiệu quả theo thời gian thực.
                    </p>
                    <div
                        class="mt-6 flex flex-wrap justify-center gap-2 text-xs sm:gap-3 sm:text-sm"
                    >
                        <span
                            class="rounded-full border border-white/35 bg-white/15 px-4 py-1.5"
                            >✓ Cập nhật liên tục</span
                        >
                        <span
                            class="rounded-full border border-white/35 bg-white/15 px-4 py-1.5"
                            >✓ 1000+ tin mới/ngày</span
                        >
                        <span
                            class="rounded-full border border-white/35 bg-white/15 px-4 py-1.5"
                            >✓ Hỗ trợ 24/7</span
                        >
                    </div>
                </div>
            </header>
            <main class="mx-auto max-w-6xl space-y-10 px-4 pb-16 sm:px-6">
                <section class="grid gap-6 lg:grid-cols-3">
                    <article
                        class="rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
                    >
                        <div class="border-b border-slate-100 px-6 py-6">
                            <span
                                class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700"
                                >Miễn phí</span
                            >
                            <h3
                                class="mt-3 text-2xl font-extrabold text-slate-900"
                            >
                                Trial
                            </h3>
                            <p class="mt-1 text-xs text-slate-500">
                                1 tháng dùng thử
                            </p>
                            <div class="mt-4 flex items-end gap-1">
                                <span
                                    class="text-4xl font-extrabold text-emerald-700"
                                    >Miễn phí</span
                                >
                                <span
                                    class="pb-1 text-xs text-slate-500"
                                ></span>
                            </div>
                        </div>
                        <div class="space-y-3 px-6 py-5 text-sm">
                            <div
                                class="flex gap-3 border-b border-dashed border-slate-100 pb-3"
                            >
                                <span
                                    class="mt-0.5 grid h-5 w-5 place-content-center rounded-full bg-emerald-100 text-[11px] font-bold text-emerald-700"
                                    >✓</span
                                >
                                <div>
                                    <p class="text-xs text-slate-500">
                                        Bài đăng mỗi ngày
                                    </p>
                                    <p class="font-semibold text-slate-800">
                                        1 bài / ngày
                                    </p>
                                </div>
                            </div>
                            <div
                                class="flex gap-3 border-b border-dashed border-slate-100 pb-3"
                            >
                                <span
                                    class="mt-0.5 grid h-5 w-5 place-content-center rounded-full bg-slate-100 text-[11px] font-bold text-slate-400"
                                    >✕</span
                                >
                                <div>
                                    <p class="text-xs text-slate-500">
                                        Tin nổi bật
                                    </p>
                                    <p class="font-semibold text-slate-400">
                                        Không có
                                    </p>
                                </div>
                            </div>
                            <div
                                class="flex gap-3 border-b border-dashed border-slate-100 pb-3"
                            >
                                <span
                                    class="mt-0.5 grid h-5 w-5 place-content-center rounded-full bg-slate-100 text-[11px] font-bold text-slate-400"
                                    >✕</span
                                >
                                <div>
                                    <p class="text-xs text-slate-500">
                                        Đẩy tin tự động
                                    </p>
                                    <p class="font-semibold text-slate-400">
                                        Không có
                                    </p>
                                </div>
                            </div>
                            <div
                                class="flex gap-3 border-b border-dashed border-slate-100 pb-3"
                            >
                                <span
                                    class="mt-0.5 grid h-5 w-5 place-content-center rounded-full bg-emerald-100 text-[11px] font-bold text-emerald-700"
                                    >✓</span
                                >
                                <div>
                                    <p class="text-xs text-slate-500">
                                        Hiển thị tìm kiếm
                                    </p>
                                    <p class="font-semibold text-slate-800">
                                        Thấp
                                    </p>
                                </div>
                            </div>
                            <div
                                class="flex gap-3 border-b border-dashed border-slate-100 pb-3"
                            >
                                <span
                                    class="mt-0.5 grid h-5 w-5 place-content-center rounded-full bg-slate-100 text-[11px] font-bold text-slate-400"
                                    >✕</span
                                >
                                <div>
                                    <p class="text-xs text-slate-500">
                                        Huy hiệu tài khoản
                                    </p>
                                    <p class="font-semibold text-slate-400">
                                        Không có
                                    </p>
                                </div>
                            </div>
                            <div
                                class="flex gap-3 border-b border-dashed border-slate-100 pb-3"
                            >
                                <span
                                    class="mt-0.5 grid h-5 w-5 place-content-center rounded-full bg-emerald-100 text-[11px] font-bold text-emerald-700"
                                    >✓</span
                                >
                                <div>
                                    <p class="text-xs text-slate-500">
                                        Hỗ trợ khách hàng
                                    </p>
                                    <p class="font-semibold text-slate-800">
                                        Chat tiêu chuẩn
                                    </p>
                                </div>
                            </div>
                            <div
                                class="flex gap-3 border-b border-dashed border-slate-100 pb-3"
                            >
                                <span
                                    class="mt-0.5 grid h-5 w-5 place-content-center rounded-full bg-emerald-100 text-[11px] font-bold text-emerald-700"
                                    >✓</span
                                >
                                <div>
                                    <p class="text-xs text-slate-500">
                                        Lưu trữ tin đăng
                                    </p>
                                    <p class="font-semibold text-slate-800">
                                        15 ngày
                                    </p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <span
                                    class="mt-0.5 grid h-5 w-5 place-content-center rounded-full bg-emerald-100 text-[11px] font-bold text-emerald-700"
                                    >✓</span
                                >
                                <div>
                                    <p class="text-xs text-slate-500">
                                        Hình ảnh kèm theo
                                    </p>
                                    <p class="font-semibold text-slate-800">
                                        5 hình
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 pb-6">
                            <button
                                v-if="!props.hasUsedTrial"
                                type="button"
                                class="block w-full rounded-xl border border-slate-300 px-4 py-3 text-center text-sm font-bold text-slate-700 transition hover:bg-slate-50"
                                @click="openTrialModal"
                            >
                                Dùng miễn phí
                            </button>
                            <div
                                v-else
                                class="rounded-xl border border-dashed border-amber-300 bg-amber-50 px-4 py-3 text-center text-sm font-semibold text-amber-700"
                            >
                                Bạn đã sử dụng gói trial rồi
                            </div>
                        </div>
                    </article>

                    <article
                        class="relative rounded-3xl border-2 border-orange-500 bg-white shadow-lg shadow-orange-100 transition hover:-translate-y-1 hover:shadow-xl"
                    >
                        <div
                            class="absolute top-0 left-1/2 -translate-x-1/2 rounded-b-xl bg-orange-500 px-4 py-1 text-[11px] font-bold tracking-wide text-white"
                        >
                            🔥 PHỔ BIẾN NHẤT
                        </div>
                        <div class="border-b border-slate-100 px-6 pt-10 pb-6">
                            <span
                                class="inline-flex rounded-full bg-orange-50 px-3 py-1 text-xs font-bold text-orange-600"
                                >VIP</span
                            >
                            <h3
                                class="mt-3 text-2xl font-extrabold text-slate-900"
                            >
                                VIP
                            </h3>
                            <p class="mt-1 text-xs text-slate-500">3 tháng</p>
                            <div class="mt-4 flex items-end gap-1">
                                <span
                                    class="text-4xl font-extrabold text-orange-500"
                                    >179K</span
                                >
                                <span class="pb-1 text-xs text-slate-500"
                                    >/ tháng</span
                                >
                            </div>
                        </div>
                        <div class="space-y-3 px-6 py-5 text-sm">
                            <div
                                class="flex gap-3 border-b border-dashed border-slate-100 pb-3"
                            >
                                <span
                                    class="mt-0.5 grid h-5 w-5 place-content-center rounded-full bg-orange-50 text-[11px] font-bold text-orange-600"
                                    >★</span
                                >
                                <div>
                                    <p class="text-xs text-slate-500">
                                        Bài đăng mỗi ngày
                                    </p>
                                    <p class="font-semibold text-slate-800">
                                        5 bài / ngày
                                    </p>
                                </div>
                            </div>
                            <div
                                class="flex gap-3 border-b border-dashed border-slate-100 pb-3"
                            >
                                <span
                                    class="mt-0.5 grid h-5 w-5 place-content-center rounded-full bg-orange-50 text-[11px] font-bold text-orange-600"
                                    >★</span
                                >
                                <div>
                                    <p class="text-xs text-slate-500">
                                        Tin nổi bật
                                    </p>
                                    <p class="font-semibold text-slate-800">
                                        Có
                                    </p>
                                </div>
                            </div>
                            <div
                                class="flex gap-3 border-b border-dashed border-slate-100 pb-3"
                            >
                                <span
                                    class="mt-0.5 grid h-5 w-5 place-content-center rounded-full bg-orange-50 text-[11px] font-bold text-orange-600"
                                    >★</span
                                >
                                <div>
                                    <p class="text-xs text-slate-500">
                                        Đẩy tin tự động
                                    </p>
                                    <p class="font-semibold text-slate-800">
                                        1 lần/ngày
                                    </p>
                                </div>
                            </div>
                            <div
                                class="flex gap-3 border-b border-dashed border-slate-100 pb-3"
                            >
                                <span
                                    class="mt-0.5 grid h-5 w-5 place-content-center rounded-full bg-orange-50 text-[11px] font-bold text-orange-600"
                                    >★</span
                                >
                                <div>
                                    <p class="text-xs text-slate-500">
                                        Hiển thị tìm kiếm
                                    </p>
                                    <p class="font-semibold text-slate-800">
                                        Cao
                                    </p>
                                </div>
                            </div>
                            <div
                                class="flex gap-3 border-b border-dashed border-slate-100 pb-3"
                            >
                                <span
                                    class="mt-0.5 grid h-5 w-5 place-content-center rounded-full bg-orange-50 text-[11px] font-bold text-orange-600"
                                    >★</span
                                >
                                <div>
                                    <p class="text-xs text-slate-500">
                                        Huy hiệu tài khoản
                                    </p>
                                    <p class="font-semibold text-slate-800">
                                        Huy hiệu VIP
                                    </p>
                                </div>
                            </div>
                            <div
                                class="flex gap-3 border-b border-dashed border-slate-100 pb-3"
                            >
                                <span
                                    class="mt-0.5 grid h-5 w-5 place-content-center rounded-full bg-orange-50 text-[11px] font-bold text-orange-600"
                                    >★</span
                                >
                                <div>
                                    <p class="text-xs text-slate-500">
                                        Hỗ trợ khách hàng
                                    </p>
                                    <p class="font-semibold text-slate-800">
                                        Chat + ưu tiên phản hồi
                                    </p>
                                </div>
                            </div>
                            <div
                                class="flex gap-3 border-b border-dashed border-slate-100 pb-3"
                            >
                                <span
                                    class="mt-0.5 grid h-5 w-5 place-content-center rounded-full bg-orange-50 text-[11px] font-bold text-orange-600"
                                    >★</span
                                >
                                <div>
                                    <p class="text-xs text-slate-500">
                                        Lưu trữ tin đăng
                                    </p>
                                    <p class="font-semibold text-slate-800">
                                        30 ngày
                                    </p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <span
                                    class="mt-0.5 grid h-5 w-5 place-content-center rounded-full bg-orange-50 text-[11px] font-bold text-orange-600"
                                    >★</span
                                >
                                <div>
                                    <p class="text-xs text-slate-500">
                                        Hình ảnh kèm theo
                                    </p>
                                    <p class="font-semibold text-slate-800">
                                        15 hình
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 pb-6">
                            <Link
                                href="/thanh-toan?plan=vip"
                                class="block w-full rounded-xl border border-orange-500 bg-orange-500 px-4 py-3 text-center text-sm font-bold text-white transition hover:bg-orange-600"
                            >
                                Mua gói VIP →
                            </Link>
                        </div>
                    </article>

                    <article
                        class="rounded-3xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
                    >
                        <div class="border-b border-slate-100 px-6 py-6">
                            <span
                                class="inline-flex rounded-full bg-pink-100 px-3 py-1 text-xs font-bold text-pink-800"
                                >SVIP</span
                            >
                            <h3
                                class="mt-3 text-2xl font-extrabold text-slate-900"
                            >
                                Super VIP
                            </h3>
                            <p class="mt-1 text-xs text-slate-500">3 tháng</p>
                            <div class="mt-4 flex items-end gap-1">
                                <span
                                    class="text-4xl font-extrabold text-pink-900"
                                    >349K</span
                                >
                                <span class="pb-1 text-xs text-slate-500"
                                    >/ tháng</span
                                >
                            </div>
                        </div>
                        <div class="space-y-3 px-6 py-5 text-sm">
                            <div
                                class="flex gap-3 border-b border-dashed border-slate-100 pb-3"
                            >
                                <span
                                    class="mt-0.5 grid h-5 w-5 place-content-center rounded-full bg-pink-100 text-[11px] font-bold text-pink-900"
                                    >★</span
                                >
                                <div>
                                    <p class="text-xs text-slate-500">
                                        Bài đăng mỗi ngày
                                    </p>
                                    <p class="font-semibold text-slate-800">
                                        Không giới hạn
                                    </p>
                                </div>
                            </div>
                            <div
                                class="flex gap-3 border-b border-dashed border-slate-100 pb-3"
                            >
                                <span
                                    class="mt-0.5 grid h-5 w-5 place-content-center rounded-full bg-pink-100 text-[11px] font-bold text-pink-900"
                                    >★</span
                                >
                                <div>
                                    <p class="text-xs text-slate-500">
                                        Tin nổi bật
                                    </p>
                                    <p class="font-semibold text-slate-800">
                                        Ưu tiên cao nhất
                                    </p>
                                </div>
                            </div>
                            <div
                                class="flex gap-3 border-b border-dashed border-slate-100 pb-3"
                            >
                                <span
                                    class="mt-0.5 grid h-5 w-5 place-content-center rounded-full bg-pink-100 text-[11px] font-bold text-pink-900"
                                    >★</span
                                >
                                <div>
                                    <p class="text-xs text-slate-500">
                                        Đẩy tin tự động
                                    </p>
                                    <p class="font-semibold text-slate-800">
                                        Tự động mỗi 6 giờ
                                    </p>
                                </div>
                            </div>
                            <div
                                class="flex gap-3 border-b border-dashed border-slate-100 pb-3"
                            >
                                <span
                                    class="mt-0.5 grid h-5 w-5 place-content-center rounded-full bg-pink-100 text-[11px] font-bold text-pink-900"
                                    >★</span
                                >
                                <div>
                                    <p class="text-xs text-slate-500">
                                        Hiển thị tìm kiếm
                                    </p>
                                    <p class="font-semibold text-slate-800">
                                        Luôn đứng đầu danh mục
                                    </p>
                                </div>
                            </div>
                            <div
                                class="flex gap-3 border-b border-dashed border-slate-100 pb-3"
                            >
                                <span
                                    class="mt-0.5 grid h-5 w-5 place-content-center rounded-full bg-pink-100 text-[11px] font-bold text-pink-900"
                                    >★</span
                                >
                                <div>
                                    <p class="text-xs text-slate-500">
                                        Huy hiệu tài khoản
                                    </p>
                                    <p class="font-semibold text-slate-800">
                                        Huy hiệu SVIP sang trọng
                                    </p>
                                </div>
                            </div>
                            <div
                                class="flex gap-3 border-b border-dashed border-slate-100 pb-3"
                            >
                                <span
                                    class="mt-0.5 grid h-5 w-5 place-content-center rounded-full bg-pink-100 text-[11px] font-bold text-pink-900"
                                    >★</span
                                >
                                <div>
                                    <p class="text-xs text-slate-500">
                                        Hỗ trợ khách hàng
                                    </p>
                                    <p class="font-semibold text-slate-800">
                                        Trực hotline riêng
                                    </p>
                                </div>
                            </div>
                            <div
                                class="flex gap-3 border-b border-dashed border-slate-100 pb-3"
                            >
                                <span
                                    class="mt-0.5 grid h-5 w-5 place-content-center rounded-full bg-pink-100 text-[11px] font-bold text-pink-900"
                                    >★</span
                                >
                                <div>
                                    <p class="text-xs text-slate-500">
                                        Lưu trữ tin đăng
                                    </p>
                                    <p class="font-semibold text-slate-800">
                                        60 ngày
                                    </p>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <span
                                    class="mt-0.5 grid h-5 w-5 place-content-center rounded-full bg-pink-100 text-[11px] font-bold text-pink-900"
                                    >★</span
                                >
                                <div>
                                    <p class="text-xs text-slate-500">
                                        Hình ảnh kèm theo
                                    </p>
                                    <p class="font-semibold text-slate-800">
                                        30 hình
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="px-6 pb-6">
                            <Link
                                href="/thanh-toan?plan=svip"
                                class="block w-full rounded-xl border border-slate-900 bg-slate-900 px-4 py-3 text-center text-sm font-bold text-white transition hover:bg-slate-700"
                            >
                                Mua gói SVIP →
                            </Link>
                        </div>
                    </article>
                </section>

                <section
                    class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm"
                >
                    <div
                        class="flex flex-wrap items-center gap-2 border-b border-slate-100 px-6 py-5"
                    >
                        <h2 class="text-base font-bold text-slate-900">
                            So sánh chi tiết tất cả gói
                        </h2>
                        <span class="text-xs text-slate-500"
                            >- Xem đầy đủ tính năng từng gói</span
                        >
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead
                                class="bg-slate-50 text-xs tracking-wide text-slate-500 uppercase"
                            >
                                <tr>
                                    <th class="px-4 py-3 text-left">
                                        Tính năng
                                    </th>
                                    <th class="px-4 py-3 text-center">
                                        Trial (1 tháng)
                                    </th>
                                    <th
                                        class="px-4 py-3 text-center text-orange-600"
                                    >
                                        VIP (3 tháng)
                                    </th>
                                    <th
                                        class="px-4 py-3 text-center text-pink-900"
                                    >
                                        SVIP (3 tháng)
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr class="odd:bg-white even:bg-slate-50">
                                    <td
                                        class="px-4 py-3 font-medium text-slate-700"
                                    >
                                        Số bài đăng/ngày
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center font-semibold"
                                    >
                                        1 bài
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center font-semibold text-orange-600"
                                    >
                                        5 bài
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center font-semibold text-pink-900"
                                    >
                                        Không giới hạn
                                    </td>
                                </tr>
                                <tr class="odd:bg-white even:bg-slate-50">
                                    <td
                                        class="px-4 py-3 font-medium text-slate-700"
                                    >
                                        Tin nổi bật
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center text-slate-400"
                                    >
                                        -
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center font-bold text-emerald-600"
                                    >
                                        ✓
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center font-bold text-emerald-600"
                                    >
                                        ✓ Ưu tiên cao nhất
                                    </td>
                                </tr>
                                <tr class="odd:bg-white even:bg-slate-50">
                                    <td
                                        class="px-4 py-3 font-medium text-slate-700"
                                    >
                                        Đẩy tin (Auto-boost)
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center text-slate-400"
                                    >
                                        -
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center font-semibold text-orange-600"
                                    >
                                        1 lần/ngày
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center font-semibold text-pink-900"
                                    >
                                        Tự động mỗi 6 giờ
                                    </td>
                                </tr>
                                <tr class="odd:bg-white even:bg-slate-50">
                                    <td
                                        class="px-4 py-3 font-medium text-slate-700"
                                    >
                                        Hiển thị ưu tiên tìm kiếm
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center font-semibold"
                                    >
                                        Thấp
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center font-semibold text-orange-600"
                                    >
                                        Cao
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center font-semibold text-pink-900"
                                    >
                                        Luôn đứng đầu
                                    </td>
                                </tr>
                                <tr class="odd:bg-white even:bg-slate-50">
                                    <td
                                        class="px-4 py-3 font-medium text-slate-700"
                                    >
                                        Huy hiệu tài khoản
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center text-slate-400"
                                    >
                                        -
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center font-semibold text-orange-600"
                                    >
                                        Huy hiệu VIP
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center font-semibold text-pink-900"
                                    >
                                        SVIP Sang trọng
                                    </td>
                                </tr>
                                <tr class="odd:bg-white even:bg-slate-50">
                                    <td
                                        class="px-4 py-3 font-medium text-slate-700"
                                    >
                                        Hỗ trợ khách hàng
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center font-semibold"
                                    >
                                        Chat cơ bản
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center font-semibold text-orange-600"
                                    >
                                        Chat + ưu tiên
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center font-semibold text-pink-900"
                                    >
                                        Hotline riêng
                                    </td>
                                </tr>
                                <tr class="odd:bg-white even:bg-slate-50">
                                    <td
                                        class="px-4 py-3 font-medium text-slate-700"
                                    >
                                        Lưu trữ tin đăng
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center font-semibold"
                                    >
                                        15 ngày
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center font-semibold text-orange-600"
                                    >
                                        30 ngày
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center font-semibold text-pink-900"
                                    >
                                        60 ngày
                                    </td>
                                </tr>
                                <tr class="odd:bg-white even:bg-slate-50">
                                    <td
                                        class="px-4 py-3 font-medium text-slate-700"
                                    >
                                        Hình ảnh đính kèm
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center font-semibold"
                                    >
                                        5 hình
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center font-semibold text-orange-600"
                                    >
                                        15 hình
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center font-semibold text-pink-900"
                                    >
                                        30 hình
                                    </td>
                                </tr>
                                <tr class="bg-slate-100">
                                    <td
                                        class="px-4 py-3 text-left font-extrabold text-slate-900"
                                    >
                                        Giá
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center font-bold text-emerald-700"
                                    >
                                        Miễn phí
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center text-base font-extrabold text-orange-600"
                                    >
                                        179.000đ
                                    </td>
                                    <td
                                        class="px-4 py-3 text-center text-base font-extrabold text-pink-900"
                                    >
                                        349.000đ
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>

                <section
                    class="rounded-3xl bg-gradient-to-br from-slate-900 via-slate-800 to-slate-700 px-6 py-10 text-center text-white shadow-xl sm:px-10"
                >
                    <h2 class="text-2xl font-extrabold">
                        Bắt đầu đăng tin bất động sản ngay hôm nay
                    </h2>
                    <p class="mx-auto mt-2 max-w-2xl text-sm text-slate-300">
                        Tiếp cận hàng nghìn người mua, người thuê tiềm năng trên
                        toàn quốc. Không cần cam kết dài hạn.
                    </p>
                    <div class="mt-6 flex flex-wrap justify-center gap-3">
                        <Link
                            href="/thanh-toan?plan=vip"
                            class="rounded-xl bg-orange-500 px-7 py-3 text-sm font-bold text-white transition hover:bg-orange-600"
                        >
                            Mua gói ngay
                        </Link>
                        <button
                            class="rounded-xl border border-white/35 px-7 py-3 text-sm font-semibold text-white transition hover:bg-white/10"
                        >
                            Tư vấn miễn phí
                        </button>
                    </div>
                </section>

                <section class="space-y-3">
                    <h3 class="text-base font-bold text-slate-900">
                        Câu hỏi thường gặp
                    </h3>

                    <article
                        v-for="(faq, index) in faqs"
                        :key="faq.question"
                        class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm"
                    >
                        <button
                            class="flex w-full items-center justify-between text-left text-sm font-semibold text-slate-800"
                            type="button"
                            @click="toggleFaq(index)"
                        >
                            <span>{{ faq.question }}</span>
                            <span
                                class="text-lg text-orange-500 transition"
                                :class="{ 'rotate-180': isFaqOpen(index) }"
                            >
                                ▾
                            </span>
                        </button>
                        <p
                            v-if="isFaqOpen(index)"
                            class="mt-3 text-sm leading-relaxed text-slate-600"
                        >
                            {{ faq.answer }}
                        </p>
                    </article>
                </section>
            </main>

            <div
                v-if="showTrialModal"
                class="fixed inset-0 z-[60] grid place-items-center bg-slate-900/60 p-4"
                @click.self="closeTrialModal"
            >
                <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
                    <p class="text-xs font-semibold tracking-[0.15em] text-emerald-600 uppercase">
                        Xác nhận dùng thử
                    </p>
                    <h3 class="mt-2 text-xl font-extrabold text-slate-900">
                        Bạn chắc chắn muốn chọn gói Trial?
                    </h3>
                    <p class="mt-3 text-sm leading-relaxed text-slate-600">
                        Gói Trial sẽ được kích hoạt ngay sau khi xác nhận. Bạn có thể nâng cấp lên gói VIP hoặc SVIP bất kỳ lúc nào.
                    </p>

                    <div class="mt-6 flex gap-3">
                        <button
                            type="button"
                            class="flex-1 rounded-xl border border-slate-300 px-4 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50"
                            :disabled="trialForm.processing"
                            @click="closeTrialModal"
                        >
                            Hủy
                        </button>
                        <button
                            type="button"
                            class="flex-1 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-bold text-white transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:bg-emerald-300"
                            :disabled="trialForm.processing"
                            @click="confirmActivateTrial"
                        >
                            {{ trialForm.processing ? 'Đang kích hoạt...' : 'Xác nhận dùng thử' }}
                        </button>
                    </div>
                </div>
            </div>
        </body>
    </ClientLayout>
</template>
