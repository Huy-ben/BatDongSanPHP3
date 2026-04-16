<script setup lang="ts">
import ClientLayout from '@/layouts/ClientLayout.vue';
import { computed, nextTick, onMounted, onUnmounted, ref } from 'vue';
import { jam_read_num_forvietnamese } from '@/utils/money';

declare global {
    interface Window {
        maplibregl?: any;
    }
}

type DetailPost = {
    id: number;
    slug: string;
    title: string;
    price: number | string;
    area: number | string;
    address: string;
    location: string;
    description: string;
    created_at: string | null;
    expires_at: string | null;
    category_name: string;
    listing_type: string;
    images: string[];
    seller_name: string;
    seller_phone: string;
    seller_avatar: string;
    seller_post_count: number;
};

type RelatedPost = {
    id: number;
    title: string;
    price: number | string;
    address: string;
    img: string;
};

type FeaturedPost = {
    id: number;
    title: string;
    price: number | string;
    address: string;
    img: string;
    category_name: string;
    listing_type: string;
    seller_name: string;
    seller_avatar: string;
};

type GalleryImage = {
    src: string;
    label: string;
};

const props = defineProps<{
    post: DetailPost | null;
    relatedPosts: RelatedPost[];
    featuredPosts: FeaturedPost[];
}>();

const fallbackImages: GalleryImage[] = [
    {
        src: 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1200&q=85',
        label: 'Ảnh mặc định',
    },
];

const galleryImages = computed<GalleryImage[]>(() => {
    const images = props.post?.images
        ?.filter((item) => !!item)
        .map((src, index) => ({
            src,
            label: `Hình ${index + 1}`,
        }));

    return images && images.length ? images : fallbackImages;
});

const postTitle = computed(() => props.post?.title || 'Tin đăng bất động sản');
const listingType = computed(() => props.post?.listing_type || 'Bất động sản');
const categoryName = computed(() => props.post?.category_name || 'Chi tiết tin đăng');
const postAddress = computed(() => props.post?.address || 'Đang cập nhật địa chỉ');
const postDescription = computed(() => props.post?.description || 'Đang cập nhật mô tả.');
const postCode = computed(() => props.post?.id || 0);
const sellerName = computed(() => props.post?.seller_name || 'Chính chủ');
const sellerPostCount = computed(() => props.post?.seller_post_count || 0);
const sellerAvatar = computed(() => {
    const avatar = props.post?.seller_avatar?.trim();
    if (avatar) {
        return avatar;
    }

    const encodedName = encodeURIComponent(sellerName.value || 'User');
    return `https://ui-avatars.com/api/?name=${encodedName}&background=F97316&color=fff&size=128`;
});

const breadcrumbListingHref = computed(() => {
    const type = (props.post?.listing_type || '').toLowerCase();

    if (type.includes('thuê')) {
        return '/post-rent';
    }

    if (type.includes('bán') || type.includes('ban')) {
        return '/post-sell';
    }

    return '/';
});

const breadcrumbCategoryHref = computed(() => breadcrumbListingHref.value);

const formattedPrice = computed(() => {
    const amount = Number(props.post?.price || 0);
    return jam_read_num_forvietnamese(amount);
});

const formattedArea = computed(() => {
    const area = Number(props.post?.area || 0);
    if (!area) {
        return 'Đang cập nhật';
    }

    return `${new Intl.NumberFormat('vi-VN', { maximumFractionDigits: 2 }).format(area)} m²`;
});

const pricePerSquareMeter = computed(() => {
    const amount = Number(props.post?.price || 0);
    const area = Number(props.post?.area || 0);

    if (!amount || !area) {
        return 'Đang cập nhật';
    }

    const value = Math.round(amount / area);
    return `~${jam_read_num_forvietnamese(value)}/m²`;
});

const displayPhone = computed(() => {
    const raw = (props.post?.seller_phone || '').replace(/\D/g, '');
    if (raw.length < 9) {
        return 'Đang cập nhật';
    }

    if (raw.length === 10) {
        return `${raw.slice(0, 4)} ${raw.slice(4, 7)} ${raw.slice(7)}`;
    }

    if (raw.length === 11) {
        return `${raw.slice(0, 4)} ${raw.slice(4, 7)} ${raw.slice(7)}`;
    }

    return raw;
});

const callablePhone = computed(() => {
    const raw = (props.post?.seller_phone || '').replace(/\D/g, '');
    return raw.length >= 9 ? `tel:${raw}` : '#';
});

const hasCallablePhone = computed(() => callablePhone.value !== '#');

const zaloLink = computed(() => {
    const raw = (props.post?.seller_phone || '').replace(/\D/g, '');
    if (raw.length < 9) {
        return '#';
    }

    if (raw.startsWith('0')) {
        return `https://zalo.me/${raw}`;
    }

    return `https://zalo.me/${raw}`;
});

const hasZaloLink = computed(() => zaloLink.value !== '#');

const GOONG_MAP_KEY = 'NDYPjCSusn6YSEozAla1mgcP7pTXoFU3tIamrb9M';
const DEFAULT_LOCATION = { lat: 16.047079, lng: 108.20623 };

const mapContainerRef = ref<HTMLDivElement | null>(null);
const mapRef = ref<any>(null);
const markerRef = ref<any>(null);

const postedDate = computed(() => {
    if (!props.post?.created_at) {
        return 'Đang cập nhật';
    }

    return new Intl.DateTimeFormat('vi-VN').format(new Date(props.post.created_at));
});

const expiryDate = computed(() => {
    if (!props.post?.expires_at) {
        return 'Đang cập nhật';
    }

    return new Intl.DateTimeFormat('vi-VN').format(new Date(props.post.expires_at));
});

let current = 0;
let mainSlide: HTMLImageElement | null = null;
let counter: HTMLElement | null = null;
let dotContainer: HTMLElement | null = null;
let thumbStrip: HTMLElement | null = null;

function parseLocation(value: string) {
    const [latRaw, lngRaw] = (value || '')
        .split(',')
        .map((item) => item.trim());
    const lat = Number(latRaw);
    const lng = Number(lngRaw);

    if (!Number.isFinite(lat) || !Number.isFinite(lng)) {
        return null;
    }

    return { lat, lng };
}

function ensureMapLibreAssets() {
    return new Promise<void>((resolve, reject) => {
        if (window.maplibregl) {
            resolve();
            return;
        }

        const cssId = 'maplibre-css';
        if (!document.getElementById(cssId)) {
            const link = document.createElement('link');
            link.id = cssId;
            link.rel = 'stylesheet';
            link.href = 'https://unpkg.com/maplibre-gl@4.7.1/dist/maplibre-gl.css';
            document.head.appendChild(link);
        }

        const scriptId = 'maplibre-script';
        const existingScript = document.getElementById(scriptId) as HTMLScriptElement | null;

        if (existingScript) {
            const checkLoaded = () => {
                if (window.maplibregl) {
                    resolve();
                } else {
                    setTimeout(checkLoaded, 50);
                }
            };
            checkLoaded();
            return;
        }

        const script = document.createElement('script');
        script.id = scriptId;
        script.src = 'https://unpkg.com/maplibre-gl@4.7.1/dist/maplibre-gl.js';
        script.async = true;
        script.onload = () => resolve();
        script.onerror = () => reject(new Error('Khong the tai MapLibre'));
        document.body.appendChild(script);
    });
}

function upsertMarker(lat: number, lng: number) {
    if (!window.maplibregl || !mapRef.value) {
        return;
    }

    if (!markerRef.value) {
        markerRef.value = new window.maplibregl.Marker({
            color: '#ef4444',
            draggable: false,
        })
            .setLngLat([lng, lat])
            .addTo(mapRef.value);
    } else {
        markerRef.value.setLngLat([lng, lat]);
    }
}

async function initMap() {
    await ensureMapLibreAssets();
    await nextTick();

    if (!mapContainerRef.value || !window.maplibregl || mapRef.value) {
        return;
    }

    const location = parseLocation(props.post?.location || '') || DEFAULT_LOCATION;

    mapRef.value = new window.maplibregl.Map({
        container: mapContainerRef.value,
        style: `https://tiles.goong.io/assets/goong_map_web.json?api_key=${GOONG_MAP_KEY}`,
        center: [location.lng, location.lat],
        zoom: 14,
    });

    mapRef.value.on('load', () => {
        upsertMarker(location.lat, location.lng);
    });
}

function goTo(idx: number) {
    if (!mainSlide || !counter) {
        return;
    }

    document
        .getElementById(`thumb-${current}`)
        ?.classList.remove('thumb-active');
    document.getElementById(`dot-${current}`)?.classList.remove('bg-white');
    document.getElementById(`dot-${current}`)?.classList.add('bg-white/50');
    const totalImages = galleryImages.value.length;
    current = (idx + totalImages) % totalImages;
    mainSlide.classList.remove('slide-active');
    void mainSlide.offsetWidth; // reflow
    mainSlide.src = galleryImages.value[current].src;
    mainSlide.classList.add('slide-active');
    counter.textContent = `${current + 1} / ${totalImages}`;
    const activeThumb = document.getElementById(`thumb-${current}`);
    activeThumb?.classList.add('thumb-active');
    activeThumb?.scrollIntoView({
        behavior: 'smooth',
        block: 'nearest',
        inline: 'nearest',
    });
    document.getElementById(`dot-${current}`)?.classList.add('bg-white');
    document.getElementById(`dot-${current}`)?.classList.remove('bg-white/50');
}

function changeSlide(dir: number) {
    goTo(current + dir);
}

function handleKeydown(e: KeyboardEvent) {
    if (e.key === 'ArrowLeft') changeSlide(-1);
    if (e.key === 'ArrowRight') changeSlide(1);
}

function initGallery() {
    mainSlide = document.getElementById('mainSlide') as HTMLImageElement | null;
    counter = document.getElementById('slideCounter');
    dotContainer = document.getElementById('dotContainer');
    thumbStrip = document.getElementById('thumbStrip');

    if (!mainSlide || !counter || !dotContainer || !thumbStrip) {
        return;
    }

    const mountedDotContainer = dotContainer;
    const mountedThumbStrip = thumbStrip;

    mountedThumbStrip.innerHTML = '';
    mountedDotContainer.innerHTML = '';

    // Build thumbnails and dots after DOM is mounted.
    current = 0;

    galleryImages.value.forEach((img, i) => {
        const btn = document.createElement('button');
        btn.className =
            'flex-shrink-0 rounded-lg overflow-hidden focus:outline-none transition';
        btn.style.width = '88px';
        btn.style.height = '64px';
        btn.innerHTML = `<img src="${img.src.replace('w=1200', 'w=200')}" alt="${img.label}" class="w-full h-full object-cover hover:opacity-90 transition" />`;
        btn.onclick = () => goTo(i);
        btn.id = `thumb-${i}`;
        mountedThumbStrip.appendChild(btn);

        const dot = document.createElement('button');
        dot.className =
            'w-1.5 h-1.5 rounded-full bg-white/50 hover:bg-white transition';
        dot.onclick = () => goTo(i);
        dot.id = `dot-${i}`;
        mountedDotContainer.appendChild(dot);
    });

    goTo(0);
}

onMounted(() => {
    initGallery();
    initMap();
    document.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
    document.removeEventListener('keydown', handleKeydown);

    if (mapRef.value) {
        mapRef.value.remove();
        mapRef.value = null;
    }

    markerRef.value = null;
});
</script>

<template>
    <ClientLayout>
        <body class="bg-gray-50 font-sans text-gray-800 antialiased">
            <main class="mx-auto max-w-7xl px-4 py-6">
                <!-- BREADCRUMB -->
                <nav
                    class="mb-4 flex flex-wrap items-center text-xs text-gray-500"
                >
                    <a :href="breadcrumbListingHref" class="hover:text-brand">{{ listingType }}</a>
                    <span class="breadcrumb-sep"></span>
                    <a :href="breadcrumbCategoryHref" class="hover:text-brand">{{ categoryName }}</a>
                    <span class="breadcrumb-sep"></span>
                    <span class="font-medium text-gray-700">{{ postTitle }}</span>
                </nav>

                <div class="flex gap-6">
                    <!-- LEFT COLUMN -->
                    <div class="min-w-0 flex-1">
                        <!-- TITLE -->
                        <h1
                            class="text-navy mb-1 text-xl leading-snug font-bold md:text-2xl"
                        >
                            {{ postTitle }}
                        </h1>
                        <div
                            class="mb-4 flex items-center gap-2 text-sm text-gray-500"
                        >
                            <svg
                                class="text-brand h-4 w-4 shrink-0"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <span>{{ postAddress }}</span>
                        </div>

                        <!-- GALLERY SLIDESHOW -->
                        <div class="mb-5" id="gallery">
                            <!-- Main slide -->
                            <div
                                class="relative overflow-hidden rounded-xl bg-black"
                                style="height: 420px"
                            >
                                <img
                                    id="mainSlide"
                                    :src="galleryImages[0]?.src"
                                    alt="Ảnh chính"
                                    class="slide-active h-full w-full object-cover"
                                />

                                <!-- Counter badge -->
                                <div
                                    class="absolute bottom-4 left-4 flex items-center gap-1.5 rounded-full bg-black/60 px-3 py-1.5 text-xs font-semibold text-white backdrop-blur-sm"
                                >
                                    <svg
                                        class="h-3.5 w-3.5"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        viewBox="0 0 24 24"
                                    >
                                        <rect
                                            x="3"
                                            y="3"
                                            width="18"
                                            height="18"
                                            rx="2"
                                        />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <path d="M21 15l-5-5L5 21" />
                                    </svg>
                                    <span id="slideCounter">1 / 12</span>
                                </div>

                                <!-- Action buttons top-right -->
                                <div class="absolute top-3 right-3 flex gap-2">
                                    <button
                                        class="rounded-lg bg-black/50 p-2 text-white backdrop-blur-sm transition hover:bg-black/70"
                                    >
                                        <svg
                                            class="h-4 w-4"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"
                                            />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Prev button -->
                                <button
                                    @click="changeSlide(-1)"
                                    class="absolute top-1/2 left-3 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-black/50 text-white shadow-lg backdrop-blur-sm transition hover:bg-black/75"
                                >
                                    <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2.5"
                                        viewBox="0 0 24 24"
                                    >
                                        <path d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                <!-- Next button -->
                                <button
                                    @click="changeSlide(1)"
                                    class="absolute top-1/2 right-3 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-black/50 text-white shadow-lg backdrop-blur-sm transition hover:bg-black/75"
                                >
                                    <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2.5"
                                        viewBox="0 0 24 24"
                                    >
                                        <path d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>

                                <!-- Dot indicators -->
                                <div
                                    class="absolute bottom-4 left-1/2 flex -translate-x-1/2 gap-1.5"
                                    id="dotContainer"
                                ></div>
                            </div>

                            <!-- Thumbnail strip -->
                            <div
                                class="thumb-scroll mt-3 flex gap-2 overflow-x-auto pb-1"
                                id="thumbStrip"
                            ></div>
                        </div>

                        <!-- PRICE ROW -->
                        <div
                            class="mb-5 flex flex-wrap items-center gap-4 rounded-xl border border-gray-100 bg-white p-4 shadow-sm"
                        >
                            <div>
                                <div class="text-brand text-2xl font-black">
                                    {{ formattedPrice }}<span
                                        class="ml-1 text-sm font-semibold text-gray-500"
                                        >VNĐ</span
                                    >
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ pricePerSquareMeter }}
                                </div>
                            </div>
                            <div class="h-10 w-px bg-gray-200"></div>
                            <div>
                                <div class="text-lg font-bold text-gray-700">
                                    {{ formattedArea }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    Diện tích
                                </div>
                            </div>
                            <div class="h-10 w-px bg-gray-200"></div>
                            <div class="ml-auto flex gap-2">
                                <button
                                    class="hover:border-brand hover:text-brand rounded-lg border border-gray-200 p-2 transition"
                                >
                                    <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                                        />
                                    </svg>
                                </button>
                                <button
                                    class="hover:border-brand hover:text-brand rounded-lg border border-gray-200 p-2 transition"
                                >
                                    <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>



                        <!-- MÔ TẢ -->
                        <section
                            class="mb-5 rounded-xl border border-gray-100 bg-white p-5 shadow-sm"
                        >
                            <h2 class="text-navy mb-3 text-base font-bold">
                                Thông tin mô tả
                            </h2>
                            <div
                                class="space-y-2 text-sm leading-relaxed text-gray-700"
                                v-html="postDescription"
                            ></div>
                        </section>
                        <!-- BẢN ĐỒ -->
                        <section
                            class="mb-5 rounded-xl border border-gray-100 bg-white p-5 shadow-sm"
                        >
                            <h2 class="text-navy mb-3 text-base font-bold">
                                Xem trên bản đồ
                            </h2>
                            <div
                                class="overflow-hidden rounded-xl border border-slate-200 bg-slate-50"
                            >
                                <div class="h-72 w-full bg-slate-100 sm:h-80 lg:h-96">
                                    <div ref="mapContainerRef" class="h-full w-full"></div>
                                </div>
                            </div>
                            <p class="mt-2 text-xs text-slate-500">Tọa độ: {{ props.post?.location || 'Đang cập nhật' }}</p>
                            <div
                                class="mt-3 grid grid-cols-4 gap-3 text-xs text-gray-600"
                            >
                                <div class="text-center">
                                    <div class="font-semibold text-gray-800">
                                        {{ postedDate }}
                                    </div>
                                    <div class="text-gray-400">Ngày đăng</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-semibold text-gray-800">
                                        {{ expiryDate }}
                                    </div>
                                    <div class="text-gray-400">Hết hạn</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-semibold text-gray-800">
                                        {{ listingType }}
                                    </div>
                                    <div class="text-gray-400">Loại tin</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-semibold text-gray-800">
                                        {{ postCode }}
                                    </div>
                                    <div class="text-gray-400">Mã tin</div>
                                </div>
                            </div>
                        </section>

                        <!-- GỢI Ý BĐS -->
                        <section class="mb-5">
                            <h2 class="text-navy mb-3 text-base font-bold">
                                Bất động sản dành cho bạn
                            </h2>
                            <div
                                class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3"
                            >
                                <a
                                    v-for="item in relatedPosts"
                                    :key="item.id"
                                    :href="`/post-detail/${item.id}`"
                                    class="group block cursor-pointer overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm transition hover:shadow-md"
                                >
                                    <div class="relative overflow-hidden">
                                        <img
                                            :src="item.img || 'https://images.unsplash.com/photo-1502005229762-cf1b2da7c5d6?w=400&q=80'"
                                            alt=""
                                            class="h-36 w-full object-cover transition duration-300 group-hover:scale-105"
                                        />
                                        <span
                                            class="tag absolute top-2 left-2 bg-red-100 text-red-600"
                                            >{{ listingType }}</span
                                        >
                                    </div>
                                    <div class="p-3">
                                        <div class="text-brand text-sm font-bold">
                                            {{ jam_read_num_forvietnamese(Number(item.price || 0)) }}
                                        </div>
                                        <p class="mt-0.5 line-clamp-2 text-xs leading-snug text-gray-700">
                                            {{ item.title }}
                                        </p>
                                        <div class="mt-1 flex items-center gap-1 text-xs text-gray-400">
                                            <svg
                                                class="h-3 w-3"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            {{ item.address }}
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </section>

                        <section class="mb-5">
                            <h2 class="text-navy mb-3 text-base font-bold">
                                Bất động sản nổi bật
                            </h2>
                            <div
                                class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3"
                            >
                                <a
                                    v-for="item in featuredPosts"
                                    :key="item.id"
                                    :href="`/post-detail/${item.id}`"
                                    class="group block cursor-pointer overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm transition hover:shadow-md"
                                >
                                    <div class="relative overflow-hidden">
                                        <img
                                            :src="item.img || 'https://images.unsplash.com/photo-1502005229762-cf1b2da7c5d6?w=400&q=80'"
                                            alt=""
                                            class="h-36 w-full object-cover transition duration-300 group-hover:scale-105"
                                        />
                                        <span
                                            class="tag absolute top-2 left-2 bg-orange-100 text-orange-700"
                                            >VIP/SVIP</span
                                        >
                                    </div>
                                    <div class="p-3">
                                        <div class="text-brand text-sm font-bold">
                                            {{ jam_read_num_forvietnamese(Number(item.price || 0)) }}
                                        </div>
                                        <p class="mt-0.5 line-clamp-2 text-xs leading-snug text-gray-700">
                                            {{ item.title }}
                                        </p>
                                        <div class="mt-1 flex items-center gap-1 text-xs text-gray-400">
                                            <svg
                                                class="h-3 w-3"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            {{ item.address }}
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </section>
                    </div>

                    <!-- RIGHT SIDEBAR -->
                    <aside class="hidden w-80 shrink-0 lg:block">
                        <!-- AGENT CARD -->
                        <div
                            class="sticky top-20 mb-4 rounded-xl border border-gray-100 bg-white p-5 shadow-sm"
                        >
                            <div class="mb-4 flex items-center gap-3">
                                <div class="relative">
                                    <img
                                        :src="sellerAvatar"
                                        alt="Agent"
                                        class="ring-brand/20 h-14 w-14 rounded-full object-cover ring-2"
                                    />
                                    <span
                                        class="pulse-dot absolute right-0 bottom-0 h-3.5 w-3.5 rounded-full border-2 border-white bg-green-400"
                                    ></span>
                                </div>
                                <div>
                                    <div class="text-navy text-sm font-bold">
                                        {{ sellerName }}
                                    </div>
                                    <div class="mt-0.5 text-xs text-gray-400">
                                        Chuyên nghiệp
                                    </div>
                                    <div
                                        class="mt-1 flex items-center gap-3 text-xs text-gray-500"
                                    >
                                        <span>📋 {{ sellerPostCount }} tin đăng</span>
                                    </div>
                                </div>
                            </div>
                            <a
                                :href="callablePhone"
                                class="bg-brand hover:bg-brand-dark mb-2 flex w-full items-center justify-center gap-2 rounded-xl py-3 text-sm font-semibold text-white transition"
                                :class="{ 'pointer-events-none opacity-60': !hasCallablePhone }"
                            >
                                <svg
                                    class="h-4 w-4"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"
                                    />
                                </svg>
                                Gọi {{ displayPhone }}
                            </a>
                            <a
                                :href="zaloLink"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="flex w-full items-center justify-center gap-2 rounded-xl bg-green-500 py-2.5 text-sm font-semibold text-white transition hover:bg-green-600"
                                :class="{ 'pointer-events-none opacity-60': !hasZaloLink }"
                            >
                                <span class="inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-white/15">
                                    <img
                                        src="https://upload.wikimedia.org/wikipedia/commons/9/91/Icon_of_Zalo.svg"
                                        alt="Zalo"
                                        class="h-4 w-4 object-contain"
                                    />
                                </span>
                                Chat qua Zalo
                            </a>
                        </div>

                        <!-- RELATED SIDEBAR LINKS -->
                        <div
                            class="mb-4 rounded-xl border border-gray-100 bg-white p-4 shadow-sm"
                        >
                            <div
                                class="text-navy mb-3 text-xs font-bold tracking-wide uppercase"
                            >
                                Bất động sản nổi bật
                            </div>
                            <ul class="space-y-1.5 text-xs text-gray-600">
                                <li v-for="item in featuredPosts.slice(0, 5)" :key="item.id">
                                    <a
                                        :href="`/post-detail/${item.id}`"
                                        class="hover:text-brand block py-0.5 transition"
                                        >{{ item.title }}</a
                                    >
                                </li>
                            </ul>
                        </div>
                    </aside>
                </div>
            </main>

            <!-- STICKY MOBILE CTA -->
            <div
                class="fixed right-0 bottom-0 left-0 z-50 flex gap-2 border-t border-gray-200 bg-white p-3 shadow-lg lg:hidden"
            >
                <a
                    :href="callablePhone"
                    class="bg-brand hover:bg-brand-dark flex flex-1 items-center justify-center gap-2 rounded-xl py-3 text-sm font-semibold text-white transition"
                    :class="{ 'pointer-events-none opacity-60': !hasCallablePhone }"
                >
                    <svg
                        class="h-4 w-4"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                    >
                        <path
                            d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"
                        />
                    </svg>
                    Gọi {{ displayPhone }}
                </a>
                <a
                    :href="zaloLink"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-green-500 py-3 text-sm font-semibold text-white transition hover:bg-green-600"
                    :class="{ 'pointer-events-none opacity-60': !hasZaloLink }"
                >
                    <span class="inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-md bg-white/15">
                        <img
                            src="https://upload.wikimedia.org/wikipedia/commons/9/91/Icon_of_Zalo.svg"
                            alt="Zalo"
                            class="h-4 w-4 object-contain"
                        />
                    </span>
                    Chat Zalo
                </a>
            </div>
        </body>
    </ClientLayout>
</template>
<style scoped>
/* Breadcrumb chevron */
.breadcrumb-sep::before {
    content: '›';
    margin: 0 6px;
    color: #9ca3af;
}
/* Custom scrollbar */
::-webkit-scrollbar {
    width: 6px;
}
::-webkit-scrollbar-track {
    background: #f1f1f1;
}
::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 3px;
}
/* Thumbnail scrollbar horizontal */
.thumb-scroll::-webkit-scrollbar {
    height: 4px;
}
.thumb-scroll::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 2px;
}
/* Badge pulse */
@keyframes pulse-dot {
    0%,
    100% {
        opacity: 1;
    }
    50% {
        opacity: 0.4;
    }
}
.pulse-dot {
    animation: pulse-dot 1.5s ease-in-out infinite;
}
/* Tag chip */
.tag {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 10px;
    border-radius: 9999px;
    font-size: 0.72rem;
    font-weight: 600;
}
/* Slide fade */
@keyframes slideFadeIn {
    from {
        opacity: 0;
        transform: scale(1.03);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
.slide-active {
    animation: slideFadeIn 0.35s ease;
}
/* Thumbnail active ring */
.thumb-active {
    outline: 2.5px solid #e8392a;
    outline-offset: 2px;
}
</style>
