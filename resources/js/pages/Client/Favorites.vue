<script setup>
import ClientLayout from '@/layouts/ClientLayout.vue';
import axios from 'axios';
import { computed, onMounted, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { jam_read_num_forvietnamese } from '@/utils/money';

const favoritePosts = ref([]);

function resolveImageUrl(imagePath) {
    if (!imagePath) {
        return '';
    }

    const normalizedPath = String(imagePath).trim();

    if (!normalizedPath) {
        return '';
    }

    if (/^(https?:)?\/\//i.test(normalizedPath) || normalizedPath.startsWith('data:')) {
        return normalizedPath;
    }

    if (normalizedPath.startsWith('/storage/')) {
        return normalizedPath;
    }

    if (normalizedPath.startsWith('storage/')) {
        return `/${normalizedPath}`;
    }

    return `/storage/${normalizedPath.replace(/^\/+/, '')}`;
}


const isLoading = ref(false);
const activeListingType = ref('all');
const searchKeyword = ref('');

onMounted(() => {
    fetchFavoritePosts();
});

const filteredPosts = computed(() => {
    const keyword = searchKeyword.value.trim().toLowerCase();

    return favoritePosts.value.filter((post) => {
        const matchType = activeListingType.value === 'all'
            || (activeListingType.value === 'sell' && post.listing_type === 'Bán')
            || (activeListingType.value === 'rent' && post.listing_type === 'Cho thuê');

        if (!matchType) {
            return false;
        }

        if (!keyword) {
            return true;
        }

        return [post.title, post.address, post.category_name, post.seller_name]
            .map((value) => String(value || '').toLowerCase())
            .some((text) => text.includes(keyword));
    });
});

const favoriteCount = computed(() => favoritePosts.value.length);

async function fetchFavoritePosts() {
    isLoading.value = true;

    try {
        const response = await axios.get('/favorites/data');
        favoritePosts.value = Array.isArray(response.data) ? response.data : [];
    } catch (error) {
        console.error('Error fetching favorite posts:', error);
        favoritePosts.value = [];
    } finally {
        isLoading.value = false;
    }
}

async function removeFavorite(postId) {
    try {
        await axios.delete(`/favorites/${postId}`);
        favoritePosts.value = favoritePosts.value.filter((post) => post.id !== postId);
    } catch (error) {
        console.error('Error removing favorite:', error);
    }
}

async function clearAllFavorites() {
    try {
        await axios.delete('/favorites');
        favoritePosts.value = [];
    } catch (error) {
        console.error('Error clearing favorites:', error);
    }
}

function formatPrice(post) {
    if (post.listing_type === 'Cho thuê') {
        return jam_read_num_forvietnamese(post.price, '/tháng');
    }

    return jam_read_num_forvietnamese(post.price);
}

function formatArea(value) {
    return `${Number(value || 0)} m²`;
}

function formatDate(value) {
    if (!value) return '';

    return new Intl.DateTimeFormat('vi-VN', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    }).format(new Date(value));
}

function maskPhone(phone) {
    if (!phone) return 'Liên hệ';

    const raw = String(phone);

    if (raw.length <= 3) {
        return '***';
    }

    return `${raw.slice(0, -3)}***`;
}

function listingBadgeClass(type) {
    if (type === 'Cho thuê') {
        return 'bg-blue-50 text-blue-600 border-blue-200';
    }

    return 'bg-orange-50 text-orange-600 border-orange-200';
}
</script>

<template>
    <ClientLayout>
        <div class="min-h-screen bg-gray-50 text-gray-900 antialiased">
            <header class="sticky top-24 z-40 border-b border-gray-200 bg-white py-4 shadow-sm">
                <div class="container mx-auto max-w-6xl px-4 md:px-5">
                    <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <p class="text-[11px] font-bold tracking-widest text-gray-500 uppercase">Danh sách cá nhân</p>
                            <h1 class="mt-1 text-xl font-extrabold tracking-tight text-gray-900 md:text-2xl">Bất động sản yêu thích</h1>
                        </div>
                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600">
                            {{ favoriteCount }} tin đã lưu
                        </span>
                    </div>

                    <div class="grid grid-cols-1 items-end gap-3 md:grid-cols-12">
                        <div class="md:col-span-6 lg:col-span-7">
                            <label class="mb-1.5 block text-[11px] font-semibold tracking-wide text-gray-500">
                                <i class="fa-solid fa-magnifying-glass mr-1"></i> Tìm trong tin đã lưu
                            </label>
                            <div class="relative">
                                <i class="fa-solid fa-magnifying-glass absolute top-1/2 left-3 -translate-y-1/2 text-xs text-gray-400"></i>
                                <input
                                    v-model="searchKeyword"
                                    type="text"
                                    placeholder="Tên tin, khu vực, người bán..."
                                    class="w-full rounded-md border border-gray-200 bg-white py-2.5 pr-3 pl-9 text-sm transition outline-none focus:border-gray-400"
                                />
                            </div>
                        </div>

                        <div class="md:col-span-4 lg:col-span-3">
                            <label class="mb-1.5 block text-[11px] font-semibold tracking-wide text-gray-500">
                                <i class="fa-solid fa-layer-group mr-1"></i> Loại tin
                            </label>
                            <div class="flex gap-2">
                                <button
                                    type="button"
                                    class="rounded-full border px-3 py-2 text-xs font-semibold transition"
                                    :class="activeListingType === 'all' ? 'border-gray-900 bg-gray-900 text-white' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300'"
                                    @click="activeListingType = 'all'"
                                >
                                    Tất cả
                                </button>
                                <button
                                    type="button"
                                    class="rounded-full border px-3 py-2 text-xs font-semibold transition"
                                    :class="activeListingType === 'sell' ? 'border-[#ff9c22] bg-[#ff9c22] text-white' : 'border-gray-200 bg-white text-gray-600 hover:border-[#ff9c22]/40 hover:text-[#ff9c22]'"
                                    @click="activeListingType = 'sell'"
                                >
                                    Bán
                                </button>
                                <button
                                    type="button"
                                    class="rounded-full border px-3 py-2 text-xs font-semibold transition"
                                    :class="activeListingType === 'rent' ? 'border-blue-500 bg-blue-500 text-white' : 'border-gray-200 bg-white text-gray-600 hover:border-blue-300 hover:text-blue-600'"
                                    @click="activeListingType = 'rent'"
                                >
                                    Cho thuê
                                </button>
                            </div>
                        </div>

                        <div class="md:col-span-2 text-right">
                            <button
                                v-if="favoriteCount > 0"
                                type="button"
                                class="w-full rounded-md border border-red-200 bg-white px-3 py-2.5 text-xs font-semibold text-red-600 transition hover:bg-red-50 md:w-auto"
                                @click="clearAllFavorites"
                            >
                                Xóa tất cả
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <main class="container mx-auto max-w-6xl px-4 py-8 md:px-5">
                <div v-if="isLoading" class="rounded-xl border border-gray-200 bg-white p-6 text-sm text-gray-500">
                    Đang tải danh sách yêu thích...
                </div>

                <div
                    v-else-if="favoriteCount === 0"
                    class="rounded-2xl border border-dashed border-gray-300 bg-white p-8 text-center"
                >
                    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-orange-50 text-[#ff9c22]">
                        <i class="fa-solid fa-heart text-xl"></i>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900">Bạn chưa lưu bất động sản nào</h2>
                    <p class="mx-auto mt-2 max-w-xl text-sm text-gray-500">
                        Hãy nhấn biểu tượng trái tim ở trang chủ hoặc trang danh sách để lưu lại các tin phù hợp.
                    </p>
                    <div class="mt-5 flex flex-col justify-center gap-3 sm:flex-row">
                        <Link
                            href="/post-sell"
                            class="rounded-full bg-[#ff9c22] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-orange-600"
                        >
                            Xem nhà đất bán
                        </Link>
                        <Link
                            href="/post-rent"
                            class="rounded-full border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:border-gray-400"
                        >
                            Xem nhà đất cho thuê
                        </Link>
                    </div>
                </div>

                <div
                    v-else-if="filteredPosts.length === 0"
                    class="rounded-xl border border-dashed border-gray-300 bg-white p-6 text-sm text-gray-500"
                >
                    Không có tin nào khớp với bộ lọc hiện tại.
                </div>

                <div v-else class="space-y-6">
                    <article
                        v-for="post in filteredPosts"
                        :key="post.id"
                        class="group flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white transition-all duration-200 hover:border-gray-300 hover:shadow-md md:flex-row"
                    >
                        <a :href="`/post-detail/${post.id}`" class="relative block h-52 shrink-0 overflow-hidden bg-gray-100 md:w-72">
                            <img
                                :src="resolveImageUrl(post.img)"
                                :alt="post.title"
                                class="h-full w-full object-cover transition duration-700 group-hover:scale-110"
                            />
                            <span
                                class="absolute top-3 left-3 rounded border px-2 py-1 text-[10px] font-semibold"
                                :class="listingBadgeClass(post.listing_type)"
                            >
                                {{ post.listing_type || 'Bất động sản' }}
                            </span>
                        </a>

                        <div class="flex flex-1 flex-col justify-between p-5">
                            <div>
                                <div class="mb-3 flex items-start justify-between gap-3">
                                    <a
                                        :href="`/post-detail/${post.id}`"
                                        class="line-clamp-2 text-base font-bold text-gray-800 uppercase transition duration-300 hover:text-[#ff9c22]"
                                    >
                                        {{ post.title }}
                                    </a>
                                    <button
                                        type="button"
                                        class="rounded-full border border-red-200 p-2 text-red-500 transition hover:bg-red-50"
                                        @click="removeFavorite(post.id)"
                                    >
                                        <i class="fa-solid fa-heart"></i>
                                    </button>
                                </div>
                                <div class="mt-1 flex flex-wrap items-center gap-3">
                                    <span class="text-lg font-bold text-[#ff9c22]">{{ formatPrice(post) }}</span>
                                    <span class="flex items-center gap-1 rounded bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-600">
                                        <i class="fa-solid fa-ruler-combined"></i> {{ formatArea(post.area) }}
                                    </span>
                                    <span class="text-xs font-medium text-gray-500">
                                        <i class="fa-solid fa-location-dot text-[#ff9c22] mr-1"></i> {{ post.address }}
                                    </span>
                                </div>
                                <p v-html="post.description" class="mt-3 line-clamp-2 text-[13px] leading-relaxed text-gray-500">
                                </p>
                            </div>

                            <div class="mt-4 flex flex-wrap items-center justify-between gap-2 border-t border-gray-100 pt-3">
                                <div>
                                    <p class="text-[11px] font-semibold leading-none text-gray-800">{{ post.seller_name || 'Chủ tin' }}</p>
                                    <p class="mt-1 text-[10px] text-gray-400">{{ post.category_name }} • {{ formatDate(post.created_at) }}</p>
                                </div>
                                <a
                                    :href="`https://zalo.me/${post.seller_phone || ''}`"
                                    class="flex items-center gap-2 rounded-md border border-[#ff9c22]/40 bg-orange-50 px-3 py-1.5 text-xs font-semibold text-[#ff9c22] transition hover:bg-[#ff9c22] hover:text-white"
                                >
                                    <i class="fa-solid fa-phone-volume"></i> {{ maskPhone(post.seller_phone) }}
                                </a>
                            </div>
                        </div>
                    </article>
                </div>
            </main>
        </div>
    </ClientLayout>
</template>

<style scoped></style>
