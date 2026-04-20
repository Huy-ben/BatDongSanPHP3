<script setup>
import ClientLayout from '@/layouts/ClientLayout.vue';
import { Link } from '@inertiajs/vue3';
import { jam_read_num_forvietnamese } from '@/utils/money';

const props = defineProps({
    orders: {
        type: Object,
        default: () => ({
            data: [],
            current_page: 1,
            last_page: 1,
            total: 0,
            per_page: 10,
        }),
    },
});

const typeMeta = {
    new: {
        label: 'Mua mới',
        className: 'bg-emerald-100 text-emerald-700',
    },
    renew: {
        label: 'Gia hạn',
        className: 'bg-orange-100 text-orange-700',
    },
};

const formatAmount = (amount) => jam_read_num_forvietnamese(Number(amount || 0));

const getTypeLabel = (type) => typeMeta[type]?.label || 'Khác';

const getTypeClass = (type) => typeMeta[type]?.className || 'bg-slate-100 text-slate-700';

const pageHref = (page) => `/profile/lich-su-thanh-toan?page=${page}`;
</script>

<template>
    <ClientLayout>
        <div class="min-h-screen bg-slate-100 pb-10 text-slate-900">
            <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
                <div class="overflow-hidden rounded-3xl bg-linear-to-br from-brand-500 via-orange-500 to-amber-500 px-6 py-12 text-center text-white shadow-glow sm:px-10">
                    <p class="text-xs font-semibold tracking-[0.24em] uppercase text-orange-100">Profile</p>
                    <h1 class="mt-3 text-2xl leading-tight font-black sm:text-3xl">Lịch sử thanh toán</h1>
                    <p class="mt-2 max-w-3xl text-sm text-slate-200 text-center mx-auto">
                        Danh sách giao dịch thanh toán gói đăng tin của bạn, bao gồm mua mới và gia hạn.
                    </p>
                </div>

                <section class="mt-6 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2 class="text-base font-black text-slate-900">Tổng giao dịch: {{ props.orders.total }}</h2>
                            <p class="text-sm text-slate-500">Theo dõi toàn bộ thanh toán theo thời gian thực hiện</p>
                        </div>
                        <Link
                            href="/profile"
                            class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold tracking-[0.16em] text-slate-700 uppercase transition hover:bg-slate-50"
                        >
                            Quay lại profile
                        </Link>
                    </div>

                    <div v-if="props.orders.data.length" class="mt-6 overflow-hidden rounded-2xl border border-slate-200">
                        <div class="hidden grid-cols-12 gap-3 bg-slate-50 px-4 py-3 text-xs font-bold tracking-wide text-slate-500 uppercase md:grid">
                            <div class="col-span-1">#</div>
                            <div class="col-span-2">Loại</div>
                            <div class="col-span-2">Gói</div>
                            <div class="col-span-3">Mã giao dịch</div>
                            <div class="col-span-2 text-right">Số tiền</div>
                            <div class="col-span-2 text-right">Thời gian</div>
                        </div>

                        <div class="divide-y divide-slate-100">
                            <article
                                v-for="order in props.orders.data"
                                :key="order.id"
                                class="grid grid-cols-1 gap-3 px-4 py-4 md:grid-cols-12 md:items-center"
                            >
                                <p class="text-sm font-semibold text-slate-900 md:col-span-1">#{{ order.id }}</p>

                                <div class="md:col-span-2">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold" :class="getTypeClass(order.type)">
                                        {{ getTypeLabel(order.type) }}
                                    </span>
                                </div>

                                <p class="text-sm font-semibold text-slate-800 md:col-span-2">{{ order.package_name }}</p>

                                <p class="truncate text-sm text-slate-600 md:col-span-3" :title="order.transaction_code || 'N/A'">
                                    {{ order.transaction_code || 'N/A' }}
                                </p>

                                <p class="text-sm font-bold text-orange-600 md:col-span-2 md:text-right">{{ formatAmount(order.amount) }}</p>
                                <p class="text-sm text-slate-600 md:col-span-2 md:text-right">{{ order.created_at || 'N/A' }}</p>
                            </article>
                        </div>
                    </div>

                    <div v-else class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-orange-100 text-orange-600">
                            <i class="fa-solid fa-receipt text-xl"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-bold text-slate-900">Chưa có giao dịch</h3>
                        <p class="mx-auto mt-2 max-w-xl text-sm text-slate-600">
                            Bạn chưa phát sinh thanh toán nào. Khi thanh toán thành công, giao dịch sẽ xuất hiện tại đây.
                        </p>
                    </div>

                    <div v-if="props.orders.last_page > 1" class="mt-6 flex items-center justify-center gap-2">
                        <Link
                            :href="pageHref(props.orders.current_page - 1)"
                            :class="[
                                'rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50',
                                props.orders.current_page === 1 ? 'pointer-events-none opacity-50' : '',
                            ]"
                        >
                            <i class="fa-solid fa-chevron-left"></i>
                        </Link>

                        <div class="flex gap-1">
                            <Link
                                v-for="page in Math.min(5, props.orders.last_page)"
                                :key="page"
                                :href="pageHref(page)"
                                :class="[
                                    'h-9 min-w-9 rounded-lg px-3 text-sm font-semibold transition',
                                    page === props.orders.current_page
                                        ? 'bg-orange-600 text-white'
                                        : 'border border-slate-200 text-slate-700 hover:bg-slate-50',
                                ]"
                            >
                                {{ page }}
                            </Link>
                            <span v-if="props.orders.last_page > 5" class="flex items-center px-2 text-slate-500">...</span>
                        </div>

                        <Link
                            :href="pageHref(props.orders.current_page + 1)"
                            :class="[
                                'rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50',
                                props.orders.current_page === props.orders.last_page ? 'pointer-events-none opacity-50' : '',
                            ]"
                        >
                            <i class="fa-solid fa-chevron-right"></i>
                        </Link>
                    </div>
                </section>
            </div>
        </div>
    </ClientLayout>
</template>
