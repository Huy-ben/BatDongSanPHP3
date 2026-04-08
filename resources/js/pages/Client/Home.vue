<script setup>
import { computed, ref } from 'vue';
import axios from 'axios';
import { Link } from '@inertiajs/vue3';
import ClientLayout from '@/layouts/ClientLayout.vue';
import Footer from '@/components/btbcomponents/Footer.vue';
import { onMounted } from 'vue';
const posts = ref([]);
onMounted(() => {
    fetchPosts();
});
const fetchPosts = () => {
    axios.get('/api/home')
        .then(response => {
            posts.value = response.data;
            console.log('Fetched posts:', posts.value);
        })
        .catch(error => {
            console.error('Error fetching posts:', error);
        });
};

const categories = [
    { label: 'Căn hộ', icon: 'fa-building' },
    { label: 'Nhà riêng', icon: 'fa-house' },
    { label: 'Nhà phố', icon: 'fa-shop' },
    { label: 'Đất nền', icon: 'fa-map' },
    { label: 'Kho xưởng', icon: 'fa-warehouse' },
    { label: 'Biệt thự', icon: 'fa-gem' },
];

const projects = Array.from({ length: 6 }, (_, index) => ({
    id: index + 1,
    name: `Diamond Riverside ${index + 1}`,
    badge: `Dự án cao cấp #0${index + 1}`,
    location: 'Thủ Thiêm, TP. Hồ Chí Minh',
    image: `https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=600&sig=${index + 1}`,
}));

const houses = [
    {
        id: 1,
        title: 'Penthouse Riverview',
        price: '45 Tỷ',
        area: '250m²',
        img: 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=500',
        location: 'TP. Hồ Chí Minh',
    },
    {
        id: 2,
        title: 'Villa Gardenia',
        price: '12 Tỷ',
        area: '180m²',
        img: 'https://images.unsplash.com/photo-1600585154340-be6199f7d009?w=500',
        location: 'TP. Hồ Chí Minh',
    },
    {
        id: 3,
        title: 'Căn hộ Fiato Uptown',
        price: '3.8 Tỷ',
        area: '85m²',
        img: 'https://images.unsplash.com/photo-1567496898905-af404c45a95a?w=500',
        location: 'TP. Hồ Chí Minh',
    },
    {
        id: 4,
        title: 'Shophouse Center',
        price: '15 Tỷ',
        area: '120m²',
        img: 'https://images.unsplash.com/photo-1582407947304-fd86f028f716?w=500',
        location: 'TP. Hồ Chí Minh',
    },
    {
        id: 5,
        title: 'Studio Landmark 81',
        price: '5.5 Tỷ',
        area: '55m²',
        img: 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=500',
        location: 'TP. Hồ Chí Minh',
    },
    {
        id: 6,
        title: 'Nhà phố Lakeview',
        price: '9.2 Tỷ',
        area: '105m²',
        img: 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=500',
        location: 'TP. Hồ Chí Minh',
    },
    {
        id: 7,
        title: 'Duplex Cao Cấp',
        price: '18 Tỷ',
        area: '140m²',
        img: 'https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=500',
        location: 'TP. Hồ Chí Minh',
    },
    {
        id: 8,
        title: 'Đất nền Biệt thự',
        price: '7.5 Tỷ',
        area: '200m²',
        img: 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=500',
        location: 'TP. Hồ Chí Minh',
    },
];

const newsList = Array.from({ length: 6 }, (_, index) => ({
    id: index + 1,
    title: `Cơn sốt bất động sản phía Đông và những điều cần biết khi đầu tư #${index + 1}`,
    summary:
        'Phân tích chi tiết dòng tiền và xu hướng dịch chuyển của các nhà đầu tư lớn trong năm 2026...',
    date: '30 Tháng 3, 2026',
    readTime: '4 phút đọc',
    image: `https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=400&sig=${index + 11}`,
}));

const propertyTypes = [
    'Tất cả loại',
    'Căn hộ',
    'Nhà phố',
    'Biệt thự',
    'Đất nền',
    'Kho xưởng',
];

const priceRanges = [
    'Mức giá',
    'Dưới 2 tỷ',
    '2 - 5 tỷ',
    '5 - 10 tỷ',
    '10 - 20 tỷ',
    'Trên 20 tỷ',
];

const selectedPropertyType = ref(propertyTypes[0]);
const selectedPriceRange = ref(priceRanges[0]);

const projectFavorites = ref(new Set());
const hotFavorites = ref(new Set());
const sliderRef = ref(null);
const loadClicks = ref(0);

const showAllHouses = computed(() => loadClicks.value > 0);
const visibleHouses = computed(() =>
    showAllHouses.value ? houses : houses.slice(0, 4),
);
const loadMoreLabel = computed(() =>
    loadClicks.value === 0 ? 'XEM THÊM SẢN PHẨM' : 'XEM TẤT CẢ SẢN PHẨM',
);

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

function toggleProjectFavorite(id) {
    const next = new Set(projectFavorites.value);
    next.has(id) ? next.delete(id) : next.add(id);
    projectFavorites.value = next;
}

function toggleHotFavorite(id) {
    const next = new Set(hotFavorites.value);
    next.has(id) ? next.delete(id) : next.add(id);
    hotFavorites.value = next;
}

function handleLoadMore() {
    loadClicks.value += 1;
    if (loadClicks.value > 1) {
        window.location.href = '/post-sell';
    }
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

                <div class="relative z-10 w-full max-w-4xl px-4 text-center sm:px-6">
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
                                class="shrink-0 border-b-2 border-orange-600 px-3 pb-3 text-orange-600"
                            >
                                Nhà đất bán
                            </button>
                            <button
                                class="shrink-0 px-3 pb-3 text-gray-500 transition hover:border-b-2 hover:border-orange-500 hover:text-orange-500"
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

                            <div class="relative md:col-span-2">
                                <select
                                    v-model="selectedPriceRange"
                                    class="w-full appearance-none rounded-xl border border-gray-200 bg-white px-3 py-3 pr-10 text-sm leading-tight shadow-sm transition focus:border-orange-500 focus:outline-none focus:ring-2 focus:ring-orange-200"
                                >
                                    <option v-for="range in priceRanges" :key="range">
                                        {{ range }}
                                    </option>
                                </select>
                                <span class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 text-gray-400">
                                    <i class="fa-solid fa-chevron-down"></i>
                                </span>
                            </div>

                            <div class="md:col-span-3">
                                <button
                                    class="flex w-full items-center justify-center gap-2 rounded-xl bg-linear-to-r from-orange-500 to-amber-500 py-3 font-bold text-white shadow-lg transition hover:from-orange-600 hover:to-amber-600"
                                >
                                    <i class="fa-solid fa-search"></i> Tìm kiếm
                                    ngay
                                </button>
                            </div>
                        </div>

                        <div
                            class="mt-4 flex flex-wrap gap-2 text-xs text-gray-600 md:mt-5"
                        >
                            <span
                                class="rounded-full border border-orange-100 bg-orange-50 px-3 py-1 text-orange-700"
                                >Quận 1</span
                            >
                            <span
                                class="rounded-full border border-orange-100 bg-orange-50 px-3 py-1 text-orange-700"
                                >Thủ Đức</span
                            >
                            <span
                                class="rounded-full border border-orange-100 bg-orange-50 px-3 py-1 text-orange-700"
                                >Biệt thự</span
                            >
                            <span
                                class="rounded-full border border-orange-100 bg-orange-50 px-3 py-1 text-orange-700"
                                >Dưới 5 tỷ</span
                            >
                        </div>
                    </div>
                </div>
            </section>

            <section class="border-b border-gray-100 bg-white py-8">
                <div class="container mx-auto px-4">
                    <div class="grid grid-cols-3 gap-3 sm:flex sm:flex-wrap sm:justify-center sm:gap-4 md:gap-8">
                        <a
                            v-for="category in categories"
                            :key="category.label"
                            href="#"
                            class="group flex flex-col items-center rounded-xl border border-transparent py-2 transition hover:border-orange-100 hover:bg-orange-50 sm:py-0"
                        >
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-50 transition-colors group-hover:bg-orange-50"
                            >
                                <i
                                    :class="[
                                        'fa-solid',
                                        category.icon,
                                        'text-gray-500',
                                        'group-hover:text-orange-600',
                                    ]"
                                ></i>
                            </div>
                            <span class="mt-2 text-center text-xs font-medium text-gray-600 group-hover:text-orange-600">{{ category.label }}</span>
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
                            v-for="project in projects"
                            :key="project.id"
                            data-card
                            class="group relative w-60 shrink-0 cursor-pointer snap-start sm:w-65 md:w-80"
                        >
                            <div
                                class="h-85 overflow-hidden rounded-3xl shadow-lg sm:h-90 md:h-105"
                            >
                                <img
                                    :src="project.image"
                                    class="h-full w-full object-cover transition duration-700 group-hover:scale-110"
                                    :alt="project.name"
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
                                        >{{ project.badge }}</span
                                    >
                                    <h4
                                        class="mt-1 text-xl font-black text-white uppercase"
                                    >
                                        {{ project.name }}
                                    </h4>
                                    <p
                                        class="mt-2 line-clamp-1 text-sm text-gray-300"
                                    >
                                        <i class="fa-solid fa-location-dot"></i>
                                        {{ project.location }}
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
                            v-for="post in posts"
                            :key="post.id"
                            class="product-item group overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm transition-all hover:shadow-xl"
                            :class="{ 'animate-fadeIn': showAllHouses }"
                        >
                        <a href="/post-detail">
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
                                        >{{ post.price }}</span
                                    >
                                    <span class="text-sm text-gray-400">{{
                                        post.area
                                    }}</span>
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
                            id="btn-load-more"
                            @click="handleLoadMore"
                            class="w-full rounded-full border-2 border-orange-600 px-6 py-3 font-bold text-orange-600 shadow-lg transition-all hover:bg-orange-600 hover:text-white sm:w-auto sm:px-10"
                        >
                            {{ loadMoreLabel }}
                            <i
                                class="fa-solid"
                                :class="
                                    loadClicks === 0
                                        ? 'fa-chevron-down ml-2'
                                        : 'fa-arrow-right ml-2'
                                "
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
                            v-for="news in newsList"
                            :key="news.id"
                            class="group flex cursor-pointer flex-col gap-6 border-b border-gray-100 pb-8 md:flex-row"
                        >
                            <div
                                class="h-40 w-full overflow-hidden rounded-2xl md:w-56"
                            >
                                <img
                                    :src="news.image"
                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-110"
                                    :alt="news.title"
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
                                    {{ news.title }}
                                </h4>
                                <p
                                    class="mt-3 line-clamp-2 text-sm text-gray-500"
                                >
                                    {{ news.summary }}
                                </p>
                                <div
                                    class="mt-4 text-[10px] font-bold text-gray-400 uppercase"
                                >
                                    {{ news.date }} • {{ news.readTime }}
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
                                href="/lien-he"
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
