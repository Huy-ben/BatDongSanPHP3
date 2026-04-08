<script setup lang="ts">
import ClientLayout from '@/layouts/ClientLayout.vue';

const highlights = [
    { label: 'Khách hàng đã đăng ký', value: '12.500+' },
    { label: 'Bất động sản đang hoạt động', value: '3.200+' },
    { label: 'Đối tác chiến lược', value: '150+' },
    { label: 'Tỉnh thành phủ sóng', value: '27' },
];

const coreValues = [
    {
        title: 'Minh bạch',
        description:
            'Mọi thông tin pháp lý, giá bán và lịch sử giao dịch được kiểm chứng rõ ràng trước khi hiển thị.',
        icon: 'fa-shield-halved',
    },
    {
        title: 'Chính xác',
        description:
            'Dữ liệu tin đăng được chuẩn hoá theo vị trí, diện tích, phân khúc để người mua ra quyết định nhanh.',
        icon: 'fa-bullseye',
    },
    {
        title: 'Đồng hành',
        description:
            'Đội ngũ tư vấn hỗ trợ xuyên suốt từ bước tìm kiếm, đàm phán đến hoàn tất thủ tục.',
        icon: 'fa-handshake',
    },
];

const milestones = [
    {
        year: '2021',
        title: 'Khởi tạo nền tảng BTB',
        description:
            'Xây dựng hệ thống đăng tin bất động sản đầu tiên, tập trung thị trường khu vực miền Tây.',
    },
    {
        year: '2023',
        title: 'Mở rộng mạng lưới tư vấn',
        description:
            'Kết nối hơn 80 chuyên viên môi giới, triển khai quy chuẩn duyệt tin đa tầng.',
    },
    {
        year: '2025',
        title: 'Ra mắt hệ sinh thái giao dịch',
        description:
            'Tích hợp công cụ so sánh, định giá và chăm sóc khách hàng đa kênh ngay trên nền tảng.',
    },
];

const teamMembers = [
    {
        name: 'Trần Minh Khang',
        role: 'Giám đốc điều hành',
        avatar: 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=300&auto=format&fit=crop&q=80',
    },
    {
        name: 'Nguyễn Khánh Linh',
        role: 'Trưởng bộ phận tư vấn',
        avatar: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=300&auto=format&fit=crop&q=80',
    },
    {
        name: 'Lê Quốc Đạt',
        role: 'Quản lý phát triển thị trường',
        avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&auto=format&fit=crop&q=80',
    },
];
</script>

<template>
    <ClientLayout>
        <div class="bg-gray-50 text-gray-900">
            <section class="mx-auto bg-gray-50 mb-10 max-w-7xl px-6 pt-6">
                <div
                    class="mb-6 rounded-xl border border-slate-200 bg-gray-50 px-4 py-3 shadow-sm"
                >
                    <nav
                        aria-label="Breadcrumb"
                        class="flex items-center gap-2 text-sm"
                    >
                        <a
                            href="/"
                            class="font-medium text-slate-500 transition hover:text-slate-800"
                            >Trang chủ</a
                        >
                        <span class="text-slate-300">/</span>
                        <span class="font-semibold text-brand"
                            >Về chúng tôi</span
                        >
                    </nav>
                </div>
                <div
                    class="relative overflow-hidden rounded-3xl bg-linear-to-br from-zinc-900 via-zinc-800 to-zinc-900 px-7 py-12 md:px-12"
                >
                
                    <div
                        class="absolute -top-12 -right-12 h-56 w-56 rounded-full bg-orange-500/15"
                    ></div>
                    <div
                        class="absolute right-24 -bottom-12 h-40 w-40 rounded-full bg-amber-300/10"
                    ></div>

                    <div
                        class="relative z-10 grid gap-10 md:grid-cols-12 md:items-center"
                    >
                        <div class="md:col-span-7">
                            <p
                                class="mb-3 text-xs font-semibold tracking-[0.25em] text-orange-300 uppercase"
                            >
                                BTB REAL ESTATE PLATFORM
                            </p>
                            <h1
                                class="text-3xl leading-tight font-extrabold text-white md:text-5xl"
                            >
                                Không chỉ là đăng tin,
                                <span class="text-orange-400"
                                    >chúng tôi xây niềm tin giao dịch.</span
                                >
                            </h1>
                            <p
                                class="mt-5 max-w-2xl text-sm leading-relaxed text-zinc-300 md:text-base"
                            >
                                BTB phát triển nền tảng bất động sản tập trung
                                vào dữ liệu minh bạch, trải nghiệm người dùng và
                                khả năng kết nối đúng nhu cầu giữa người mua,
                                người bán và nhà đầu tư.
                            </p>

                            <div class="mt-8 flex flex-wrap gap-3">
                                <a
                                    href="/post-sell"
                                    class="rounded-xl bg-orange-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-orange-600"
                                >
                                    Khám phá nhà đất bán
                                </a>
                            </div>
                        </div>

                        <div class="md:col-span-5">
                            <div class="grid grid-cols-2 gap-3">
                                <div
                                    v-for="item in highlights"
                                    :key="item.label"
                                    class="rounded-2xl border border-zinc-700/70 bg-zinc-800/75 px-4 py-5"
                                >
                                    <p
                                        class="text-2xl font-extrabold text-orange-400 md:text-3xl"
                                    >
                                        {{ item.value }}
                                    </p>
                                    <p
                                        class="mt-1 text-xs leading-relaxed text-zinc-300"
                                    >
                                        {{ item.label }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mx-auto max-w-7xl px-6 pb-14">
                <div class="mb-8 flex items-end justify-between gap-4">
                    <div>
                        <p
                            class="text-xs font-semibold tracking-[0.2em] text-orange-500 uppercase"
                        >
                            Giá trị cốt lõi
                        </p>
                        <h2
                            class="mt-2 text-2xl font-extrabold text-zinc-900 md:text-3xl"
                        >
                            3 nguyên tắc tạo nên BTB
                        </h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    <article
                        v-for="value in coreValues"
                        :key="value.title"
                        class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
                    >
                        <div
                            class="mb-4 flex h-12 w-12 items-center justify-center rounded-xl bg-orange-100 text-orange-500"
                        >
                            <i :class="`fa-solid ${value.icon}`"></i>
                        </div>
                        <h3 class="text-lg font-bold text-zinc-900">
                            {{ value.title }}
                        </h3>
                        <p class="mt-2 text-sm leading-relaxed text-gray-600">
                            {{ value.description }}
                        </p>
                    </article>
                </div>
            </section>

            <section class="border-y border-gray-100 bg-white py-14">
                <div class="mx-auto max-w-7xl px-6">
                    <div class="grid grid-cols-1 gap-10 lg:grid-cols-12">
                        <div class="lg:col-span-4">
                            <p
                                class="text-xs font-semibold tracking-[0.2em] text-orange-500 uppercase"
                            >
                                Hành trình phát triển
                            </p>
                            <h2
                                class="mt-2 text-2xl font-extrabold text-zinc-900 md:text-3xl"
                            >
                                Tăng trưởng bền vững qua từng giai đoạn
                            </h2>
                            <p
                                class="mt-4 text-sm leading-relaxed text-gray-600"
                            >
                                Chúng tôi ưu tiên chất lượng dữ liệu và tiêu
                                chuẩn dịch vụ trước khi mở rộng, để mỗi khu vực
                                mới đều giữ được trải nghiệm nhất quán cho khách
                                hàng.
                            </p>
                        </div>

                        <div class="space-y-4 lg:col-span-8">
                            <article
                                v-for="item in milestones"
                                :key="item.year"
                                class="rounded-2xl border border-gray-100 bg-gray-50 p-5 md:p-6"
                            >
                                <div class="flex flex-wrap items-center gap-3">
                                    <span
                                        class="rounded-full bg-orange-500 px-3 py-1 text-xs font-bold tracking-wide text-white"
                                        >{{ item.year }}</span
                                    >
                                    <h3
                                        class="text-base font-bold text-zinc-900 md:text-lg"
                                    >
                                        {{ item.title }}
                                    </h3>
                                </div>
                                <p
                                    class="mt-3 text-sm leading-relaxed text-gray-600"
                                >
                                    {{ item.description }}
                                </p>
                            </article>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mx-auto max-w-7xl px-6 py-14">
                <div class="mb-8">
                    <p
                        class="text-xs font-semibold tracking-[0.2em] text-orange-500 uppercase"
                    >
                        Đội ngũ chủ chốt
                    </p>
                    <h2
                        class="mt-2 text-2xl font-extrabold text-zinc-900 md:text-3xl"
                    >
                        Những người đứng sau trải nghiệm BTB
                    </h2>
                </div>

                <div
                    class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3"
                >
                    <article
                        v-for="member in teamMembers"
                        :key="member.name"
                        class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm"
                    >
                        <img
                            :src="member.avatar"
                            :alt="member.name"
                            class="h-64 w-full object-cover"
                        />
                        <div class="p-5">
                            <h3 class="text-base font-bold text-zinc-900">
                                {{ member.name }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ member.role }}
                            </p>
                        </div>
                    </article>
                </div>
            </section>
        </div>
    </ClientLayout>
</template>
