<script setup>
import { computed, ref } from 'vue';
import ClientLayout from '@/layouts/ClientLayout.vue';

const activePage = ref('ban');

const baseTabClasses =
    'py-3 text-xs uppercase tracking-wider transition-all duration-300 border-b-[3px] focus:outline-none';

const tabBanClass = computed(() =>
    activePage.value === 'ban'
        ? `${baseTabClasses} font-extrabold border-primary text-primary hover:text-primaryDark`
        : `${baseTabClasses} font-bold border-transparent text-gray-400 hover:text-primaryDark`,
);

const tabThueClass = computed(() =>
    activePage.value === 'thue'
        ? `${baseTabClasses} font-extrabold border-blue-600 text-blue-600 hover:text-blue-800`
        : `${baseTabClasses} font-bold border-transparent text-gray-400 hover:text-blue-600`,
);
</script>

<template>
    <ClientLayout>
        <div class="overflow-y-scroll bg-[#f8f9fa] text-gray-900 antialiased">
            <header class="sticky top-0 z-50 bg-white pt-4 shadow-sm">
                <div class="container mx-auto max-w-6xl px-4 md:px-5">
                    <div
                        class="mb-4 grid grid-cols-1 items-end gap-4 md:grid-cols-2 lg:grid-cols-4"
                    >
                        <div>
                            <label
                                class="mb-2 block text-[11px] font-black tracking-widest text-gray-400 uppercase"
                            >
                                Khu vực / Tên đường
                            </label>
                            <input
                                type="text"
                                placeholder="Bình Thạnh, Quận 1..."
                                class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5 text-xs transition outline-none focus:border-primary"
                            />
                        </div>

                        <div>
                            <label
                                class="mb-2 block text-[11px] font-black tracking-widest text-gray-400 uppercase"
                            >
                                Diện tích (m2)
                            </label>
                            <div class="flex items-center gap-2">
                                <input
                                    type="number"
                                    placeholder="Từ"
                                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5 text-center text-xs transition outline-none focus:border-primary"
                                />
                                <span class="font-bold text-gray-400">-</span>
                                <input
                                    type="number"
                                    placeholder="Đến"
                                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5 text-center text-xs transition outline-none focus:border-primary"
                                />
                            </div>
                        </div>

                        <div>
                            <label
                                class="mb-2 block text-[11px] font-black tracking-widest text-gray-400 uppercase"
                            >
                                Mức giá
                            </label>
                            <div class="flex items-center gap-2">
                                <input
                                    type="number"
                                    placeholder="Từ"
                                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5 text-center text-xs transition outline-none focus:border-primary"
                                />
                                <span class="font-bold text-gray-400">-</span>
                                <input
                                    type="number"
                                    placeholder="Đến"
                                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2.5 text-center text-xs transition outline-none focus:border-primary"
                                />
                            </div>
                        </div>

                        <button
                            class="hover:bg-primaryDark w-full rounded-lg bg-primary py-2.5 text-xs font-bold tracking-wide text-white uppercase shadow-md transition duration-300"
                        >
                            Áp dụng lọc
                        </button>
                    </div>

                    <div class="flex gap-5 border-t border-gray-100">
                        <button
                            :class="tabBanClass"
                            @click="activePage = 'ban'"
                        >
                            Nhà đất bán
                        </button>
                        <button
                            :class="tabThueClass"
                            @click="activePage = 'thue'"
                        >
                            Nhà đất cho thuê
                        </button>
                    </div>
                </div>
            </header>

            <main
                class="container mx-auto min-h-120 max-w-6xl px-4 py-7 md:px-5"
            >
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
                    <div class="lg:col-span-3">
                        <div
                            :class="
                                activePage === 'ban'
                                    ? 'page-active space-y-4'
                                    : 'page-hidden space-y-4'
                            "
                        >
                            <div
                                class="mb-5 flex items-center justify-between border-b border-gray-200 pb-3"
                            >
                                <h2
                                    class="text-xl leading-none font-extrabold tracking-tight text-gray-900 uppercase italic"
                                >
                                    Danh mục
                                    <span class="text-primary">Mua Bán</span>
                                </h2>
                                <span
                                    class="text-[11px] font-bold tracking-widest text-gray-500 uppercase"
                                    >1,245 Kết quả</span
                                >
                            </div>

                            <div
                                class="group flex cursor-pointer flex-col overflow-hidden rounded-xl border border-gray-200 bg-white transition-all duration-300 hover:border-primary hover:shadow-xl md:flex-row"
                            >
                                <div
                                    class="relative grid h-52 shrink-0 grid-cols-2 grid-rows-2 gap-0.5 bg-gray-100 md:w-80"
                                >
                                    <div
                                        class="col-span-2 row-span-1 overflow-hidden"
                                    >
                                        <img
                                            src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800"
                                            class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                        />
                                    </div>
                                    <div class="overflow-hidden">
                                        <img
                                            src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=400"
                                            class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                        />
                                    </div>
                                    <div
                                        class="relative overflow-hidden bg-black/40"
                                    >
                                        <img
                                            src="https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=400"
                                            class="h-full w-full object-cover opacity-60 transition duration-700 group-hover:scale-105"
                                        />
                                        <span
                                            class="absolute inset-0 flex items-center justify-center text-xs font-bold text-white"
                                        >
                                            +4 ảnh
                                        </span>
                                    </div>
                                    <span
                                        class="absolute top-3 left-3 rounded bg-red-600 px-2 py-1 text-[10px] font-bold text-white uppercase shadow-md"
                                    >
                                        Độc quyền
                                    </span>
                                </div>

                                <div
                                    class="flex flex-1 flex-col justify-between p-4"
                                >
                                    <div>
                                        <h3
                                            class="group-hover:text-primaryDark line-clamp-1 text-base font-bold text-gray-800 uppercase transition duration-300"
                                        >
                                            Biệt thự đơn lập Sun Group - View
                                            trực diện biển
                                        </h3>
                                        <div
                                            class="mt-2.5 flex items-center gap-4"
                                        >
                                            <span
                                                class="text-xl font-black text-red-600 italic"
                                                >45.5 Tỷ</span
                                            >
                                            <span
                                                class="rounded-full bg-gray-100 px-3 py-1 text-xs font-bold text-gray-900"
                                            >
                                                350 m2
                                            </span>
                                            <span
                                                class="text-[10px] font-bold tracking-tight text-gray-500 uppercase"
                                            >
                                                <i
                                                    class="fa-solid fa-location-dot mr-1"
                                                />
                                                Phú Quốc
                                            </span>
                                        </div>
                                        <p
                                            class="mt-3 line-clamp-2 text-xs leading-relaxed text-gray-500"
                                        >
                                            Sản phẩm hiếm hoi có pháp lý sở hữu
                                            lâu dài. Thiết kế theo phong cách
                                            Địa Trung Hải, đầy đủ nội thất 5 sao
                                            cao cấp. Thích hợp mua để ở hoặc
                                            khai thác nghỉ dưỡng...
                                        </p>
                                    </div>
                                    <div
                                        class="mt-4 flex items-center justify-between border-t border-gray-100 pt-3"
                                    >
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-9 w-9 items-center justify-center rounded-full bg-primary text-xs font-bold text-white shadow"
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
                                                    Đăng 2 giờ trước
                                                </p>
                                            </div>
                                        </div>
                                        <a
                                            href="tel:0901234567"
                                            class="z-10 flex items-center gap-2 rounded-lg border border-orange-200 bg-orange-50 px-4 py-2 text-xs font-bold text-primary transition duration-300 hover:bg-primary hover:text-white"
                                        >
                                            <i class="fa-solid fa-phone" /> 0901
                                            234 ***
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
                                    class="h-9 w-9 rounded-lg bg-primary text-sm font-bold text-white shadow-md shadow-orange-200"
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

                        <div
                            :class="
                                activePage === 'thue'
                                    ? 'page-active space-y-4'
                                    : 'page-hidden space-y-4'
                            "
                        >
                            <div
                                class="mb-5 flex items-center justify-between border-b border-gray-200 pb-3"
                            >
                                <h2
                                    class="text-xl leading-none font-extrabold tracking-tight text-gray-900 uppercase italic"
                                >
                                    Danh mục
                                    <span class="text-blue-600">Cho Thuê</span>
                                </h2>
                                <span
                                    class="text-[11px] font-bold tracking-widest text-gray-500 uppercase"
                                    >850 Kết quả</span
                                >
                            </div>

                            <div
                                class="group flex cursor-pointer flex-col overflow-hidden rounded-xl border border-gray-200 bg-white transition-all duration-300 hover:border-blue-500 hover:shadow-xl md:flex-row"
                            >
                                <div
                                    class="relative h-52 shrink-0 overflow-hidden md:w-80"
                                >
                                    <img
                                        src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800"
                                        class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                    />
                                    <span
                                        class="absolute top-3 left-3 rounded bg-blue-600 px-2 py-1 text-[10px] font-bold text-white uppercase shadow-md"
                                    >
                                        Cho thuê nhanh
                                    </span>
                                </div>

                                <div
                                    class="flex flex-1 flex-col justify-between p-4"
                                >
                                    <div>
                                        <h3
                                            class="line-clamp-1 text-base font-bold text-gray-800 uppercase transition duration-300 group-hover:text-blue-600"
                                        >
                                            Căn hộ dịch vụ Landmark 81 - Tầng
                                            cao view thành phố
                                        </h3>
                                        <div
                                            class="mt-2.5 flex items-center gap-4"
                                        >
                                            <span
                                                class="text-xl font-black text-blue-600 italic"
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
                                                class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-600 shadow-sm"
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
                                            class="z-10 flex items-center gap-2 rounded-lg border border-blue-600 px-4 py-2 text-xs font-bold text-blue-600 uppercase transition duration-300 hover:bg-blue-600 hover:text-white"
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
                                    class="h-9 w-9 rounded-lg bg-blue-600 text-sm font-bold text-white shadow-md shadow-blue-200"
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
                                    <span class="text-primary">Tin tức</span>
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
                                        class="mb-2 text-[10px] font-bold tracking-wider text-primary uppercase"
                                    >
                                        Thị trường
                                    </p>
                                    <h4
                                        class="mb-2 line-clamp-2 text-sm font-bold text-gray-800 transition group-hover:text-primary"
                                    >
                                        Giá bất động sản tăng 15% trong 6 tháng
                                        đầu năm 2026
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
                                        class="mb-2 text-[10px] font-bold tracking-wider text-blue-600 uppercase"
                                    >
                                        Kinh Doanh
                                    </p>
                                    <h4
                                        class="mb-2 line-clamp-2 text-sm font-bold text-gray-800 transition group-hover:text-blue-600"
                                    >
                                        Cơ hội đầu tư dự án khu đô thị mới phía
                                        Đông
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
                                        class="mb-2 text-[10px] font-bold tracking-wider text-green-600 uppercase"
                                    >
                                        Tư Vấn
                                    </p>
                                    <h4
                                        class="mb-2 line-clamp-2 text-sm font-bold text-gray-800 transition group-hover:text-green-600"
                                    >
                                        Hướng dẫn quy trình mua bán nhà đất toàn
                                        tập năm 2026
                                    </h4>
                                    <p class="text-[11px] text-gray-500">
                                        1 tuần trước
                                    </p>
                                </div>
                            </article>

                            <button
                                class="w-full rounded-lg bg-gray-100 py-2.5 text-xs font-bold tracking-wider text-gray-800 uppercase transition duration-300 hover:bg-primary hover:text-white"
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

.page-hidden {
    display: none !important;
}

.page-active {
    display: block;
    animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}

.text-primary {
    color: #ff9c22;
}

.bg-primary {
    background-color: #ff9c22;
}

.border-primary {
    border-color: #ff9c22;
}

.hover\:bg-primaryDark:hover {
    background-color: #e67e22;
}

.hover\:text-primaryDark:hover {
    color: #e67e22;
}

.hover\:bg-primary:hover {
    background-color: #ff9c22;
}

.hover\:text-primary:hover {
    color: #ff9c22;
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
</style
