<script setup>
import ClientLayout from '@/layouts/ClientLayout.vue';
import axios from 'axios';
import { computed, onMounted, ref } from 'vue';
import { jam_read_num_forvietnamese } from '@/utils/money';

const posts = ref([]);
const blogs = ref([]);
const categories = ref([]);
const pagination = ref({
    current_page: 1,
    last_page: 1,
    per_page: 12,
    total: 0,
});
const currentPage = ref(1);
const currentListingType = 'sell';
const filters = ref({
    keyword: '',
    location: '',
    property_type: '',
    area_min: '',
    area_max: '',
    price_min: '',
    price_max: '',
});

const pageNumbers = computed(() => {
    return Array.from({ length: pagination.value.last_page }, (_, index) => index + 1);
});

const propertyTypeOptions = computed(() => {
    const categoryById = Object.fromEntries(categories.value.map((category) => [category.id, category]));

    return [...new Set(
        categories.value
            .filter((category) => categoryById[category.parent_id]?.category_name === 'Bán')
            .map((category) => category.category_name)
            .filter(Boolean),
    )];
});

onMounted(() => {
    syncFiltersFromUrl();
    fetchCategories();
    fetchPosts();
    fetchBlogs();
});

function fetchCategories() {
    axios.get('/api/category')
        .then((response) => {
            categories.value = response.data ?? [];
        })
        .catch((error) => {
            console.error('Error fetching categories:', error);
        });
}

function syncFiltersFromUrl() {
    const searchParams = new URLSearchParams(window.location.search);

    filters.value.keyword = searchParams.get('keyword') ?? '';
    filters.value.location = searchParams.get('location') ?? '';
    filters.value.property_type = searchParams.get('property_type') ?? '';
    filters.value.area_min = searchParams.get('area_min') ?? '';
    filters.value.area_max = searchParams.get('area_max') ?? '';
    filters.value.price_min = searchParams.get('price_min') ?? '';
    filters.value.price_max = searchParams.get('price_max') ?? '';
    currentPage.value = Math.max(1, Number(searchParams.get('page') ?? 1));
}

function buildRequestParams(page) {
    const requestParams = new URLSearchParams();

    Object.entries(filters.value).forEach(([key, value]) => {
        const normalizedValue = String(value ?? '').trim();

        if (normalizedValue) {
            requestParams.set(key, normalizedValue);
        }
    });

    if (page > 1) {
        requestParams.set('page', String(page));
    }

    return requestParams;
}

function syncBrowserUrl(page) {
    const requestParams = buildRequestParams(page);
    const nextUrl = requestParams.toString()
        ? `${window.location.pathname}?${requestParams.toString()}`
        : window.location.pathname;

    window.history.replaceState({}, '', nextUrl);
}

function fetchPosts(page = currentPage.value) {
    axios.get('/api/posts/sell', {
        params: Object.fromEntries(buildRequestParams(page).entries()),
    })
        .then((response) => {
            posts.value = response.data.data ?? [];
            pagination.value = {
                current_page: response.data.current_page ?? page,
                last_page: response.data.last_page ?? 1,
                per_page: response.data.per_page ?? 12,
                total: response.data.total ?? 0,
            };
            currentPage.value = pagination.value.current_page;
            syncBrowserUrl(currentPage.value);
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
    return jam_read_num_forvietnamese(value);
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

function applyFilters() {
    currentPage.value = 1;
    fetchPosts(1);
}

function switchListingType(type) {
    if (type === currentListingType) {
        return;
    }

    const targetPath = type === 'rent' ? '/post-rent' : '/post-sell';
    const queryString = buildRequestParams(1).toString();

    window.location.href = queryString ? `${targetPath}?${queryString}` : targetPath;
}

function goToPage(page) {
    if (page < 1 || page > pagination.value.last_page || page === currentPage.value) {
        return;
    }

    fetchPosts(page);
}
</script>

<template>
    <ClientLayout>
        <div class="min-h-screen bg-gray-50 text-gray-800 antialiased">
            <header class="sticky top-24 z-40 border-b border-gray-200 bg-white py-3">
                <div class="container mx-auto max-w-6xl px-4 md:px-5">
                    <div class="mb-3 flex flex-wrap gap-2 text-xs font-semibold tracking-wide uppercase">
                        <button
                            class="rounded-full border px-4 py-2 transition"
                            :class="currentListingType === 'sell' ? 'border-[#ff9c22] bg-[#ff9c22] text-white' : 'border-gray-200 bg-white text-gray-600 hover:border-[#ff9c22]/40 hover:text-[#ff9c22]'"
                            @click="switchListingType('sell')"
                        >
                            Nhà đất bán
                        </button>
                        <button
                            class="rounded-full border px-4 py-2 transition"
                            :class="currentListingType === 'rent' ? 'border-[#ff9c22] bg-[#ff9c22] text-white' : 'border-gray-200 bg-white text-gray-600 hover:border-[#ff9c22]/40 hover:text-[#ff9c22]'"
                            @click="switchListingType('rent')"
                        >
                            Nhà đất cho thuê
                        </button>
                    </div>

                    <div class="grid grid-cols-1 items-end gap-3 md:grid-cols-2 lg:grid-cols-6">
                        <div class="relative">
                            <label class="mb-1.5 block text-[11px] font-semibold tracking-wide text-gray-500">
                                <i class="fa-solid fa-magnifying-glass mr-1"></i> Từ khóa
                            </label>
                            <div class="relative">
                                <i class="fa-solid fa-magnifying-glass absolute top-1/2 left-3 -translate-y-1/2 text-xs text-gray-400"></i>
                                <input
                                    v-model="filters.keyword"
                                    type="text"
                                    placeholder="Tìm theo tên, địa điểm..."
                                    class="w-full rounded-md border border-gray-200 bg-white py-2.5 pr-3 pl-9 text-sm transition outline-none focus:border-gray-400"
                                    @keyup.enter="applyFilters"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-[11px] font-semibold tracking-wide text-gray-500">
                                <i class="fa-solid fa-layer-group mr-1"></i> Loại bất động sản
                            </label>
                            <div class="relative">
                                <select
                                    v-model="filters.property_type"
                                    class="w-full appearance-none rounded-md border border-gray-200 bg-white px-3 py-2.5 pr-8 text-sm transition outline-none focus:border-gray-400"
                                >
                                    <option value="">Tất cả loại</option>
                                    <option
                                        v-for="type in propertyTypeOptions"
                                        :key="type"
                                        :value="type"
                                    >
                                        {{ type }}
                                    </option>
                                </select>
                                <i class="fa-solid fa-chevron-down pointer-events-none absolute top-1/2 right-3 -translate-y-1/2 text-xs text-gray-400"></i>
                            </div>
                        </div>

                        <div class="relative">
                            <label class="mb-1.5 block text-[11px] font-semibold tracking-wide text-gray-500">
                                <i class="fa-solid fa-map-location-dot mr-1"></i> Khu vực / Tên đường
                            </label>
                            <div class="relative">
                                <i class="fa-solid fa-magnifying-glass absolute top-1/2 left-3 -translate-y-1/2 text-gray-400 text-xs"></i>
                                <input
                                    v-model="filters.location"
                                    type="text"
                                    placeholder="Bình Thạnh, Quận 1..."
                                    class="w-full rounded-md border border-gray-200 bg-white py-2.5 pr-3 pl-9 text-sm transition outline-none focus:border-gray-400"
                                    @keyup.enter="applyFilters"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-[11px] font-semibold tracking-wide text-gray-500">
                                <i class="fa-solid fa-chart-area mr-1"></i> Diện tích (m²)
                            </label>
                            <div class="flex items-center gap-2">
                                <input
                                    v-model="filters.area_min"
                                    type="number"
                                    placeholder="Từ"
                                    class="w-full rounded-md border border-gray-200 bg-white px-3 py-2.5 text-center text-sm transition outline-none focus:border-gray-400"
                                    @keyup.enter="applyFilters"
                                />
                                <span class="text-gray-300">-</span>
                                <input
                                    v-model="filters.area_max"
                                    type="number"
                                    placeholder="Đến"
                                    class="w-full rounded-md border border-gray-200 bg-white px-3 py-2.5 text-center text-sm transition outline-none focus:border-gray-400"
                                    @keyup.enter="applyFilters"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-[11px] font-semibold tracking-wide text-gray-500">
                                <i class="fa-solid fa-tag mr-1"></i> Mức giá
                            </label>
                            <div class="flex items-center gap-2">
                                <input
                                    v-model="filters.price_min"
                                    type="number"
                                    placeholder="Từ"
                                    class="w-full rounded-md border border-gray-200 bg-white px-3 py-2.5 text-center text-sm transition outline-none focus:border-gray-400"
                                    @keyup.enter="applyFilters"
                                />
                                <span class="text-gray-300">-</span>
                                <input
                                    v-model="filters.price_max"
                                    type="number"
                                    placeholder="Đến"
                                    class="w-full rounded-md border border-gray-200 bg-white px-3 py-2.5 text-center text-sm transition outline-none focus:border-gray-400"
                                    @keyup.enter="applyFilters"
                                />
                            </div>
                        </div>

                        <button
                            @click="applyFilters"
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
                                {{ pagination.total }} Kết quả
                            </span>
                        </div>

                        <div v-if="posts.length === 0" class="rounded-xl border border-dashed border-gray-300 bg-white p-6 text-sm text-gray-500">
                            Không tìm thấy bất động sản phù hợp với bộ lọc hiện tại.
                        </div>

                        <div v-else class="space-y-6">
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

                        <div v-if="pagination.last_page > 1" class="mt-8 flex items-center justify-center gap-2">
                            <button
                                class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
                                :disabled="currentPage === 1"
                                :class="currentPage === 1 ? 'cursor-not-allowed opacity-40' : ''"
                                @click="goToPage(currentPage - 1)"
                            >
                                <i class="fa-solid fa-chevron-left"></i>
                            </button>
                            <button
                                v-for="page in pageNumbers"
                                :key="page"
                                class="h-9 w-9 rounded-lg text-sm font-semibold transition"
                                :class="page === currentPage ? 'bg-[#ff9c22] text-white' : 'border border-[#ff9c22]/30 bg-white text-[#ff9c22] hover:bg-orange-50'"
                                @click="goToPage(page)"
                            >
                                {{ page }}
                            </button>
                            <button
                                class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
                                :disabled="currentPage === pagination.last_page"
                                :class="currentPage === pagination.last_page ? 'cursor-not-allowed opacity-40' : ''"
                                @click="goToPage(currentPage + 1)"
                            >
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
