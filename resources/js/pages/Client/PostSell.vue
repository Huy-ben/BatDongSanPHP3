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

            <main class="container mx-auto max-w-6xl px-4 py-8 md:px-5">
                <div class="grid grid-cols-1 gap-8 lg:grid-cols-4">
                    <div class="lg:col-span-3">
                        <div class="mb-6 flex items-center justify-between border-b border-gray-200 pb-4">
                            <h2 class="text-xl font-black tracking-tight text-gray-900 uppercase italic">
                                Danh mục <span class="text-[#ff9c22]">Mua Bán</span>
                            </h2>
                            <span class="rounded-full bg-gray-100 px-3 py-1 text-[10px] font-bold text-gray-500 uppercase tracking-tighter">
                                1,245 Kết quả
                            </span>
                        </div>

                        <div class="space-y-6">
                            <div class="group flex cursor-pointer flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white transition-all duration-300 hover:border-[#ff9c22]/50 hover:shadow-2xl md:flex-row">
                                <div class="relative h-56 shrink-0 bg-gray-100 md:w-80 overflow-hidden">
                                    <div class="grid h-full grid-cols-2 grid-rows-2 gap-0.5">
                                        <div class="col-span-2 row-span-1 overflow-hidden">
                                            <img src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800" class="h-full w-full object-cover transition duration-700 group-hover:scale-110" />
                                        </div>
                                        <div class="overflow-hidden">
                                            <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=400" class="h-full w-full object-cover transition duration-700 group-hover:scale-110" />
                                        </div>
                                        <div class="relative overflow-hidden">
                                            <img src="https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=400" class="h-full w-full object-cover opacity-60 transition duration-700 group-hover:scale-110" />
                                            <div class="absolute inset-0 flex items-center justify-center bg-black/40 text-xs font-bold text-white">+4 ảnh</div>
                                        </div>
                                    </div>
                                    <span class="absolute top-3 left-3 rounded-md bg-red-600 px-2 py-1 text-[10px] font-bold text-white uppercase shadow-lg">Độc quyền</span>
                                </div>

                                <div class="flex flex-1 flex-col justify-between p-5">
                                    <div>
                                        <h3 class="line-clamp-2 text-lg font-bold text-gray-800 transition duration-300 group-hover:text-[#ff9c22] uppercase">
                                            Biệt thự đơn lập Sun Group - View trực diện biển
                                        </h3>
                                        <div class="mt-3 flex flex-wrap items-center gap-3">
                                            <span class="text-2xl font-black text-red-600 italic">45.5 Tỷ</span>
                                            <span class="flex items-center gap-1 rounded-md bg-gray-100 px-2 py-1 text-xs font-bold text-gray-600">
                                                <i class="fa-solid fa-ruler-combined"></i> 350 m²
                                            </span>
                                            <span class="text-[11px] font-bold text-gray-500 uppercase">
                                                <i class="fa-solid fa-location-dot text-[#ff9c22] mr-1"></i> Phú Quốc
                                            </span>
                                        </div>
                                        <p class="mt-3 line-clamp-2 text-sm leading-relaxed text-gray-500">
                                            Sản phẩm hiếm hoi có pháp lý sở hữu lâu dài. Thiết kế theo phong cách Địa Trung Hải, đầy đủ nội thất 5 sao cao cấp...
                                        </p>
                                    </div>
                                    
                                    <div class="mt-5 flex items-center justify-between border-t border-gray-50 pt-4">
                                        <div class="flex items-center gap-3">
                                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-linear-to-tr from-orange-400 to-[#ff9c22] text-[10px] font-bold text-white shadow-inner">QT</div>
                                            <div>
                                                <p class="text-[11px] font-bold text-gray-800 uppercase leading-none">Quốc Toàn</p>
                                                <p class="mt-1 text-[10px] text-gray-400">2 giờ trước</p>
                                            </div>
                                        </div>
                                        <a href="tel:0901234567" class="flex items-center gap-2 rounded-full border border-[#ff9c22]/20 bg-orange-50 px-4 py-2 text-xs font-bold text-[#ff9c22] transition hover:bg-[#ff9c22] hover:text-white">
                                            <i class="fa-solid fa-phone-volume"></i> 0901 234 ***
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-10 flex items-center justify-center gap-2">
                            <button class="flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 text-gray-400 transition hover:bg-white hover:text-[#ff9c22] hover:border-[#ff9c22]">
                                <i class="fa-solid fa-chevron-left"></i>
                            </button>
                            <button class="h-10 w-10 rounded-xl bg-[#ff9c22] font-bold text-white shadow-lg shadow-orange-200">1</button>
                            <button class="h-10 w-10 rounded-xl border border-gray-200 bg-white font-bold text-gray-600 transition hover:border-[#ff9c22] hover:text-[#ff9c22]">2</button>
                            <button class="flex h-10 w-10 items-center justify-center rounded-xl border border-gray-200 text-gray-400 transition hover:bg-white hover:text-[#ff9c22] hover:border-[#ff9c22]">
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>

                    <aside class="lg:col-span-1">
                        <div class="sticky top-28">
                            <div class="mb-5 flex items-center border-b border-gray-200 pb-3">
                                <h3 class="text-lg font-black tracking-wide text-gray-900 uppercase">
                                    <span class="text-[#ff9c22]">Tin tức</span> nổi bật
                                </h3>
                            </div>

                            <div class="space-y-4">
                                <article v-for="i in 3" :key="i" class="group cursor-pointer overflow-hidden rounded-xl border border-gray-100 bg-white p-2 transition hover:shadow-xl">
                                    <div class="h-24 overflow-hidden rounded-lg bg-gray-200">
                                        <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=400" class="h-full w-full object-cover transition duration-500 group-hover:scale-110" />
                                    </div>
                                    <div class="mt-2 p-1">
                                        <p class="text-[9px] font-bold text-[#ff9c22] uppercase">Thị trường</p>
                                        <h4 class="mt-1 line-clamp-2 text-[13px] font-bold text-gray-800 group-hover:text-[#ff9c22]">Giá bất động sản tăng 15% trong năm 2026</h4>
                                    </div>
                                </article>
                            </div>

                            <button class="mt-5 w-full rounded-xl bg-gray-900 py-3 text-[10px] font-bold tracking-widest text-white uppercase transition hover:bg-[#ff9c22]">
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