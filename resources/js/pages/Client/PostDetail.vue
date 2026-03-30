<script setup lang="ts">
import Footer from '@/components/btbcomponents/Footer.vue';
import ClientLayout from '@/layouts/ClientLayout.vue';
import { onMounted, onUnmounted } from 'vue';

const images = [
    {
        src: 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1200&q=85',
        label: 'Phòng khách',
    },
    {
        src: 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=1200&q=85',
        label: 'Phòng ngủ master',
    },
    {
        src: 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=1200&q=85',
        label: 'Toàn cảnh ban ngày',
    },
    {
        src: 'https://images.unsplash.com/photo-1502005229762-cf1b2da7c5d6?w=1200&q=85',
        label: 'Phòng bếp',
    },
    {
        src: 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=1200&q=85',
        label: 'Hồ bơi riêng',
    },
    {
        src: 'https://images.unsplash.com/photo-1560185893-a55cbc8c57e8?w=1200&q=85',
        label: 'Phòng ngủ 2',
    },
    {
        src: 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=1200&q=85',
        label: 'Sân vườn thượng tầng',
    },
    {
        src: 'https://images.unsplash.com/photo-1484154218962-a197022b5858?w=1200&q=85',
        label: 'Phòng tắm',
    },
    {
        src: 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=1200&q=85',
        label: 'View ban công',
    },
    {
        src: 'https://images.unsplash.com/photo-1583608205776-bfd35f0d9f83?w=1200&q=85',
        label: 'Phòng gym',
    },
    {
        src: 'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?w=1200&q=85',
        label: 'Sảnh đón',
    },
    {
        src: 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?w=1200&q=85',
        label: 'Khu tiện ích',
    },
];

let current = 0;
let mainSlide: HTMLImageElement | null = null;
let counter: HTMLElement | null = null;
let dotContainer: HTMLElement | null = null;
let thumbStrip: HTMLElement | null = null;

function goTo(idx: number) {
    if (!mainSlide || !counter) {
        return;
    }

    document
        .getElementById(`thumb-${current}`)
        ?.classList.remove('thumb-active');
    document.getElementById(`dot-${current}`)?.classList.remove('bg-white');
    document.getElementById(`dot-${current}`)?.classList.add('bg-white/50');
    current = (idx + images.length) % images.length;
    mainSlide.classList.remove('slide-active');
    void mainSlide.offsetWidth; // reflow
    mainSlide.src = images[current].src;
    mainSlide.classList.add('slide-active');
    counter.textContent = `${current + 1} / ${images.length}`;
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
    images.forEach((img, i) => {
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
    document.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
    document.removeEventListener('keydown', handleKeydown);
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
                    <a href="#" class="hover:text-brand">Bán</a>
                    <span class="breadcrumb-sep"></span>
                    <a href="#" class="hover:text-brand">Căn hộ chung cư</a>
                    <span class="breadcrumb-sep"></span>
                    <a href="#" class="hover:text-brand">Fiato Uptown</a>
                    <span class="breadcrumb-sep"></span>
                    <span class="font-medium text-gray-700"
                        >Penthouse thông 2 tầng có bơi riêng</span
                    >
                </nav>

                <div class="flex gap-6">
                    <!-- LEFT COLUMN -->
                    <div class="min-w-0 flex-1">
                        <!-- TITLE -->
                        <h1
                            class="text-navy mb-1 text-xl leading-snug font-bold md:text-2xl"
                        >
                            Penthouse thông 2 tầng có hồ bơi riêng, có suất đỗ ô
                            tô miễn phí dự án Fiato Uptown Thủ Đức
                        </h1>
                        <div
                            class="mb-4 flex items-center gap-2 text-sm text-gray-500"
                        >
                            <svg
                                class="text-brand h-4 w-4 flex-shrink-0"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <span
                                >95 Nguyễn Văn Tăng, Phường Tăm Phú, Thành phố
                                Thủ Đức, Hồ Chí Minh</span
                            >
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
                                    src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=1200&q=85"
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
                                    50 tỷ<span
                                        class="ml-1 text-sm font-semibold text-gray-500"
                                        >VNĐ</span
                                    >
                                </div>
                                <div class="text-xs text-gray-400">
                                    ~161 tr/m²
                                </div>
                            </div>
                            <div class="h-10 w-px bg-gray-200"></div>
                            <div>
                                <div class="text-lg font-bold text-gray-700">
                                    310 m²
                                </div>
                                <div class="text-xs text-gray-400">
                                    Diện tích
                                </div>
                            </div>
                            <div class="h-10 w-px bg-gray-200"></div>
                            <div>
                                <div class="text-lg font-bold text-gray-700">
                                    4
                                </div>
                                <div class="text-xs text-gray-400">
                                    Phòng ngủ
                                </div>
                            </div>
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

                        <!-- PRICE ALERT BANNER -->
                        <div
                            class="mb-6 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm"
                        >
                            <span class="text-lg text-green-600">📊</span>
                            <span class="text-green-700"
                                ><b>53% căn hộ</b> tại đây đang rao bán trong
                                tầm giá này trong 1 năm qua</span
                            >
                            <a
                                href="#"
                                class="ml-auto text-xs font-semibold whitespace-nowrap text-green-700 hover:underline"
                                >Xem lịch sử giá ›</a
                            >
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
                            >
                                <p>
                                    Penthouse thông tầng có hồ bơi tại TP Thủ
                                    Đức trong khu compound Hưng Phú, tỉnh lộ
                                    Dương Hàng Bàng.
                                </p>
                                <p>
                                    ✓ Có hồ bơi riêng &nbsp; ✓ Diện tích sân
                                    vườn: 81m² &nbsp; ✓ Chỗ đỗ xe ô tô miễn phí
                                </p>
                                <p>
                                    Ngân hàng hỗ trợ vay tối đa <b>70%</b>, lãi
                                    suất ưu đãi <b>0%</b> trong 24 tháng –
                                    Vietcombank &amp; AB Bank.
                                </p>
                                <p>
                                    Hỗ trợ 3D/CG (25%, không giới hạn) –
                                    Eximbank.
                                </p>
                                <div class="mt-2">
                                    <p>
                                        <b>Vị trí:</b> Số 2 Thái Sơn 13, Nguyễn
                                        Oanh Bắc 2, cổ ng chuyển đổi sân bay Tân
                                        Sơn Nhất 45'.
                                    </p>
                                    <p class="mt-1">
                                        Tiện ích xung quanh: hồ bơi CLB, Sky
                                        Garden tầng thượng, gần Landmark 81.
                                        Công viên nội khu, cây xanh, Gym &amp;
                                        Yoga, phòng chức năng.
                                    </p>
                                </div>
                            </div>
                        </section>

                        <!-- ĐẶC ĐIỂM BẤT ĐỘNG SẢN -->
                        <section
                            class="mb-5 rounded-xl border border-gray-100 bg-white p-5 shadow-sm"
                        >
                            <h2 class="text-navy mb-4 text-base font-bold">
                                Đặc điểm bất động sản
                            </h2>
                            <div
                                class="grid grid-cols-2 gap-4 text-sm md:grid-cols-3"
                            >
                                <div class="flex items-center gap-3">
                                    <div
                                        class="text-brand flex h-9 w-9 items-center justify-center rounded-lg bg-red-50"
                                    >
                                        💰
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-400">
                                            Khoảng giá
                                        </div>
                                        <div class="font-semibold">
                                            50 tỷ/m²
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50"
                                    >
                                        🏊
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-400">
                                            Số phòng tắm &amp; vệ sinh
                                        </div>
                                        <div class="font-semibold">3 phòng</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-yellow-50"
                                    >
                                        📐
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-400">
                                            Diện tích
                                        </div>
                                        <div class="font-semibold">310 m²</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-orange-50"
                                    >
                                        🧭
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-400">
                                            Hướng nhà
                                        </div>
                                        <div class="font-semibold">
                                            Đông – Nam
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-green-50"
                                    >
                                        🛏
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-400">
                                            Số phòng ngủ
                                        </div>
                                        <div class="font-semibold">4 phòng</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-9 w-9 items-center justify-center rounded-lg bg-purple-50"
                                    >
                                        🏢
                                    </div>
                                    <div>
                                        <div class="text-xs text-gray-400">
                                            Nội thất
                                        </div>
                                        <div class="font-semibold">Đầy đủ</div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- DỰ ÁN -->
                        <section
                            class="mb-5 rounded-xl border border-gray-100 bg-white p-5 shadow-sm"
                        >
                            <div class="mb-3 flex items-center justify-between">
                                <h2 class="text-navy text-base font-bold">
                                    Thông tin dự án
                                </h2>
                                <a
                                    href="#"
                                    class="text-brand text-xs font-semibold hover:underline"
                                    >Xem tất cả đăng bán ›</a
                                >
                            </div>
                            <div class="flex items-center gap-4">
                                <img
                                    src="https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=120&q=80"
                                    alt="Fiato Uptown"
                                    class="h-16 w-20 flex-shrink-0 rounded-lg object-cover"
                                />
                                <div class="text-sm">
                                    <div class="text-navy text-base font-bold">
                                        Fiato Uptown
                                    </div>
                                    <div
                                        class="mt-1 flex items-center gap-4 text-xs text-gray-500"
                                    >
                                        <span>🏗 72.097 m²</span>
                                        <span>🏠 464 căn</span>
                                        <span>🏢 8 tầng</span>
                                    </div>
                                    <div class="mt-1 text-xs text-gray-400">
                                        Công ty phân hưng Phú Inversit
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- BẢN ĐỒ -->
                        <section
                            class="mb-5 rounded-xl border border-gray-100 bg-white p-5 shadow-sm"
                        >
                            <h2 class="text-navy mb-3 text-base font-bold">
                                Xem trên bản đồ
                            </h2>
                            <div
                                class="flex h-48 items-center justify-center overflow-hidden rounded-xl border border-blue-100 bg-blue-50"
                            >
                                <div class="text-center text-gray-400">
                                    <div class="mb-2 text-4xl">🗺</div>
                                    <p class="text-sm">
                                        95 Nguyễn Văn Tăng, Phường Tăm Phú, TP.
                                        Thủ Đức
                                    </p>
                                </div>
                            </div>
                            <div
                                class="mt-3 grid grid-cols-4 gap-3 text-xs text-gray-600"
                            >
                                <div class="text-center">
                                    <div class="font-semibold text-gray-800">
                                        10/03/2026
                                    </div>
                                    <div class="text-gray-400">Ngày đăng</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-semibold text-gray-800">
                                        14/04/2026
                                    </div>
                                    <div class="text-gray-400">Hết hạn</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-semibold text-gray-800">
                                        Tin thường
                                    </div>
                                    <div class="text-gray-400">Loại tin</div>
                                </div>
                                <div class="text-center">
                                    <div class="font-semibold text-gray-800">
                                        43187931
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
                                <!-- card 1 -->
                                <div
                                    class="group cursor-pointer overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm transition hover:shadow-md"
                                >
                                    <div class="relative overflow-hidden">
                                        <img
                                            src="https://images.unsplash.com/photo-1502005229762-cf1b2da7c5d6?w=400&q=80"
                                            alt=""
                                            class="h-36 w-full object-cover transition duration-300 group-hover:scale-105"
                                        />
                                        <span
                                            class="tag absolute top-2 left-2 bg-red-100 text-red-600"
                                            >Bán</span
                                        >
                                    </div>
                                    <div class="p-3">
                                        <div
                                            class="text-brand text-sm font-bold"
                                        >
                                            6.8 tỷ
                                        </div>
                                        <p
                                            class="mt-0.5 line-clamp-2 text-xs leading-snug text-gray-700"
                                        >
                                            Căn rộng không số có đầy đủ nội
                                            thất...
                                        </p>
                                        <div
                                            class="mt-1 flex items-center gap-1 text-xs text-gray-400"
                                        >
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
                                            TP Thủ Đức
                                        </div>
                                    </div>
                                </div>
                                <!-- card 2 -->
                                <div
                                    class="group cursor-pointer overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm transition hover:shadow-md"
                                >
                                    <div class="relative overflow-hidden">
                                        <img
                                            src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&q=80"
                                            alt=""
                                            class="h-36 w-full object-cover transition duration-300 group-hover:scale-105"
                                        />
                                        <span
                                            class="tag absolute top-2 left-2 bg-red-100 text-red-600"
                                            >Bán</span
                                        >
                                    </div>
                                    <div class="p-3">
                                        <div
                                            class="text-brand text-sm font-bold"
                                        >
                                            6.5 tỷ
                                        </div>
                                        <p
                                            class="mt-0.5 line-clamp-2 text-xs leading-snug text-gray-700"
                                        >
                                            Bán Duplex căn hộ Fiato Uptown diện
                                            tích...
                                        </p>
                                        <div
                                            class="mt-1 flex items-center gap-1 text-xs text-gray-400"
                                        >
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
                                            TP Thủ Đức
                                        </div>
                                    </div>
                                </div>
                                <!-- card 3 -->
                                <div
                                    class="group cursor-pointer overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm transition hover:shadow-md"
                                >
                                    <div class="relative overflow-hidden">
                                        <img
                                            src="https://images.unsplash.com/photo-1560185893-a55cbc8c57e8?w=400&q=80"
                                            alt=""
                                            class="h-36 w-full object-cover transition duration-300 group-hover:scale-105"
                                        />
                                        <span
                                            class="tag absolute top-2 left-2 bg-orange-100 text-orange-600"
                                            >Hot</span
                                        >
                                    </div>
                                    <div class="p-3">
                                        <div
                                            class="text-brand text-sm font-bold"
                                        >
                                            4.9 tỷ
                                        </div>
                                        <p
                                            class="mt-0.5 line-clamp-2 text-xs leading-snug text-gray-700"
                                        >
                                            Cập nhật giá Fiato Uptown 6/980...
                                        </p>
                                        <div
                                            class="mt-1 flex items-center gap-1 text-xs text-gray-400"
                                        >
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
                                            TP Thủ Đức
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <!-- RIGHT SIDEBAR -->
                    <aside class="hidden w-80 flex-shrink-0 lg:block">
                        <!-- AGENT CARD -->
                        <div
                            class="sticky top-20 mb-4 rounded-xl border border-gray-100 bg-white p-5 shadow-sm"
                        >
                            <div class="mb-4 flex items-center gap-3">
                                <div class="relative">
                                    <img
                                        src="https://i.pravatar.cc/56?img=47"
                                        alt="Agent"
                                        class="ring-brand/20 h-14 w-14 rounded-full object-cover ring-2"
                                    />
                                    <span
                                        class="pulse-dot absolute right-0 bottom-0 h-3.5 w-3.5 rounded-full border-2 border-white bg-green-400"
                                    ></span>
                                </div>
                                <div>
                                    <div class="text-navy text-sm font-bold">
                                        Nguyễn Phan Trinh
                                    </div>
                                    <div class="mt-0.5 text-xs text-gray-400">
                                        Chuyên nghiệp
                                    </div>
                                    <div
                                        class="mt-1 flex items-center gap-3 text-xs text-gray-500"
                                    >
                                        <span>⭐ 8 năm</span>
                                        <span>📋 11 tin đăng</span>
                                    </div>
                                </div>
                            </div>
                            <button
                                class="bg-brand hover:bg-brand-dark mb-2 flex w-full items-center justify-center gap-2 rounded-xl py-3 text-sm font-semibold text-white transition"
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
                                0912 572 *** – Hiện số
                            </button>
                            <button
                                class="flex w-full items-center justify-center gap-2 rounded-xl bg-green-500 py-2.5 text-sm font-semibold text-white transition hover:bg-green-600"
                            >
                                <svg
                                    class="h-4 w-4"
                                    viewBox="0 0 24 24"
                                    fill="currentColor"
                                >
                                    <path
                                        d="M17.498 14.382c-.301-.15-1.767-.867-2.04-.966-.273-.101-.473-.15-.673.15-.197.295-.771.964-.944 1.162-.175.195-.349.21-.646.075-.3-.15-1.263-.465-2.403-1.485-.888-.795-1.484-1.77-1.66-2.07-.174-.3-.019-.465.13-.615.136-.135.301-.345.451-.523.146-.181.194-.301.297-.496.1-.21.049-.375-.025-.524-.075-.15-.672-1.62-.922-2.206-.24-.584-.487-.51-.672-.51-.172-.015-.371-.015-.571-.015-.2 0-.523.074-.797.359-.273.3-1.045 1.02-1.045 2.475s1.07 2.865 1.219 3.075c.149.195 2.105 3.195 5.1 4.485.714.3 1.27.48 1.704.629.714.227 1.365.195 1.88.121.574-.091 1.767-.721 2.016-1.426.255-.705.255-1.29.18-1.425-.074-.135-.27-.21-.57-.345z"
                                    />
                                    <path
                                        d="M20.52 3.449C12.831-3.984.106 1.407.101 11.893c0 2.096.549 4.14 1.595 5.945L0 24l6.335-1.652a11.955 11.955 0 005.71 1.447h.005c7.987 0 14.09-6.49 14.09-14.036 0-3.756-1.461-7.277-4.12-9.31z"
                                    />
                                </svg>
                                Chat qua Zalo
                            </button>
                            <div class="mt-3 flex justify-center">
                                <a
                                    href="#"
                                    class="text-brand text-xs font-medium hover:underline"
                                    >Xem thêm tin ›</a
                                >
                            </div>
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
                                <li>
                                    <a
                                        href="#"
                                        class="hover:text-brand block py-0.5 transition"
                                        >Nhà đất Thành Long Thọ</a
                                    >
                                </li>
                                <li>
                                    <a
                                        href="#"
                                        class="hover:text-brand block py-0.5 transition"
                                        >Nhà mặt tiền sổ hồng chính chủ</a
                                    >
                                </li>
                                <li>
                                    <a
                                        href="#"
                                        class="hover:text-brand block py-0.5 transition"
                                        >Bán đất Liên Thuận</a
                                    >
                                </li>
                                <li>
                                    <a
                                        href="#"
                                        class="hover:text-brand block py-0.5 transition"
                                        >Nhà cần Đường đất</a
                                    >
                                </li>
                                <li>
                                    <a
                                        href="#"
                                        class="hover:text-brand block py-0.5 transition"
                                        >Mua nhà tại Tân Bình</a
                                    >
                                </li>
                            </ul>
                        </div>

                        <!-- MORTGAGE BANNER -->
                        <div
                            class="from-navy rounded-xl bg-gradient-to-br to-blue-700 p-4 text-white"
                        >
                            <div
                                class="mb-1 text-xs font-bold tracking-wide uppercase opacity-70"
                            >
                                Tổng hợp
                            </div>
                            <div class="text-lg leading-tight font-black">
                                Lãi suất vay<br />ưu đãi nhất 3.5%
                            </div>
                            <div class="mt-1 mb-3 text-xs opacity-70">
                                phí trả trước thời hạn
                            </div>
                            <button
                                class="text-navy w-full rounded-lg bg-white py-2 text-xs font-bold transition hover:bg-gray-100"
                            >
                                Tìm hiểu thêm
                            </button>
                        </div>
                    </aside>
                </div>
            </main>

            <!-- STICKY MOBILE CTA -->
            <div
                class="fixed right-0 bottom-0 left-0 z-50 flex gap-2 border-t border-gray-200 bg-white p-3 shadow-lg lg:hidden"
            >
                <button
                    class="bg-brand hover:bg-brand-dark flex flex-1 items-center justify-center gap-2 rounded-xl py-3 text-sm font-semibold text-white transition"
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
                    Gọi ngay
                </button>
                <button
                    class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-green-500 py-3 text-sm font-semibold text-white transition hover:bg-green-600"
                >
                    Chat Zalo
                </button>
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
