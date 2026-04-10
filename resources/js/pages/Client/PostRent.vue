<script setup>
import ClientLayout from '@/layouts/ClientLayout.vue';
import axios from 'axios';
import { onMounted, ref } from 'vue';

const posts = ref([]);
const blogs = ref([]);

onMounted(() => {
    fetchPosts();
    fetchBlogs();
});

function fetchPosts() {
    axios.get('/api/posts/rent')
        .then((response) => {
            posts.value = response.data.data ?? [];
        })
        .catch((error) => {
            console.error('Error fetching rent posts:', error);
        });
}

function fetchBlogs() {
    axios.get('/api/blog')
        .then((response) => {
            blogs.value = response.data ?? [];
        })
        .catch((error) => {
            console.error('Error fetching blogs:', error);
        });
}

function formatPrice(value) {
    const amount = Number(value || 0);
    return new Intl.NumberFormat('vi-VN').format(amount) + ' VNĐ/tháng';
}

function formatArea(value) {
    return `${Number(value || 0)} m2`;
}

function maskPhone(phone) {
    if (!phone) return 'Gọi ngay';
    const raw = String(phone);
    if (raw.length <= 3) return '***';
    return `${raw.slice(0, -3)}***`;
}

function formatDate(value) {
    if (!value) return '';
    return new Intl.DateTimeFormat('vi-VN', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    }).format(new Date(value));
}
</script>

<template>
    <ClientLayout>
        <div class="min-h-screen bg-gray-50 text-gray-900 antialiased">
            <header class="sticky top-24 z-40 border-b border-gray-200 bg-white py-4 shadow-sm">
                <div class="container mx-auto max-w-6xl px-4 md:px-5">
                    <div class="grid grid-cols-1 items-end gap-4 md:grid-cols-2 lg:grid-cols-4">
                        
                        <div class="relative">
                            <label class="mb-1.5 block text-[10px] font-bold tracking-widest text-gray-400 uppercase">
                                <i class="fa-solid fa-map-location-dot mr-1"></i> Khu vực / Tên đường
                            </label>
                            <div class="relative">
                                <i class="fa-solid fa-magnifying-glass absolute top-1/2 left-3 -translate-y-1/2 text-gray-400 text-xs"></i>
                                <input
                                    type="text"
                                    placeholder="Bình Thạnh, Quận 1..."
                                    class="w-full rounded-lg border border-gray-200 bg-white pl-9 pr-3 py-2.5 text-xs transition outline-none focus:border-[#ff9c22] focus:ring-1 focus:ring-[#ff9c22]/20"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-[10px] font-bold tracking-widest text-gray-400 uppercase">
                                <i class="fa-solid fa-chart-area mr-1"></i> Diện tích (m²)
                            </label>
                            <div class="flex items-center gap-2">
                                <input
                                    type="number"
                                    placeholder="Từ"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-center text-xs transition outline-none focus:border-[#ff9c22]"
                                />
                                <span class="text-gray-300">-</span>
                                <input
                                    type="number"
                                    placeholder="Đến"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-center text-xs transition outline-none focus:border-[#ff9c22]"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-[10px] font-bold tracking-widest text-gray-400 uppercase">
                                <i class="fa-solid fa-tag mr-1"></i> Mức giá
                            </label>
                            <div class="flex items-center gap-2">
                                <input
                                    type="number"
                                    placeholder="Từ"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-center text-xs transition outline-none focus:border-[#ff9c22]"
                                />
                                <span class="text-gray-300">-</span>
                                <input
                                    type="number"
                                    placeholder="Đến"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-center text-xs transition outline-none focus:border-[#ff9c22]"
                                />
                            </div>
                        </div>

                        <button
                            class="flex items-center justify-center gap-2 rounded-lg bg-[#ff9c22] py-2.5 text-xs font-bold tracking-wide text-white uppercase transition duration-300 hover:bg-orange-600 active:scale-95"
                        >
                            <i class="fa-solid fa-filter"></i> Áp dụng lọc
                        </button>
                    </div>
                </div>
            </header>


            <main
                class="container mx-auto min-h-120 max-w-6xl px-4 py-7 md:px-5"
            >
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
                    <div class="lg:col-span-3">
                        <div class="space-y-4">
                            <div
                                class="mb-5 flex items-center justify-between border-b border-gray-200 pb-3"
                            >
                                <h2
                                    class="text-xl leading-none font-extrabold tracking-tight text-gray-900 uppercase italic"
                                >
                                    Danh mục
                                    <span class="text-[#ff9c22]">Cho Thuê</span>
                                </h2>
                                <span
                                    class="text-[11px] font-bold tracking-widest text-gray-500 uppercase"
                                    >{{ posts.length }} Kết quả</span
                                >
                            </div>

                            <div
                                v-for="post in posts"
                                :key="post.id"
                                class="group flex cursor-pointer flex-col overflow-hidden rounded-xl border border-gray-200 bg-white transition-all duration-300 hover:border-[#ff9c22]/50 hover:shadow-xl md:flex-row"
                            >
                                <div
                                    class="relative h-52 shrink-0 overflow-hidden md:w-80"
                                >
                                    <img
                                        :src="post.img"
                                        class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                    />
                                    <span
                                        class="absolute top-3 left-3 rounded bg-gray-800 px-2 py-1 text-[10px] font-bold text-white uppercase shadow-md"
                                    >
                                        Cho thuê nhanh
                                    </span>
                                </div>

                                <div
                                    class="flex flex-1 flex-col justify-between p-4"
                                >
                                    <div>
                                        <h3
                                            class="line-clamp-1 text-base font-bold text-gray-800 uppercase transition duration-300 group-hover:text-[#ff9c22]"
                                        >
                                            {{ post.title }}
                                        </h3>
                                        <div
                                            class="mt-2.5 flex items-center gap-4"
                                        >
                                            <span
                                                class="text-xl font-black text-[#ff9c22] italic"
                                                >{{ formatPrice(post.price) }}</span
                                            >
                                            <span
                                                class="rounded-full bg-gray-100 px-3 py-1 text-xs font-bold text-gray-900"
                                                >{{ formatArea(post.area) }}</span
                                            >
                                            <span
                                                class="text-[10px] font-bold tracking-tight text-gray-500 uppercase"
                                            >
                                                <i
                                                    class="fa-solid fa-location-dot mr-1"
                                                />
                                                {{ post.address }}
                                            </span>
                                        </div>
                                        <p
                                            class="mt-3 line-clamp-2 text-xs leading-relaxed text-gray-500"
                                        >
                                            {{ post.description }}
                                        </p>
                                    </div>
                                    <div
                                        class="mt-4 flex items-center justify-between border-t border-gray-100 pt-3"
                                    >
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-200 text-xs font-bold text-gray-700 shadow-sm"
                                            >
                                                QT
                                            </div>
                                            <div>
                                                <p
                                                    class="text-[11px] leading-none font-bold text-gray-800 uppercase"
                                                >
                                                    {{ post.seller_name || 'Chủ tin' }}
                                                </p>
                                                <p
                                                    class="mt-1 text-[10px] text-gray-400"
                                                >
                                                    {{ post.category_name }}
                                                </p>
                                            </div>
                                        </div>
                                        <a
                                            :href="`https://zalo.me/${post.seller_phone || ''}`"
                                            class="z-10 flex items-center gap-2 rounded-lg border border-[#ff9c22] px-4 py-2 text-xs font-bold text-[#ff9c22] uppercase transition duration-300 hover:bg-[#ff9c22] hover:text-white"
                                        >
                                            <i
                                                class="fa-solid fa-phone-volume"
                                            />
                                            {{ maskPhone(post.seller_phone) }}
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="mt-8 flex items-center justify-center gap-2"
                            >
                                <button
                                    class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-400 transition hover:bg-gray-100"
                                >
                                    <i class="fa-solid fa-chevron-left" />
                                </button>
                                <button
                                    class="h-9 w-9 rounded-lg bg-[#ff9c22] text-sm font-bold text-white"
                                >
                                    1
                                </button>
                                <button
                                    class="h-9 w-9 rounded-lg border border-gray-200 text-sm font-bold text-gray-700 transition hover:bg-gray-100"
                                >
                                    2
                                </button>
                                <button
                                    class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-400 transition hover:bg-gray-100"
                                >
                                    <i class="fa-solid fa-chevron-right" />
                                </button>
                            </div>
                        </div>
                    </div>

                    <aside class="lg:col-span-1">
                        <div>
                            <div
                                class="mb-5 flex items-center border-b border-gray-200 pb-3"
                            >
                                <h3
                                    class="m-0 text-lg leading-none font-black tracking-wide text-gray-900 uppercase"
                                >
                                    <span class="text-[#ff9c22]">Tin tức</span>
                                </h3>
                            </div>

                            <article
                                v-for="blog in blogs.slice(0, 3)"
                                :key="blog.id"
                                class="group mb-4 cursor-pointer overflow-hidden rounded-lg border border-gray-200 bg-white transition duration-300 hover:shadow-lg"
                            >
                                <div class="h-28 overflow-hidden bg-gray-200">
                                    <img
                                        :src="blog.image"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-110"
                                    />
                                </div>
                                <div class="p-3">
                                    <p
                                        class="mb-2 text-[10px] font-bold tracking-wider text-[#ff9c22] uppercase"
                                    >
                                        Thị trường
                                    </p>
                                    <h4
                                        class="mb-2 line-clamp-2 text-sm font-bold text-gray-800 transition group-hover:text-[#ff9c22]"
                                    >
                                        {{ blog.title }}
                                    </h4>
                                    <p class="text-[11px] text-gray-500">
                                        {{ formatDate(blog.created_at) }}
                                    </p>
                                </div>
                            </article>

                            <button
                                class="w-full rounded-lg bg-gray-100 py-2.5 text-xs font-bold tracking-wider text-gray-800 uppercase transition duration-300 hover:bg-[#ff9c22] hover:text-white"
                            >
                                Tất cả tin tức
                            </button>
                        </div>
                    </aside>
                </div>
            </main>
        </div>
    </ClientLayout>
</template>

<style scoped>
input[type='number']::-webkit-inner-spin-button,
input[type='number']::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

input[type='number'] {
    appearance: textfield;
    -moz-appearance: textfield;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
