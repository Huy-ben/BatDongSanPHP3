<script setup>
import { ref } from 'vue';
import ClientLayout from '@/layouts/ClientLayout.vue';

// 1. Trạng thái giao diện
const isGridView = ref(true);
const isEditModalOpen = ref(false);

// 2. Dữ liệu người dùng (Cập nhật tên Quốc Toàn)
const userProfile = ref({
    name: 'Quốc Toàn',
    avatar: 'https://api.dicebear.com/7.x/avataaars/svg?seed=QuocToan',
    title: 'Chuyên viên cấp cao',
    plan: 'VIP Platinum',
    planExpiry: '31/12/2026',
    phone: '0912 672 ***',
    email: 'toan.quoc@btb.vn',
    location: 'TP. Cần Thơ',
});

// 3. Form chỉnh sửa (Bản sao của profile)
const editForm = ref({ ...userProfile.value });

// 4. Danh sách tin đăng
const posts = ref([
    {
        id: 1,
        image: 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=500',
        type: 'Bán',
        label: 'HOT',
        price: '50 tỷ VND',
        title: 'Penthouse thông 2 tầng có hồ bơi riêng biệt lập tại Thủ Đức',
        location: 'TP. Thủ Đức',
        area: '310 m²',
        bedrooms: 4,
    },
    {
        id: 2,
        image: 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=500',
        type: 'Cho thuê',
        label: 'NEW',
        price: '25 triệu/tháng',
        title: 'Căn hộ 3PN dự án Diamond Island - Full nội thất cao cấp',
        location: 'Quận 2',
        area: '120 m²',
        bedrooms: 3,
    },
]);

// 5. Logic xử lý
const openEditModal = () => {
    editForm.value = { ...userProfile.value };
    isEditModalOpen.value = true;
};

const saveProfile = () => {
    userProfile.value = { ...editForm.value };
    isEditModalOpen.value = false;
};

const deletePost = (id) => {
    if (confirm('Sếp chắc chắn muốn xóa tin này chứ?')) {
        posts.value = posts.value.filter((p) => p.id !== id);
    }
};
</script>

<template>
    <ClientLayout>
        <div
            class="min-h-screen bg-[#f8f9fc] pb-12 font-sans text-slate-900 antialiased"
        >
            <div class="container mx-auto max-w-5xl px-4 py-8">
                <div
                    class="mb-6 rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm"
                >
                    <nav
                        aria-label="Breadcrumb"
                        class="flex items-center gap-2 text-sm"
                    >
                        <a
                            href="/"
                            class="font-medium text-slate-500 transition hover:text-slate-800"
                            >Trang chủ</a
                        >
                        <span class="text-slate-300">/</span>
                        <span class="font-semibold text-brand"
                            >Thông tin cá nhân</span
                        >
                    </nav>
                </div>
                <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
                    <div class="lg:col-span-4">
                        <div
                            class="sticky top-24 rounded-[2.5rem] border border-slate-100 bg-white p-6 shadow-sm"
                        >
                            <div
                                class="flex flex-col items-center lg:items-start lg:text-left"
                            >
                                <div
                                    class="mb-5 flex h-24 w-24 justify-center self-center overflow-hidden rounded-[2rem] shadow-lg ring-4 ring-orange-50"
                                >
                                    <img
                                        :src="userProfile.avatar"
                                        class="h-full w-full object-cover"
                                    />
                                </div>

                                <h1
                                    class="w-full text-center text-xl font-black text-slate-900 italic"
                                >
                                    {{ userProfile.name }}
                                </h1>

                                <div
                                    class="mt-3 w-full rounded-2xl bg-orange-500 px-4 py-2.5 text-xs font-bold tracking-widest text-white uppercase"
                                >
                                    <div class="">
                                        <span
                                            class="text-[13px] font-black tracking-tighter text-white uppercase italic"
                                        >
                                            <i
                                                class="fa-solid fa-crown mr-1"
                                            ></i>
                                            {{ userProfile.plan }}
                                        </span>
                                    </div>

                                    <p
                                        class="ml-2 text-[11px] font-bold tracking-wide text-white uppercase"
                                    >
                                        Ngày hết hạn:
                                        {{ userProfile.planExpiry }}
                                    </p>
                                </div>

                                <div
                                    class="w-full space-y-4 border-t border-slate-50 pt-8 text-[11px] font-bold tracking-tight text-slate-600 uppercase"
                                >
                                    <div
                                        class="flex items-center gap-3 text-sm"
                                    >
                                        <i
                                            class="fa-solid fa-phone w-5 text-orange-500"
                                        ></i>
                                        {{ userProfile.phone }}
                                    </div>
                                    <div
                                        class="flex items-center gap-3 text-sm"
                                    >
                                        <i
                                            class="fa-solid fa-envelope w-5 text-orange-500"
                                        ></i>
                                        {{ userProfile.email }}
                                    </div>
                                    <div
                                        class="flex items-center gap-3 text-sm"
                                    >
                                        <i
                                            class="fa-solid fa-location-dot w-5 text-orange-500"
                                        ></i>
                                        {{ userProfile.location }}
                                    </div>
                                </div>

                                <button
                                    @click="openEditModal"
                                    class="mt-8 w-full rounded-2xl bg-slate-900 py-3 text-[10px] font-black tracking-widest text-white uppercase shadow-xl shadow-orange-100 transition hover:bg-orange-600"
                                >
                                    Chỉnh sửa hồ sơ
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-8">
                        <div
                            class="mb-6 flex items-center justify-between px-1"
                        >
                            <h3
                                class="text-[10px] font-black tracking-[0.3em] text-slate-400 uppercase italic"
                            >
                                Tin đã đăng
                            </h3>
                            <div
                                class="flex rounded-2xl border border-slate-100 bg-white p-1.5 shadow-sm"
                            >
                                <button
                                    @click="isGridView = true"
                                    :class="[
                                        'h-8 w-10 rounded-xl transition',
                                        isGridView
                                            ? 'bg-orange-50 text-orange-600'
                                            : 'text-slate-300',
                                    ]"
                                >
                                    <i
                                        class="fa-solid fa-table-cells-large"
                                    ></i>
                                </button>
                                <button
                                    @click="isGridView = false"
                                    :class="[
                                        'h-8 w-10 rounded-xl transition',
                                        !isGridView
                                            ? 'bg-orange-50 text-orange-600'
                                            : 'text-slate-300',
                                    ]"
                                >
                                    <i class="fa-solid fa-list"></i>
                                </button>
                            </div>
                        </div>

                        <div
                            :class="[
                                'grid gap-6',
                                isGridView
                                    ? 'grid-cols-1 md:grid-cols-2'
                                    : 'grid-cols-1',
                            ]"
                        >
                            <div
                                v-for="post in posts"
                                :key="post.id"
                                class="group relative overflow-hidden rounded-[2rem] border border-slate-100 bg-white transition hover:border-orange-200 hover:shadow-2xl"
                            >
                                <div
                                    class="absolute top-4 right-4 z-20 flex gap-2 opacity-0 transition-all group-hover:opacity-100"
                                >
                                    <button
                                        class="h-9 w-9 rounded-xl bg-white text-blue-600 shadow-lg transition hover:bg-blue-600 hover:text-white"
                                    >
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button
                                        @click="deletePost(post.id)"
                                        class="h-9 w-9 rounded-xl bg-white text-red-600 shadow-lg transition hover:bg-red-600 hover:text-white"
                                    >
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                                <div
                                    :class="[isGridView ? '' : 'flex flex-row']"
                                >
                                    <div
                                        :class="[
                                            isGridView
                                                ? 'h-48 w-full'
                                                : 'h-48 w-60',
                                            'shrink-0 overflow-hidden',
                                        ]"
                                    >
                                        <img
                                            :src="post.image"
                                            class="h-full w-full object-cover transition duration-700 group-hover:scale-110"
                                        />
                                    </div>
                                    <div
                                        class="flex flex-col justify-center p-6"
                                    >
                                        <span
                                            class="text-lg font-black text-orange-600"
                                            >{{ post.price }}</span
                                        >
                                        <h4
                                            class="mt-2 line-clamp-2 text-[13px] font-bold text-slate-800 uppercase transition group-hover:text-orange-600"
                                        >
                                            {{ post.title }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <Transition name="fade">
                <div
                    v-if="isEditModalOpen"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm"
                >
                    <div
                        class="w-full max-w-xl rounded-[3rem] bg-white p-8 shadow-2xl"
                    >
                        <div class="mb-6 flex items-center justify-between">
                            <h3
                                class="text-lg font-black tracking-tight text-slate-900 uppercase italic"
                            >
                                Cập nhật hồ sơ sếp
                            </h3>
                            <button
                                @click="isEditModalOpen = false"
                                class="text-slate-400 transition hover:text-slate-600"
                            >
                                <i class="fa-solid fa-xmark text-xl"></i>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label
                                    class="ml-1 text-[10px] font-black text-slate-400 uppercase"
                                    >Tên hiển thị</label
                                >
                                <input
                                    v-model="editForm.name"
                                    type="text"
                                    class="mt-1 w-full rounded-2xl border-none bg-slate-50 px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-orange-500"
                                />
                            </div>
                            <div>
                                <label
                                    class="ml-1 text-[10px] font-black text-slate-400 uppercase"
                                    >Số điện thoại</label
                                >
                                <input
                                    v-model="editForm.phone"
                                    type="text"
                                    class="mt-1 w-full rounded-2xl border-none bg-slate-50 px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-orange-500"
                                />
                            </div>
                            <div>
                                <label
                                    class="ml-1 text-[10px] font-black text-slate-400 uppercase"
                                    >Email liên hệ</label
                                >
                                <input
                                    v-model="editForm.email"
                                    type="email"
                                    class="mt-1 w-full rounded-2xl border-none bg-slate-50 px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-orange-500"
                                />
                            </div>
                            <div>
                                <label
                                    class="ml-1 text-[10px] font-black text-slate-400 uppercase"
                                    >Địa điểm</label
                                >
                                <input
                                    v-model="editForm.location"
                                    type="text"
                                    class="mt-1 w-full rounded-2xl border-none bg-slate-50 px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-orange-500"
                                />
                            </div>
                            <div>
                                <label
                                    class="ml-1 text-[10px] font-black text-slate-400 uppercase"
                                    >Kinh nghiệm</label
                                >
                                <input
                                    v-model="editForm.experience"
                                    type="text"
                                    class="mt-1 w-full rounded-2xl border-none bg-slate-50 px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-orange-500"
                                />
                            </div>
                            <div>
                                <label
                                    class="ml-1 text-[10px] font-black text-slate-400 uppercase"
                                    >Chức danh</label
                                >
                                <input
                                    v-model="editForm.title"
                                    type="text"
                                    class="mt-1 w-full rounded-2xl border-none bg-slate-50 px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-orange-500"
                                />
                            </div>
                            <div class="md:col-span-2">
                                <label
                                    class="ml-1 text-[10px] font-black text-slate-400 uppercase"
                                    >Tiểu sử cá nhân</label
                                >
                                <textarea
                                    v-model="editForm.bio"
                                    rows="3"
                                    class="mt-1 w-full rounded-2xl border-none bg-slate-50 px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-orange-500"
                                ></textarea>
                            </div>
                        </div>

                        <div class="mt-8 flex gap-3">
                            <button
                                @click="isEditModalOpen = false"
                                class="flex-1 rounded-2xl bg-slate-100 py-3 text-[10px] font-black tracking-widest text-slate-500 uppercase transition hover:bg-slate-200"
                            >
                                Hủy bỏ
                            </button>
                            <button
                                @click="saveProfile"
                                class="flex-1 rounded-2xl bg-orange-600 py-3 text-[10px] font-black tracking-widest text-white uppercase shadow-lg shadow-orange-200 transition hover:scale-[1.02]"
                            >
                                Lưu thông tin
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </div>
    </ClientLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: all 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: scale(0.95);
}
</style>
