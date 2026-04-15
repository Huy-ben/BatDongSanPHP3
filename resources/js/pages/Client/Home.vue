<script setup>
import { computed, onMounted, ref, watch } from 'vue';
import axios from 'axios';
import { Link, usePage } from '@inertiajs/vue3';
import ClientLayout from '@/layouts/ClientLayout.vue';
import { getBearerAuthHeaders } from '@/services/config';
import { jam_read_num_forvietnamese } from '@/utils/money';
const posts = ref([]);
const categories = ref([]);
const blogs = ref([]);
const allCategories = ref([]);
const page = usePage();
const isAuthenticated = computed(() => Boolean(page.props.auth?.user));

onMounted(() => {
    fetchHomeData();
    fetchCategories();

    if (isAuthenticated.value) {
        fetchFavoriteIds();
    }
});

const fetchHomeData = () => {
    axios.get('/api/home', {
        headers: getBearerAuthHeaders(),
    })
        .then(response => {
            posts.value = response.data.posts ?? [];
            categories.value = response.data.categories ?? [];
            blogs.value = response.data.blogs ?? [];
            console.log('Fetched home data:', response.data);
        })
        .catch(error => {
            console.error('Error fetching home data:', error);
        });
};

const DEFAULT_PROPERTY_TYPE = 'Tất cả loại';

const fetchCategories = () => {
    axios.get('/api/category')
        .then(response => {
            allCategories.value = response.data ?? [];
        })
        .catch(error => {
            console.error('Error fetching categories:', error);
        });
};

const activeListingType = ref('sell');
const searchKeyword = ref('');
const selectedPropertyType = ref(DEFAULT_PROPERTY_TYPE);
const minPrice = ref('');
const maxPrice = ref('');

const propertyTypes = computed(() => {
    const categoryById = Object.fromEntries(allCategories.value.map((category) => [category.id, category]));
    const parentName = activeListingType.value === 'rent' ? 'Cho thuê' : 'Bán';

    const dynamicTypes = [...new Set(
        allCategories.value
            .filter((category) => categoryById[category.parent_id]?.category_name === parentName)
            .map((category) => category.category_name)
            .filter(Boolean),
    )];

    return [DEFAULT_PROPERTY_TYPE, ...dynamicTypes];
});

const projectFavorites = ref(new Set());
const hotFavorites = ref(new Set());
const sliderRef = ref(null);
const visiblePostsCount = ref(4);
const loadMoreClicks = ref(0);

function syncFavoriteIds(next) {
    projectFavorites.value = new Set(next);
    hotFavorites.value = new Set(next);
}

async function fetchFavoriteIds() {
    try {
        const response = await axios.get('/favorites/ids');
        const ids = Array.isArray(response.data?.ids)
            ? response.data.ids
                .map((id) => Number(id))
                .filter((id) => Number.isInteger(id) && id > 0)
            : [];

        syncFavoriteIds(new Set(ids));
    } catch (error) {
        console.error('Error fetching favorite ids:', error);
        syncFavoriteIds(new Set());
    }
}

const filteredPostsByType = computed(() => {
    if (selectedPropertyType.value === DEFAULT_PROPERTY_TYPE) {
        return posts.value;
    }

    return posts.value.filter((post) => post.category_name === selectedPropertyType.value);
});

const projectPosts = computed(() => filteredPostsByType.value.slice(0, 6));
const visibleHotPosts = computed(() => filteredPostsByType.value.slice(0, visiblePostsCount.value));
const canShowLoadMoreButton = computed(() => filteredPostsByType.value.length > 0);
const loadMoreLabel = computed(() =>
    loadMoreClicks.value === 0
        ? 'XEM THÊM SẢN PHẨM (+4)'
        : 'XEM TẤT CẢ SẢN PHẨM',
);

watch(selectedPropertyType, () => {
    visiblePostsCount.value = 4;
    loadMoreClicks.value = 0;
});

watch([activeListingType, propertyTypes], () => {
    if (!propertyTypes.value.includes(selectedPropertyType.value)) {
        selectedPropertyType.value = DEFAULT_PROPERTY_TYPE;
    }
});

function formatCurrency(value) {
    return jam_read_num_forvietnamese(value);
}

function formatArea(value) {
    return `${Number(value || 0)}m²`;
}

function formatDate(value) {
    if (!value) return '';
    return new Intl.DateTimeFormat('vi-VN', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    }).format(new Date(value));
}

function slide(direction) {
    const slider = sliderRef.value;
    if (!slider) return;
    const card = slider.querySelector('[data-card]');
    const width = card?.offsetWidth ?? 0;
    const gap = 24;
    slider.scrollBy({
        left: direction === 'next' ? width + gap : -(width + gap),
        behavior: 'smooth',
    });
}

async function toggleFavorite(id) {
    if (!isAuthenticated.value) {
        window.location.href = '/login';

        return;
    }

    const next = new Set(projectFavorites.value);
    const isLiked = next.has(id);

    if (isLiked) {
        next.delete(id);
    } else {
        next.add(id);
    }

    syncFavoriteIds(next);

    try {
        if (isLiked) {
            await axios.delete(`/favorites/${id}`);
        } else {
            await axios.post('/favorites', { post_id: id });
        }
    } catch (error) {
        console.error('Error toggling favorite:', error);
        const rollback = new Set(projectFavorites.value);

        if (isLiked) {
            rollback.add(id);
        } else {
            rollback.delete(id);
        }

        syncFavoriteIds(rollback);
    }
}

function toggleProjectFavorite(id) {
    toggleFavorite(id);
}

function toggleHotFavorite(id) {
    toggleFavorite(id);
}

function handleLoadMore() {
    if (loadMoreClicks.value === 0) {
        visiblePostsCount.value = Math.min(visiblePostsCount.value + 4, filteredPostsByType.value.length);
        loadMoreClicks.value += 1;
        return;
    }

    window.location.href = '/post-sell';
}

function submitQuickSearch() {
    const searchParams = new URLSearchParams();

    if (searchKeyword.value.trim()) {
        searchParams.set('keyword', searchKeyword.value.trim());
    }

    if (selectedPropertyType.value && selectedPropertyType.value !== DEFAULT_PROPERTY_TYPE) {
        searchParams.set('property_type', selectedPropertyType.value);
    }

    if (minPrice.value) {
        searchParams.set('price_min', minPrice.value);
    }

    if (maxPrice.value) {
        searchParams.set('price_max', maxPrice.value);
    }

    const targetPath = activeListingType.value === 'rent' ? '/post-rent' : '/post-sell';
    const queryString = searchParams.toString();

    window.location.href = queryString ? `${targetPath}?${queryString}` : targetPath;
}

function getCategoryFilterUrl(categoryName) {
    const searchParams = new URLSearchParams();

    if (categoryName) {
        searchParams.set('property_type', categoryName);
    }

    const queryString = searchParams.toString();

    return queryString ? `/post-sell?${queryString}` : '/post-sell';
}
</script>

<template>
    <ClientLayout>
        <div class="home-page bg-gray-50 text-gray-900">
            <section
                class="relative flex min-h-105 items-center justify-center py-12 md:min-h-125 md:py-0"
            >
                <img
                    src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?q=80&w=2070&auto=format&fit=crop"
                    class="absolute inset-0 h-full w-full object-cover"
                    alt="Hero Background"
                />
                <div class="absolute inset-0 bg-black/40"></div>

                <div class="relative z-10 w-full max-w-6xl px-4 text-center sm:px-6">
                    <h1
                        class="mb-5 text-2xl font-extrabold text-white drop-shadow-2xl sm:mb-6 sm:text-3xl md:mb-8 md:text-4xl"
                    >
                        Bất động sản cho mọi nhu cầu
                    </h1>

                    <div
                        class="overflow-hidden rounded-2xl border border-orange-100 bg-white/95 p-4 text-left shadow-2xl backdrop-blur sm:p-5 md:p-6"
                    >
                        <div
                            class="mb-4 flex flex-col gap-3 md:mb-5 md:flex-row md:items-center md:justify-between"
                        >
                            <div
                                class="flex items-center gap-2 text-xs font-semibold tracking-[0.2em] text-orange-600 uppercase"
                            >
                                <span
                                    class="rounded-full border border-orange-200 bg-orange-50 px-2 py-1"
                                    >Hot</span
                                >
                                <span>Tìm kiếm nhanh</span>
                            </div>
                            <div class="flex flex-wrap gap-2 text-[11px] font-medium text-gray-500 md:justify-end">
                                <span class="rounded-full bg-gray-100 px-3 py-1"
                                    >Cập nhật liên tục</span
                                >
                                <span class="rounded-full bg-gray-100 px-3 py-1"
                                    >1000+ tin mới</span
                                >
                            </div>
                        </div>

                        <div
                            class="mb-4 flex gap-2 overflow-x-auto text-sm font-bold tracking-wider uppercase md:mb-5 md:gap-4"
                        >
                            <button
                                @click="activeListingType = 'sell'"
                                class="shrink-0 border-b-2 px-3 pb-3 transition"
                                :class="
                                    activeListingType === 'sell'
                                        ? 'border-orange-600 text-orange-600'
                                        : 'border-transparent text-gray-500 hover:border-orange-500 hover:text-orange-500'
                                "
                            >
                                Nhà đất bán
                            </button>
                            <button
                                @click="activeListingType = 'rent'"
                                class="shrink-0 border-b-2 px-3 pb-3 transition"
                                :class="
                                    activeListingType === 'rent'
                                        ? 'border-orange-600 text-orange-600'
                                        : 'border-transparent text-gray-500 hover:border-orange-500 hover:text-orange-500'
                                "
                            >
                                Nhà đất cho thuê
                            </button>
                        </div>

                        <div
                            class="grid grid-cols-1 gap-3 md:grid-cols-12 md:gap-4"
                        >
                            <div class="relative md:col-span-5">
                                <i
                                    class="fa-solid fa-magnifying-glass absolute top-3.5 left-3 text-gray-400"
                                ></i>
                                <input
                                    v-model="searchKeyword"
                                    type="text"
                                    placeholder="Tìm kiếm địa điểm, dự án..."
                                    class="w-full rounded-xl border border-gray-200 bg-white py-3 pr-4 pl-10 text-sm shadow-sm transition focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200"
                                />
                            </div>

                            <div class="relative md:col-span-2">
                                <select
                                    v-model="selectedPropertyType"
                                    class="w-full appearance-none rounded-xl border border-gray-200 bg-white px-3 py-3 pr-10 text-sm leading-tight shadow-sm transition focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200"
                                >
                                    <option v-for="type in propertyTypes" :key="type">
                                        {{ type }}
                                    </option>
                                </select>
                                <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <i class="fa-solid fa-chevron-down"></i>
                                </span>
                            </div>

                            <div class="md:col-span-2">
                                <div class="flex items-center gap-2">
                                    <input
                                        v-model="minPrice"
                                        type="number"
                                        min="0"
                                        step="1000000"
                                        inputmode="numeric"
                                        placeholder="Giá từ"
                                        class="w-full rounded-xl border border-gray-200 bg-white px-3 py-3 text-sm shadow-sm transition focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200"
                                    />
                                    <span class="text-gray-300">-</span>
                                    <input
                                        v-model="maxPrice"
                                        type="number"
                                        min="0"
                                        step="1000000"
                                        inputmode="numeric"
                                        placeholder="Giá đến"
                                        class="w-full rounded-xl border border-gray-200 bg-white px-3 py-3 text-sm shadow-sm transition focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200"
                                    />
                                </div>
                            </div>

                            <div class="md:col-span-3">
                                <button
                                    @click="submitQuickSearch"
                                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-linear-to-r from-orange-500 to-amber-500 py-3 font-bold text-white shadow-lg transition hover:from-orange-600 hover:to-amber-600"
                                >
                                    <i class="fa-solid fa-search"></i> Tìm kiếm
                                    ngay
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="border-b border-gray-100 bg-white py-8">
                <div class="container mx-auto px-4">
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6">
                        <a
                            v-for="category in categories"
                            :key="category.id"
                            :href="getCategoryFilterUrl(category.category_name)"
                            class="group overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
                        >
                            <div class="relative h-28 overflow-hidden">
                                <img
                                    :src="category.image"
                                    :alt="category.category_name"
                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-110"
                                />
                                <div class="absolute inset-0 bg-linear-to-t from-black/45 to-transparent"></div>
                            </div>
                            <div class="p-3">
                                <span class="line-clamp-1 text-center text-sm font-semibold text-gray-700 transition group-hover:text-orange-600">
                                    {{ category.category_name }}
                                </span>
                            </div>
                        </a>
                    </div>
                </div>
            </section>

            <section class="overflow-hidden bg-white py-12 md:py-14">
                <div class="relative container mx-auto max-w-6xl px-4">
                    <div
                        class="mb-8 flex items-center justify-between md:mb-10"
                    >
                        <h2 class="text-2xl font-black tracking-tighter uppercase md:text-3xl">
                            Dự án tầm cỡ
                        </h2>
                        <div class="flex gap-2">
                            <button
                                @click="slide('prev')"
                                class="h-10 w-10 rounded-full border hover:bg-gray-100"
                            >
                                <i class="fa-solid fa-chevron-left"></i>
                            </button>
                            <button
                                @click="slide('next')"
                                class="h-10 w-10 rounded-full border hover:bg-gray-100"
                            >
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    <div
                        id="project-slider"
                        ref="sliderRef"
                        class="no-scrollbar flex snap-x snap-mandatory gap-4 overflow-x-auto scroll-smooth md:gap-6"
                    >
                        <div
                            v-for="project in projectPosts"
                            :key="project.id"
                            data-card
                            class="group relative w-60 shrink-0 cursor-pointer snap-start sm:w-65 md:w-80"
                        >
                            <div
                                class="h-85 overflow-hidden rounded-3xl shadow-lg sm:h-90 md:h-105"
                            >
                                <img
                                    :src="project.img"
                                    class="h-full w-full object-cover transition duration-700 group-hover:scale-110"
                                    :alt="project.title"
                                />
                                <div class="absolute top-5 right-5 z-20">
                                    <button
                                        @click.stop="
                                            toggleProjectFavorite(project.id)
                                        "
                                        class="h-10 w-10 rounded-full bg-white/20 text-white backdrop-blur-md transition-all hover:bg-white hover:text-red-500"
                                    >
                                        <i
                                            :class="[
                                                projectFavorites.has(project.id)
                                                    ? 'fa-solid heart-active'
                                                    : 'fa-regular',
                                                'fa-heart',
                                            ]"
                                        ></i>
                                    </button>
                                </div>
                                <div class="absolute inset-0 flex flex-col justify-end bg-linear-to-t from-black/90 via-transparent to-transparent p-6">
                                    <span
                                        class="text-xs font-bold text-orange-400 uppercase"
                                        >{{ project.listing_type || 'Bất động sản' }}</span
                                    >
                                    <h4
                                        class="mt-1 text-xl font-black text-white uppercase"
                                    >
                                        {{ project.title }}
                                    </h4>
                                    <p
                                        class="mt-2 line-clamp-1 text-sm text-gray-300"
                                    >
                                        <i class="fa-solid fa-location-dot"></i>
                                        {{ project.address }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-gray-50 py-12 md:py-14">
                <div class="container mx-auto max-w-6xl px-4">
                    <h2 class="mb-8 text-center text-2xl font-black uppercase md:mb-10 md:text-3xl">
                        Bất động sản HOT
                    </h2>
                    <div
                        id="hot-product-grid"
                        class="grid grid-cols-1 gap-5 sm:grid-cols-2 md:gap-6 lg:grid-cols-4"
                    >
                        <div
                            v-for="post in visibleHotPosts"
                            :key="post.id"
                            class="product-item group overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all hover:shadow-xl"
                        >
                        <a :href="`/post-detail/${post.id}`">
                            <div class="relative h-48 overflow-hidden md:h-52">
                                <img
                                    :src="post.img"
                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-110"
                                    :alt="post.title"
                                />
                                <div
                                    class="absolute top-3 left-3 rounded bg-red-600 px-2 py-1 text-[10px] font-bold text-white"
                                >
                                    HOT
                                </div>
                            </div>
                            </a>
                            <div class="p-5">
                                <h3
                                    class="mb-2 truncate font-bold text-gray-800"
                                >
                                    {{ post.title }}
                                </h3>
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-lg font-bold text-red-600"
                                        >{{ formatCurrency(post.price) }}</span
                                    >
                                    <span class="text-sm text-gray-400">{{ formatArea(post.area) }}</span>
                                </div>
                                <div
                                    class="mt-4 flex items-center justify-between border-t pt-4"
                                >
                                    <span class="text-xs text-gray-400"
                                        ><i
                                            class="fa-solid fa-location-dot"
                                        ></i>
                                        {{ post.address }}</span
                                    >
                                    <i
                                        @click="toggleHotFavorite(post.id)"
                                        :class="[
                                            hotFavorites.has(post.id)
                                                ? 'fa-solid heart-active'
                                                : 'fa-regular',
                                            'fa-heart',
                                            'cursor-pointer text-gray-300 transition-all hover:text-red-500',
                                        ]"
                                    ></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-10 text-center md:mt-12">
                        <button
                            v-if="canShowLoadMoreButton"
                            id="btn-load-more"
                            @click="handleLoadMore"
                            class="w-full rounded-full border-2 border-orange-600 px-6 py-3 font-bold text-orange-600 shadow-lg transition-all hover:bg-orange-600 hover:text-white sm:w-auto sm:px-10"
                        >
                            {{ loadMoreLabel }}
                            <i
                                class="fa-solid"
                                :class="'fa-chevron-down ml-2'"
                            ></i>
                        </button>
                    </div>
                </div>
            </section>

            <section class="bg-white py-16">
                <div class="container mx-auto px-4">
                    <h2 class="mb-12 text-center text-2xl font-black tracking-tighter uppercase md:text-3xl">
                        Tin tức thị trường
                    </h2>
                    <div class="grid grid-cols-1 gap-10 lg:grid-cols-2">
                        <div
                            v-for="blog in blogs"
                            :key="blog.id"
                            class="group flex cursor-pointer flex-col gap-6 border-b border-gray-100 pb-8 md:flex-row"
                        >
                            <div
                                class="h-40 w-full overflow-hidden rounded-2xl md:w-56"
                            >
                                <img
                                    :src="blog.image"
                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-110"
                                    :alt="blog.title"
                                />
                            </div>
                            <div class="flex-1">
                                <span
                                    class="text-[10px] font-black tracking-widest text-orange-600 uppercase"
                                    >Thị trường 2026</span
                                >
                                <h4
                                    class="mt-2 line-clamp-2 text-lg font-bold transition group-hover:text-orange-600"
                                >
                                    {{ blog.title }}
                                </h4>
                                <p
                                    class="mt-3 line-clamp-2 text-sm text-gray-500"
                                >
                                    {{ blog.excerpt }}
                                </p>
                                <div
                                    class="mt-4 text-[10px] font-bold text-gray-400 uppercase"
                                >
                                    {{ formatDate(blog.created_at) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-white py-12 md:py-14">
                <div class="container mx-auto max-w-5xl px-4">
                    <div class="rounded-2xl border border-orange-100 bg-white p-6 text-center shadow-2xl sm:p-8 md:p-10">
                        <p class="text-xs font-semibold uppercase tracking-[0.3em] text-orange-600">Đăng tin nhanh</p>
                        <h3 class="mt-3 text-2xl font-black leading-tight text-gray-900 md:text-4xl">
                            Mua gói ngay để đăng bất động sản của bạn
                        </h3>
                        <p class="mx-auto mt-3 max-w-2xl text-sm text-gray-600 md:text-base">
                            Tiếp cận hàng nghìn khách hàng tiềm năng mỗi ngày với tin đăng nổi bật, hỗ trợ hình ảnh sắc nét và báo cáo hiệu quả theo thời gian thực.
                        </p>
                        <div class="mt-6 flex flex-col justify-center gap-3 sm:flex-row sm:flex-wrap">
                            <a
                                href="/package"
                                class="rounded-full bg-linear-to-r from-orange-500 to-amber-500 px-6 py-3 text-center text-sm font-bold text-white shadow-lg transition hover:from-orange-600 hover:to-amber-600"
                            >
                                Mua gói ngay
                            </a>
                            <Link
                                href="/dang-tin"
                                class="rounded-full border border-orange-200 px-6 py-3 text-center text-sm font-bold text-orange-600 transition hover:bg-orange-50"
                            >
                                Đăng tin ngay
                            </Link>
                            <a
                                href="/contact"
                                class="rounded-full border border-orange-200 px-6 py-3 text-center text-sm font-bold text-orange-600 transition hover:bg-orange-50"
                            >
                                Tư vấn miễn phí
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </ClientLayout>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

.home-page {
    font-family: 'Inter', sans-serif;
    scroll-behavior: smooth;
}

.no-scrollbar::-webkit-scrollbar {
    display: none;
}

.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.heart-active {
    color: #ef4444 !important;
    font-weight: 900 !important;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(15px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeIn {
    animation: fadeIn 0.5s ease forwards;
}
</style>
