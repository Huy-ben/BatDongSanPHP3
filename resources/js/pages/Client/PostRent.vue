<script setup>
import ClientLayout from '@/layouts/ClientLayout.vue';
</script>

<template>
    <ClientLayout>
        <div class="min-h-screen bg-[#f8f9fa] text-gray-900 antialiased">
            <header class="sticky top-0 z-50 border-b border-gray-200/60 bg-white/80 py-4 backdrop-blur-md shadow-sm">
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
                            class="flex items-center justify-center gap-2 rounded-lg bg-[#ff9c22] py-2.5 text-xs font-bold tracking-wide text-white uppercase shadow-lg shadow-orange-200 transition duration-300 hover:bg-orange-600 active:scale-95"
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
                                    >850 Kết quả</span
                                >
                            </div>

                            <div
                                class="group flex cursor-pointer flex-col overflow-hidden rounded-xl border border-gray-200 bg-white transition-all duration-300 hover:border-[#ff9c22]/50 hover:shadow-xl md:flex-row"
                            >
                                <div
                                    class="relative h-52 shrink-0 overflow-hidden md:w-80"
                                >
                                    <img
                                        src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800"
                                        class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                    />
                                    <span
                                        class="absolute top-3 left-3 rounded bg-[#ff9c22] px-2 py-1 text-[10px] font-bold text-white uppercase shadow-md"
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
                                            Căn hộ dịch vụ Landmark 81 - Tầng
                                            cao view thành phố
                                        </h3>
                                        <div
                                            class="mt-2.5 flex items-center gap-4"
                                        >
                                            <span
                                                class="text-xl font-black text-[#ff9c22] italic"
                                                >25 Triệu/tháng</span
                                            >
                                            <span
                                                class="rounded-full bg-gray-100 px-3 py-1 text-xs font-bold text-gray-900"
                                                >55 m2</span
                                            >
                                            <span
                                                class="text-[10px] font-bold tracking-tight text-gray-500 uppercase"
                                            >
                                                <i
                                                    class="fa-solid fa-location-dot mr-1"
                                                />
                                                Bình Thạnh
                                            </span>
                                        </div>
                                        <p
                                            class="mt-3 line-clamp-2 text-xs leading-relaxed text-gray-500"
                                        >
                                            Căn hộ studio đầy đủ tiện nghi,
                                            thiết kế hiện đại, chỉ việc xách
                                            vali vào ở. Miễn phí phí quản lý,
                                            thẻ cư dân sử dụng hồ bơi và gym cao
                                            cấp...
                                        </p>
                                    </div>
                                    <div
                                        class="mt-4 flex items-center justify-between border-t border-gray-100 pt-3"
                                    >
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-9 w-9 items-center justify-center rounded-full bg-orange-100 text-xs font-bold text-[#ff9c22] shadow-sm"
                                            >
                                                QT
                                            </div>
                                            <div>
                                                <p
                                                    class="text-[11px] leading-none font-bold text-gray-800 uppercase"
                                                >
                                                    Quốc Toàn
                                                </p>
                                                <p
                                                    class="mt-1 text-[10px] text-gray-400"
                                                >
                                                    Đăng hôm nay
                                                </p>
                                            </div>
                                        </div>
                                        <a
                                            href="tel:0909999888"
                                            class="z-10 flex items-center gap-2 rounded-lg border border-[#ff9c22] px-4 py-2 text-xs font-bold text-[#ff9c22] uppercase transition duration-300 hover:bg-[#ff9c22] hover:text-white"
                                        >
                                            <i
                                                class="fa-solid fa-phone-volume"
                                            />
                                            Gọi ngay
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
                                    class="h-9 w-9 rounded-lg bg-[#ff9c22] text-sm font-bold text-white shadow-md shadow-orange-200"
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
                        <div class="sticky top-20">
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
                                class="group mb-4 cursor-pointer overflow-hidden rounded-lg border border-gray-200 bg-white transition duration-300 hover:shadow-lg"
                            >
                                <div class="h-28 overflow-hidden bg-gray-200">
                                    <img
                                        src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=400"
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
                                        Giá thuê căn hộ tăng 20% năm 2026
                                    </h4>
                                    <p class="text-[11px] text-gray-500">
                                        2 ngày trước
                                    </p>
                                </div>
                            </article>

                            <article
                                class="group mb-4 cursor-pointer overflow-hidden rounded-lg border border-gray-200 bg-white transition duration-300 hover:shadow-lg"
                            >
                                <div class="h-28 overflow-hidden bg-gray-200">
                                    <img
                                        src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=400"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-110"
                                    />
                                </div>
                                <div class="p-3">
                                    <p
                                        class="mb-2 text-[10px] font-bold tracking-wider text-[#ff9c22] uppercase"
                                    >
                                        Kinh Doanh
                                    </p>
                                    <h4
                                        class="mb-2 line-clamp-2 text-sm font-bold text-gray-800 transition group-hover:text-[#ff9c22]"
                                    >
                                        Cơ hội cho thuê dự án hạng A
                                    </h4>
                                    <p class="text-[11px] text-gray-500">
                                        4 ngày trước
                                    </p>
                                </div>
                            </article>

                            <article
                                class="group mb-4 cursor-pointer overflow-hidden rounded-lg border border-gray-200 bg-white transition duration-300 hover:shadow-lg"
                            >
                                <div class="h-28 overflow-hidden bg-gray-200">
                                    <img
                                        src="https://images.unsplash.com/photo-1554224311-beee415c15c9?w=400"
                                        class="h-full w-full object-cover transition duration-500 group-hover:scale-110"
                                    />
                                </div>
                                <div class="p-3">
                                    <p
                                        class="mb-2 text-[10px] font-bold tracking-wider text-[#ff9c22] uppercase"
                                    >
                                        Tư Vấn
                                    </p>
                                    <h4
                                        class="mb-2 line-clamp-2 text-sm font-bold text-gray-800 transition group-hover:text-[#ff9c22]"
                                    >
                                        Hướng dẫn cho thuê nhà đạt hiệu quả cao
                                    </h4>
                                    <p class="text-[11px] text-gray-500">
                                        1 tuần trước
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
