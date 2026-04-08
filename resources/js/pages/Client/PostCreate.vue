<script setup lang="ts">
import { computed, ref } from 'vue'
import ClientLayout from '@/layouts/ClientLayout.vue'

const categoryOptions = [
    'Căn hộ',
    'Nhà riêng',
    'Nhà phố',
    'Biệt thự',
    'Đất nền',
    'Kho xưởng',
    'Shophouse',
]

const statusOptions = [
    { label: 'Hiện', value: '1' },
    { label: 'Ẩn', value: '0' },
]

const form = ref({
    title: '',
    seller: 'Quốc Toàn',
    category: 'Căn hộ',
    price: '',
    area: '',
    address: '',
    status: '1',
    description: '',
})

const selectedImages = ref<string[]>([])
const coverImage = computed(() => selectedImages.value[0] || '')
const imageCount = computed(() => selectedImages.value.length)

const sellerCards = [
    { label: 'Người bán', value: 'Quốc Toàn', helper: 'Tài khoản verified' },
    { label: 'Gói hiện tại', value: 'VIP Platinum', helper: 'Còn 23 ngày' },
    { label: 'Kênh hỗ trợ', value: 'Zalo / Hotline', helper: 'Phản hồi trong 5 phút' },
]

function handleImageChange(event: Event) {
    const input = event.target as HTMLInputElement
    const files = Array.from(input.files || [])

    if (!files.length) return

    selectedImages.value = files.map((file) => URL.createObjectURL(file))
}
</script>

<template>
    <ClientLayout>
        <div class="min-h-screen bg-gray-50 text-slate-900">
            <section class="border-b border-orange-100 bg-gradient-to-br from-white via-[#FFF8EF] to-[#FFE7C7]">
                <div class="mx-auto grid w-full max-w-[1100px] gap-8 px-4 py-8 lg:px-6 lg:py-10">
                    <div class="mx-auto w-full max-w-4xl text-center">
                        <div class="inline-flex items-center gap-2 rounded-full border border-orange-200 bg-white px-4 py-2 text-xs font-bold tracking-[0.28em] text-orange-600 uppercase shadow-sm">
                            <span class="h-2 w-2 rounded-full bg-orange-500"></span>
                            Đăng tin dành cho seller
                        </div>
                        <h1 class="mx-auto mt-4 max-w-2xl text-3xl font-black leading-tight text-slate-950 md:text-4xl">
                            Tạo tin đăng bất động sản với đầy đủ thông tin chỉ trong một form.
                        </h1>
                        <p class="mx-auto mt-3 max-w-2xl text-sm leading-7 text-slate-600">
                            Giao diện này bám theo bộ trường dữ liệu của bài đăng: tiêu đề, người bán, danh mục, giá, diện tích, địa chỉ, trạng thái và hình ảnh.
                        </p>

                        <div class="mx-auto mt-7 grid max-w-4xl gap-4 md:grid-cols-3">
                            <div v-for="card in sellerCards" :key="card.label" class="rounded-2xl border border-white/60 bg-white/80 p-3.5 text-left shadow-sm backdrop-blur">
                                <p class="text-[11px] font-bold tracking-[0.25em] text-slate-400 uppercase">{{ card.label }}</p>
                                <p class="mt-1.5 text-base font-extrabold text-orange-600">{{ card.value }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ card.helper }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mx-auto w-full max-w-[1100px] px-4 py-8 lg:px-6 lg:py-9">
                <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
                    <form class="space-y-6 rounded-[2rem] border border-slate-100 bg-white p-5 shadow-sm lg:p-6">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-orange-600">Thông tin bài đăng</p>
                            <h2 class="mt-2 text-xl font-black text-slate-950">Nhập các trường cần thiết</h2>
                        </div>

                        <div class="grid gap-5 md:grid-cols-2">
                            <label class="md:col-span-2">
                                <span class="mb-2 block text-sm font-semibold text-slate-700">Tiêu đề bài đăng</span>
                                <input v-model="form.title" type="text" placeholder="Ví dụ: Căn hộ 3PN view sông tại Thủ Đức" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100" />
                            </label>

                            <label>
                                <span class="mb-2 block text-sm font-semibold text-slate-700">Người bán</span>
                                <input v-model="form.seller" type="text" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100" />
                            </label>

                            <label>
                                <span class="mb-2 block text-sm font-semibold text-slate-700">Danh mục</span>
                                <select v-model="form.category" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100">
                                    <option v-for="option in categoryOptions" :key="option" :value="option">{{ option }}</option>
                                </select>
                            </label>

                            <label>
                                <span class="mb-2 block text-sm font-semibold text-slate-700">Giá bán</span>
                                <input v-model="form.price" type="text" placeholder="Ví dụ: 3,8 tỷ" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100" />
                            </label>

                            <label>
                                <span class="mb-2 block text-sm font-semibold text-slate-700">Diện tích</span>
                                <input v-model="form.area" type="text" placeholder="Ví dụ: 85" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100" />
                            </label>

                            <label class="md:col-span-2">
                                <span class="mb-2 block text-sm font-semibold text-slate-700">Địa chỉ</span>
                                <input v-model="form.address" type="text" placeholder="Nhập địa chỉ đầy đủ của sản phẩm" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100" />
                            </label>

                            <label>
                                <span class="mb-2 block text-sm font-semibold text-slate-700">Trạng thái</span>
                                <select v-model="form.status" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100">
                                    <option v-for="option in statusOptions" :key="option.value" :value="option.value">{{ option.label }}</option>
                                </select>
                            </label>

                            <label>
                                <span class="mb-2 block text-sm font-semibold text-slate-700">Mô tả nhanh</span>
                                <input v-model="form.description" type="text" placeholder="Thông tin nổi bật, pháp lý, tiện ích..." class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100" />
                            </label>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Hình ảnh bài đăng</label>
                            <label class="flex cursor-pointer flex-col items-center justify-center rounded-[1.75rem] border-2 border-dashed border-orange-200 bg-orange-50/60 px-6 py-10 text-center transition hover:border-orange-400 hover:bg-orange-50">
                                <input type="file" multiple accept="image/*" class="hidden" @change="handleImageChange" />
                                <span class="text-sm font-bold text-orange-700">Bấm để tải ảnh lên</span>
                                <span class="mt-2 text-sm text-slate-500">Kéo thả hoặc chọn nhiều ảnh. Ảnh đầu tiên là thumbnail.</span>
                            </label>

                            <div v-if="selectedImages.length" class="mt-4 rounded-[1.5rem] border border-slate-100 bg-slate-50 p-4">
                                <div class="mb-3 flex items-center justify-between">
                                    <p class="text-sm font-semibold text-slate-700">Ảnh đã chọn</p>
                                    <span class="rounded-full bg-white px-3 py-1 text-xs font-bold text-slate-500">
                                        {{ selectedImages.length }} ảnh
                                    </span>
                                </div>

                                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                                    <div
                                        v-for="(image, index) in selectedImages"
                                        :key="`${image}-${index}`"
                                        class="group relative overflow-hidden rounded-2xl border border-white bg-white shadow-sm"
                                    >
                                        <img
                                            :src="image"
                                            :alt="`Ảnh đã chọn ${index + 1}`"
                                            class="h-28 w-full object-cover transition duration-300 group-hover:scale-105"
                                        />
                                        <div class="absolute left-2 top-2 rounded-full bg-black/60 px-2 py-1 text-[10px] font-bold text-white">
                                            {{ index === 0 ? 'Cover' : `#${index + 1}` }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <button type="button" class="rounded-full bg-gradient-to-r from-orange-500 to-amber-500 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-orange-200 transition hover:from-orange-600 hover:to-amber-600">
                                Đăng tin ngay
                            </button>
                            <button type="button" class="rounded-full border border-slate-200 bg-white px-6 py-3 text-sm font-bold text-slate-700 transition hover:border-orange-200 hover:text-orange-600">
                                Lưu nháp
                            </button>
                        </div>
                    </form>

                    <div class="space-y-6">
                        <aside class="rounded-[2rem] border border-orange-100 bg-white p-5 shadow-xl shadow-orange-100/40 lg:p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-orange-600">Tổng quan tin</p>
                                    <h2 class="mt-1 text-xl font-black text-slate-950">Bản xem nhanh</h2>
                                </div>
                                <span class="rounded-full bg-orange-50 px-3 py-1 text-xs font-bold text-orange-600">Seller</span>
                            </div>

                            <div class="mt-5 overflow-hidden rounded-[1.5rem] border border-slate-100 bg-slate-50">
                                <div class="aspect-[16/11] bg-gradient-to-br from-slate-200 to-slate-100">
                                    <img
                                        v-if="coverImage"
                                        :src="coverImage"
                                        alt="Ảnh bài đăng"
                                        class="h-full w-full object-cover"
                                    />
                                    <div v-else class="flex h-full items-center justify-center px-8 text-center text-sm font-medium text-slate-500">
                                        Ảnh đầu tiên sẽ hiển thị như thumbnail.
                                    </div>
                                </div>
                                <div class="p-5">
                                    <p class="text-[10px] font-bold tracking-[0.28em] text-orange-500 uppercase">{{ form.category || 'Danh mục' }}</p>
                                    <h3 class="mt-2 line-clamp-2 text-lg font-black leading-snug text-slate-950">
                                        {{ form.title || 'Tiêu đề bài đăng sẽ hiển thị ở đây' }}
                                    </h3>
                                    <div class="mt-4 flex flex-wrap gap-2 text-sm font-semibold">
                                        <span class="rounded-full bg-orange-50 px-3 py-1 text-orange-600">{{ form.price || 'Giá bán' }}</span>
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-slate-700">{{ form.area || 'Diện tích' }} m²</span>
                                    </div>
                                    <p class="mt-4 text-sm leading-6 text-slate-600">
                                        {{ form.address || 'Địa chỉ đăng tin sẽ xuất hiện tại đây.' }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-5 grid grid-cols-2 gap-3 text-sm">
                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <p class="text-[10px] font-bold tracking-[0.2em] text-slate-400 uppercase">Số ảnh</p>
                                    <p class="mt-1 text-xl font-black text-slate-900">{{ imageCount }}</p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <p class="text-[10px] font-bold tracking-[0.2em] text-slate-400 uppercase">Trạng thái</p>
                                    <p class="mt-1 text-xl font-black text-slate-900">
                                        {{ form.status === '1' ? 'Hiện' : 'Ẩn' }}
                                    </p>
                                </div>
                            </div>
                        </aside>

                        <div class="rounded-[2rem] border border-orange-100 bg-gradient-to-br from-[#FFF8EF] to-[#FFEACB] p-5 shadow-sm lg:p-6">
                            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-orange-600">Gợi ý seller</p>
                            <h3 class="mt-2 text-xl font-black text-slate-950">Nên dùng ảnh đầu tiên thật đẹp</h3>
                            <p class="mt-3 text-sm leading-7 text-slate-600">
                                Ảnh cover quyết định CTR. Hãy chọn ảnh mặt tiền hoặc ảnh phòng khách rõ sáng để tin nổi bật hơn.
                            </p>
                            <div class="mt-5 grid gap-3 sm:grid-cols-3 lg:grid-cols-1 xl:grid-cols-3">
                                <div class="rounded-2xl bg-white/80 p-4 text-sm">
                                    <p class="font-bold text-slate-900">Tiêu đề</p>
                                    <p class="mt-1 text-slate-500">Ngắn gọn, có điểm mạnh nổi bật.</p>
                                </div>
                                <div class="rounded-2xl bg-white/80 p-4 text-sm">
                                    <p class="font-bold text-slate-900">Giá</p>
                                    <p class="mt-1 text-slate-500">Nên ghi đúng kỳ vọng của khách hàng.</p>
                                </div>
                                <div class="rounded-2xl bg-white/80 p-4 text-sm">
                                    <p class="font-bold text-slate-900">Trạng thái</p>
                                    <p class="mt-1 text-slate-500">Ẩn khi cần duyệt, hiện khi đã sẵn sàng.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </ClientLayout>
</template>
