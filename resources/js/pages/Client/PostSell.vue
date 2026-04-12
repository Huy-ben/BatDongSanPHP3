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
    axios.get('/api/posts/sell')
        .then((response) => {
            posts.value = response.data.data ?? [];
        })
        .catch((error) => {
            console.error('Error fetching sell posts:', error);
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
    return new Intl.NumberFormat('vi-VN').format(amount) + ' VNĐ';
}

function formatArea(value) {
    return `${Number(value || 0)} m²`;
}

function maskPhone(phone) {
    if (!phone) return 'Liên hệ';
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
        <div class="min-h-screen bg-gray-50 text-gray-800 antialiased">
            
            <header class="sticky top-24 z-40 border-b border-gray-200 bg-white py-3">
                <div class="container mx-auto max-w-6xl px-4 md:px-5">
                    <div class="grid grid-cols-1 items-end gap-3 md:grid-cols-2 lg:grid-cols-4">
                        
                        <div class="relative">
                            <label class="mb-1.5 block text-[11px] font-semibold tracking-wide text-gray-500">
                                <i class="fa-solid fa-map-location-dot mr-1"></i> Khu vực / Tên đường
                            </label>
                            <div class="relative">
                                <i class="fa-solid fa-magnifying-glass absolute top-1/2 left-3 -translate-y-1/2 text-gray-400 text-xs"></i>
                                <input
                                    type="text"
                                    placeholder="Bình Thạnh, Quận 1..."
                                    class="w-full rounded-md border border-gray-200 bg-white py-2.5 pr-3 pl-9 text-sm transition outline-none focus:border-gray-400"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-[11px] font-semibold tracking-wide text-gray-500">
                                <i class="fa-solid fa-chart-area mr-1"></i> Diện tích (m²)
                            </label>
                            <div class="flex items-center gap-2">
                                <input
                                    type="number"
                                    placeholder="Từ"
                                    class="w-full rounded-md border border-gray-200 bg-white px-3 py-2.5 text-center text-sm transition outline-none focus:border-gray-400"
                                />
                                <span class="text-gray-300">-</span>
                                <input
                                    type="number"
                                    placeholder="Đến"
                                    class="w-full rounded-md border border-gray-200 bg-white px-3 py-2.5 text-center text-sm transition outline-none focus:border-gray-400"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-[11px] font-semibold tracking-wide text-gray-500">
                                <i class="fa-solid fa-tag mr-1"></i> Mức giá
                            </label>
                            <div class="flex items-center gap-2">
                                <input
                                    type="number"
                                    placeholder="Từ"
                                    class="w-full rounded-md border border-gray-200 bg-white px-3 py-2.5 text-center text-sm transition outline-none focus:border-gray-400"
                                />
                                <span class="text-gray-300">-</span>
                                <input
                                    type="number"
                                    placeholder="Đến"
                                    class="w-full rounded-md border border-gray-200 bg-white px-3 py-2.5 text-center text-sm transition outline-none focus:border-gray-400"
                                />
                            </div>
                        </div>

                        <button
                            class="flex items-center justify-center gap-2 rounded-md bg-[#ff9c22] py-2.5 text-xs font-semibold tracking-wide text-white transition hover:bg-orange-600"
                        >
                            <i class="fa-solid fa-filter"></i> Áp dụng lọc
                        </button>
                    </div>
                </div>
            </header>

            <main class="container mx-auto max-w-6xl px-4 py-8 md:px-5">
                <div class="grid grid-cols-1 gap-8 lg:grid-cols-4">
                    <div class="lg:col-span-3">
                        <div class="mb-5 flex items-center justify-between border-b border-gray-200 pb-3">
                            <h2 class="text-lg font-bold tracking-tight text-gray-900">
                                Danh mục <span class="text-[#ff9c22]">Mua Bán</span>
                            </h2>
                            <span class="rounded-full bg-gray-100 px-3 py-1 text-[11px] font-semibold text-gray-600">
                                {{ posts.length }} Kết quả
                            </span>
                        </div>

                        <div class="space-y-6">
                            <div
                                v-for="post in posts"
                                :key="post.id"
                                class="group flex cursor-pointer flex-col overflow-hidden rounded-xl border border-gray-200 bg-white transition-all duration-200 hover:border-gray-300 hover:shadow-md md:flex-row"
                            >
                                <div class="relative h-48 shrink-0 overflow-hidden bg-gray-100 md:w-72">
                                    <div class="grid h-full grid-cols-2 grid-rows-2 gap-0.5">
                                        <div class="col-span-2 row-span-1 overflow-hidden">
                                            <img :src="post.img" class="h-full w-full object-cover transition duration-700 group-hover:scale-110" />
                                        </div>
                                        <div class="overflow-hidden">
                                            <img :src="post.img" class="h-full w-full object-cover transition duration-700 group-hover:scale-110" />
                                        </div>
                                        <div class="relative overflow-hidden">
                                            <img :src="post.img" class="h-full w-full object-cover opacity-60 transition duration-700 group-hover:scale-110" />
                                            <div class="absolute inset-0 flex items-center justify-center bg-black/40 text-xs font-bold text-white">Ảnh đại diện</div>
                                        </div>
                                    </div>
                                    <span class="absolute top-3 left-3 rounded bg-gray-800 px-2 py-1 text-[10px] font-semibold text-white">Nổi bật</span>
                                </div>

                                <div class="flex flex-1 flex-col justify-between p-5">
                                    <div>
                                        <h3 class="line-clamp-2 text-base font-semibold text-gray-800 transition duration-200 group-hover:text-[#ff9c22]">
                                            {{ post.title }}
                                        </h3>
                                        <div class="mt-3 flex flex-wrap items-center gap-3">
                                            <span class="text-lg font-bold text-gray-900">{{ formatPrice(post.price) }}</span>
                                            <span class="flex items-center gap-1 rounded bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-600">
                                                <i class="fa-solid fa-ruler-combined"></i> {{ formatArea(post.area) }}
                                            </span>
                                            <span class="text-xs font-medium text-gray-500">
                                                <i class="fa-solid fa-location-dot text-[#ff9c22] mr-1"></i> {{ post.address }}
                                            </span>
                                        </div>
                                        <p class="mt-3 line-clamp-2 text-[13px] leading-relaxed text-gray-500">
                                            {{ post.description }}
                                        </p>
                                    </div>
                                    
                                    <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-3">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-200 text-[10px] font-semibold text-gray-600">QT</div>
                                            <div>
                                                <p class="text-[11px] font-semibold leading-none text-gray-800">{{ post.seller_name || 'Người bán' }}</p>
                                                <p class="mt-1 text-[10px] text-gray-400">{{ post.category_name }}</p>
                                            </div>
                                        </div>
                                        <a :href="`https://zalo.me/${post.seller_phone || ''}`" class="flex items-center gap-2 rounded-md border border-[#ff9c22]/40 bg-orange-50 px-3 py-1.5 text-xs font-semibold text-[#ff9c22] transition hover:bg-[#ff9c22] hover:text-white">
                                            <i class="fa-solid fa-phone-volume"></i> {{ maskPhone(post.seller_phone) }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 flex items-center justify-center gap-2">
                            <button class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600">
                                <i class="fa-solid fa-chevron-left"></i>
                            </button>
                            <button class="h-9 w-9 rounded-lg bg-[#ff9c22] text-sm font-semibold text-white">1</button>
                            <button class="h-9 w-9 rounded-lg border border-[#ff9c22]/30 bg-white text-sm font-semibold text-[#ff9c22] transition hover:bg-orange-50">2</button>
                            <button class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600">
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>

                    <aside class="lg:col-span-1">
                        <div class="sticky top-28">
                            <div class="mb-4 flex items-center border-b border-gray-200 pb-3">
                                <h3 class="text-base font-bold tracking-wide text-gray-900">
                                    <span class="text-[#ff9c22]">Tin tức</span> nổi bật
                                </h3>
                            </div>

                            <div class="space-y-4">
                                <article
                                    v-for="blog in blogs.slice(0, 3)"
                                    :key="blog.id"
                                    class="group cursor-pointer overflow-hidden rounded-lg border border-gray-100 bg-white p-2 transition hover:shadow-md"
                                >
                                    <div class="h-22 overflow-hidden rounded-md bg-gray-200">
                                        <img :src="blog.image" class="h-full w-full object-cover transition duration-500 group-hover:scale-110" />
                                    </div>
                                    <div class="mt-2 p-1">
                                        <p class="text-[10px] font-semibold text-[#ff9c22]">Thị trường</p>
                                        <h4 class="mt-1 line-clamp-2 text-[12px] font-semibold text-gray-800 group-hover:text-gray-900">{{ blog.title }}</h4>
                                        <p class="mt-1 text-[10px] text-gray-400">{{ formatDate(blog.created_at) }}</p>
                                    </div>
                                </article>
                            </div>

                            <button class="mt-4 w-full rounded-md bg-[#ff9c22] py-2.5 text-[11px] font-semibold text-white transition hover:bg-orange-600">
                                Xem tất cả tin tức
                            </button>
                        </div>
                    </aside>
                </div>
            </main>
        </div>
    </ClientLayout>
</template>

<style scoped></style>