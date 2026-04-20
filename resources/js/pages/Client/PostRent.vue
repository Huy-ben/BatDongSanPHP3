<script setup>
import ClientLayout from '@/layouts/ClientLayout.vue';
import axios from 'axios';
import { computed, onMounted, ref } from 'vue';
import { jam_read_num_forvietnamese } from '@/utils/money';

const posts = ref([]);

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

const blogs = ref([]);
const categories = ref([]);
const pagination = ref({
    current_page: 1,
    last_page: 1,
    per_page: 12,
    total: 0,
});
const currentPage = ref(1);
const currentListingType = 'rent';
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
            .filter((category) => categoryById[category.parent_id]?.category_name === 'Cho thuê')
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
    axios.get('/api/posts/rent', {
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
    return jam_read_num_forvietnamese(value, '/tháng');
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

function getAuthorInitials(name) {
    const normalizedName = String(name || '').trim();

    if (!normalizedName) {
        return 'CT';
    }

    const asciiName = normalizedName
        .normalize('NFD')
        .replace(/\p{Diacritic}/gu, '');

    const words = asciiName
        .split(/\s+/)
        .map((word) => word.replace(/[^A-Za-z]/g, ''))
        .filter(Boolean);

    if (words.length === 0) {
        return 'CT';
    }

    if (words.length === 1) {
        return words[0].slice(0, 2).toUpperCase();
    }

    return `${words[0][0]}${words[words.length - 1][0]}`.toUpperCase();
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

function goToBlogDetail(blogSlug) {
    if (!blogSlug) {
        return;
    }

    window.location.href = `/blog-detail/${blogSlug}`;
}

function goToPostDetail(postSlug) {
    if (!postSlug) {
        return;
    }

    window.location.href = `/post-detail/${postSlug}`;
}
</script>

<template>
    <ClientLayout>
        <div class="min-h-screen bg-gray-50 text-gray-900 antialiased">
            <header class="sticky top-24 z-40 border-b border-gray-200 bg-white py-4 shadow-sm">
                <div class="container mx-auto max-w-6xl px-4 md:px-5">
                    <div class="mb-4 flex flex-wrap gap-2 text-[11px] font-bold tracking-widest uppercase">
                        <button
                            class="rounded-full border px-4 py-2 transition"
                            :class="currentListingType === 'rent' ? 'border-[#ff9c22] bg-[#ff9c22] text-white' : 'border-gray-200 bg-white text-gray-600 hover:border-[#ff9c22]/40 hover:text-[#ff9c22]'"
                            @click="switchListingType('rent')"
                        >
                            Nhà đất cho thuê
                        </button>
                    </div>
                    <div class="grid grid-cols-1 items-end gap-4 md:grid-cols-2 lg:grid-cols-6">
                        <div class="relative">
                            <label class="mb-1.5 block text-[10px] font-bold tracking-widest text-gray-400 uppercase">
                                <i class="fa-solid fa-magnifying-glass mr-1"></i> Từ khóa
                            </label>
                            <div class="relative">
                                <i class="fa-solid fa-magnifying-glass absolute top-1/2 left-3 -translate-y-1/2 text-gray-400 text-xs"></i>
                                <input
                                    v-model="filters.keyword"
                                    type="text"
                                    placeholder="Tìm theo tên, địa điểm..."
                                    class="w-full rounded-lg border border-gray-200 bg-white pl-9 pr-3 py-2.5 text-xs transition outline-none focus:border-[#ff9c22] focus:ring-1 focus:ring-[#ff9c22]/20"
                                    @keyup.enter="applyFilters"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-[10px] font-bold tracking-widest text-gray-400 uppercase">
                                <i class="fa-solid fa-layer-group mr-1"></i> Loại bất động sản
                            </label>
                            <div class="relative">
                                <select
                                    v-model="filters.property_type"
                                    class="w-full appearance-none rounded-lg border border-gray-200 bg-white px-3 py-2.5 pr-8 text-xs transition outline-none focus:border-[#ff9c22]"
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
                            <label class="mb-1.5 block text-[10px] font-bold tracking-widest text-gray-400 uppercase">
                                <i class="fa-solid fa-map-location-dot mr-1"></i> Khu vực / Tên đường
                            </label>
                            <div class="relative">
                                <i class="fa-solid fa-magnifying-glass absolute top-1/2 left-3 -translate-y-1/2 text-gray-400 text-xs"></i>
                                <input
                                    v-model="filters.location"
                                    type="text"
                                    placeholder="Bình Thạnh, Quận 1..."
                                    class="w-full rounded-lg border border-gray-200 bg-white pl-9 pr-3 py-2.5 text-xs transition outline-none focus:border-[#ff9c22] focus:ring-1 focus:ring-[#ff9c22]/20"
                                    @keyup.enter="applyFilters"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-[10px] font-bold tracking-widest text-gray-400 uppercase">
                                <i class="fa-solid fa-chart-area mr-1"></i> Diện tích (m²)
                            </label>
                            <div class="flex items-center gap-2">
                                <input
                                    v-model="filters.area_min"
                                    type="number"
                                    placeholder="Từ"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-center text-xs transition outline-none focus:border-[#ff9c22]"
                                    @keyup.enter="applyFilters"
                                />
                                <span class="text-gray-300">-</span>
                                <input
                                    v-model="filters.area_max"
                                    type="number"
                                    placeholder="Đến"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-center text-xs transition outline-none focus:border-[#ff9c22]"
                                    @keyup.enter="applyFilters"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-[10px] font-bold tracking-widest text-gray-400 uppercase">
                                <i class="fa-solid fa-tag mr-1"></i> Mức giá
                            </label>
                            <div class="flex items-center gap-2">
                                <input
                                    v-model="filters.price_min"
                                    type="number"
                                    placeholder="Từ"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-center text-xs transition outline-none focus:border-[#ff9c22]"
                                    @keyup.enter="applyFilters"
                                />
                                <span class="text-gray-300">-</span>
                                <input
                                    v-model="filters.price_max"
                                    type="number"
                                    placeholder="Đến"
                                    class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2.5 text-center text-xs transition outline-none focus:border-[#ff9c22]"
                                    @keyup.enter="applyFilters"
                                />
                            </div>
                        </div>

                        <button
                            @click="applyFilters"
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
                                        >{{ pagination.total }} Kết quả</span
                                >
                            </div>

                                <div v-if="posts.length === 0" class="rounded-xl border border-dashed border-gray-300 bg-white p-6 text-sm text-gray-500">
                                    Không tìm thấy bất động sản phù hợp với bộ lọc hiện tại.
                                </div>

                                <div v-else class="space-y-6">
                                    <div
                                        v-for="post in posts"
                                        :key="post.id"
                                        class="group flex cursor-pointer flex-col overflow-hidden rounded-xl border border-gray-200 bg-white transition-all duration-300 hover:border-[#ff9c22]/50 hover:shadow-xl md:flex-row"
                                        role="button"
                                        tabindex="0"
                                        @click="goToPostDetail(post.slug)"
                                        @keydown.enter="goToPostDetail(post.slug)"
                                    >
                                <div
                                    class="relative h-52 shrink-0 overflow-hidden md:w-80"
                                >
                                    <img
                                        :src="resolveImageUrl(post.img)"
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
                                                class="text-lg font-bold text-[#ff9c22]"
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
                                           v-html="post.description" class="mt-3 line-clamp-2 text-xs leading-relaxed text-gray-500"
                                        >
                                        </p>
                                    </div>
                                    <div
                                        class="mt-4 flex items-center justify-between border-t border-gray-100 pt-3"
                                    >
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-200 text-xs font-bold text-gray-700 shadow-sm"
                                            >
                                                {{ getAuthorInitials(post.seller_name || 'Chủ tin') }}
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
                                            @click.stop
                                        >
                                            <i
                                                class="fa-solid fa-phone-volume"
                                            />
                                            {{ maskPhone(post.seller_phone) }}
                                        </a>
                                    </div>
                                </div>
                                </div>
                            </div>

                            <div v-if="pagination.last_page > 1" class="mt-8 flex items-center justify-center gap-2">
                                <button
                                    class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-400 transition hover:bg-gray-100"
                                    :disabled="currentPage === 1"
                                    :class="currentPage === 1 ? 'cursor-not-allowed opacity-40' : ''"
                                    @click="goToPage(currentPage - 1)"
                                >
                                    <i class="fa-solid fa-chevron-left" />
                                </button>
                                <button
                                    v-for="page in pageNumbers"
                                    :key="page"
                                    class="h-9 w-9 rounded-lg text-sm font-bold transition"
                                    :class="page === currentPage ? 'bg-[#ff9c22] text-white' : 'border border-gray-200 text-gray-700 hover:bg-gray-100'"
                                    @click="goToPage(page)"
                                >
                                    {{ page }}
                                </button>
                                <button
                                    class="flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 text-gray-400 transition hover:bg-gray-100"
                                    :disabled="currentPage === pagination.last_page"
                                    :class="currentPage === pagination.last_page ? 'cursor-not-allowed opacity-40' : ''"
                                    @click="goToPage(currentPage + 1)"
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
                                    <span class="text-[#ff9c22]">Tin tức</span> nổi bật
                                </h3>
                            </div>

                            <article
                                v-for="blog in blogs.slice(0, 3)"
                                :key="blog.id"
                                class="group mb-4 cursor-pointer overflow-hidden rounded-lg border border-gray-200 bg-white transition duration-300 hover:shadow-lg"
                                @click="goToBlogDetail(blog.slug)"
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
