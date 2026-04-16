<script setup>
import InputError from '@/components/InputError.vue';
import ClientLayout from '@/layouts/ClientLayout.vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { computed, ref } from 'vue';
import { jam_read_num_forvietnamese } from '@/utils/money';

const props = defineProps({
    profile: {
        type: Object,
        required: true,
    },
    hasActivePackage: {
        type: Boolean,
        default: false,
    },
    activePackage: {
        type: Object,
        default: null,
    },
    posts: {
        type: Object,
        default: () => ({
            data: [],
            current_page: 1,
            last_page: 1,
            total: 0,
            per_page: 12,
        }),
    },
});

const isGridView = ref(true);
const isEditModalOpen = ref(false);
const isUploadingAvatar = ref(false);
const currentPage = ref(props.posts.current_page);

const form = useForm({
    name: props.profile.name ?? '',
    email: props.profile.email ?? '',
    phone_number: props.profile.phone_number ?? '',
});

const fallbackAvatar = computed(() => {
    const seed = encodeURIComponent(form.name || 'User');

    return `https://api.dicebear.com/7.x/initials/svg?seed=${seed}`;
});

const avatarPreview = computed(() => props.profile.avatar?.trim() || fallbackAvatar.value);

const packageLabel = computed(() => {
    const packageType = props.activePackage?.package_type;

    if (packageType === '2') {
        return 'SVIP';
    }

    if (packageType === '1') {
        return 'VIP';
    }

    return 'Thường';
});

const packageTone = computed(() => {
    if (packageLabel.value === 'SVIP') {
        return 'bg-amber-100 text-amber-700 border-amber-200';
    }

    if (packageLabel.value === 'VIP') {
        return 'bg-orange-100 text-orange-700 border-orange-200';
    }

    return 'bg-slate-100 text-slate-700 border-slate-200';
});

const packageAction = computed(() => {
    const packageType = props.activePackage?.package_type;

    if (packageType === '1') {
        return {
            label: 'Gia hạn gói',
            href: '/thanh-toan?plan=vip&renew=1',
        };
    }

    if (packageType === '2') {
        return {
            label: 'Gia hạn gói',
            href: '/thanh-toan?plan=svip&renew=1',
        };
    }

    return {
        label: 'Mua gói',
        href: '/package',
    }
});

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

const formatPrice = (price) => jam_read_num_forvietnamese(Number(price || 0));

const postStatusMeta = {
    published: {
        label: 'Đang hiển thị',
        className: 'bg-emerald-100 text-emerald-700',
    },
    waiting: {
        label: 'Chờ duyệt',
        className: 'bg-amber-100 text-amber-700',
    },
    draft: {
        label: 'Nháp',
        className: 'bg-slate-100 text-slate-600',
    },
    rejected: {
        label: 'Từ chối',
        className: 'bg-rose-100 text-rose-700',
    },
};

const getPostStatusLabel = (status) => {
    return postStatusMeta[status]?.label || 'Không xác định';
};

const getPostStatusClass = (status) => {
    return postStatusMeta[status]?.className || 'bg-slate-100 text-slate-600';
};

const formatDate = (dateValue) => {
    if (!dateValue) {
        return 'Chưa cập nhật';
    }

    const date = new Date(dateValue);

    if (Number.isNaN(date.getTime())) {
        return dateValue;
    }

    return new Intl.DateTimeFormat('vi-VN').format(date);
};

const openEditModal = () => {
    form.defaults({
        name: props.profile.name ?? '',
        email: props.profile.email ?? '',
        phone_number: props.profile.phone_number ?? '',
    });

    form.reset();
    form.clearErrors();
    isEditModalOpen.value = true;
};

const saveProfile = () => {
    form.patch('/profile', {
        preserveScroll: true,
        onSuccess: () => {
            isEditModalOpen.value = false;
        },
    });
};

const uploadAvatar = (event) => {
    const file = event.target.files[0];

    if (!file) {
        return;
    }

    isUploadingAvatar.value = true;
    const formData = new FormData();
    formData.append('avatar', file);

    axios
        .post('/profile/avatar', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        })
        .then(() => {
            // Reload page to refresh avatar
            window.location.reload();
        })
        .catch((error) => {
            console.error('Upload error:', error);
        })
        .finally(() => {
            isUploadingAvatar.value = false;
        });
};

const goToPage = (page) => {
    if (page < 1 || page > props.posts.last_page) {
        return;
    }

    currentPage.value = page;
    window.location.href = `/profile?page=${page}`;
};
</script>

<template>
    <ClientLayout>
        <div class="min-h-screen bg-slate-100 pb-10 text-slate-900">
            <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
                <div class="overflow-hidden rounded-3xl bg-linear-to-br from-brand-500 via-orange-500 to-amber-500 px-6 py-12 text-center text-white shadow-glow sm:px-10">
                    <p class="text-xs font-semibold tracking-[0.24em] uppercase text-orange-100">Hồ sơ cá nhân</p>
                    <h1 class="mt-3 text-2xl leading-tight font-black sm:text-3xl">Quản lý thông tin tài khoản và tin đăng của bạn</h1>
                    <p class="mt-2 max-w-3xl text-sm text-slate-200 text-center mx-auto">
                        Thông tin được đồng bộ với dữ liệu trong hệ thống. Chỉ tài khoản có gói đăng tin còn hiệu lực mới hiển thị danh sách bài đăng tại trang này.
                    </p>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-12">
                    <div class="space-y-6 lg:col-span-4">
                        <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                            <div class="flex flex-col items-center text-center">
                                <div class="relative">
                                    <img :src="avatarPreview" alt="avatar" class="h-24 w-24 rounded-2xl object-cover ring-4 ring-orange-100" />
                                    <label
                                        class="absolute bottom-0 right-0 flex h-8 w-8 cursor-pointer items-center justify-center rounded-full bg-orange-600 text-white shadow-lg transition hover:bg-orange-700"
                                        :class="{ 'opacity-50 cursor-not-allowed': isUploadingAvatar }"
                                    >
                                        <input
                                            type="file"
                                            accept="image/*"
                                            class="hidden"
                                            @change="uploadAvatar"
                                            :disabled="isUploadingAvatar"
                                        />
                                        <i v-if="!isUploadingAvatar" class="fa-solid fa-camera text-sm"></i>
                                        <i v-else class="fa-solid fa-spinner animate-spin text-sm"></i>
                                    </label>
                                </div>
                                <h2 class="mt-4 text-xl font-black text-slate-900">{{ props.profile.name }}</h2>
                                <p class="mt-1 text-sm text-slate-500">{{ props.profile.email }}</p>
                            </div>

                            <div class="mt-6 space-y-3 border-t border-slate-100 pt-5 text-sm">
                                <div class="flex items-center gap-3 text-slate-700">
                                    <i class="fa-solid fa-phone text-orange-500"></i>
                                    <span>{{ props.profile.phone_number || 'Chưa cập nhật số điện thoại' }}</span>
                                </div>
                                <div class="flex items-center gap-3 text-slate-700">
                                    <i class="fa-solid fa-envelope text-orange-500"></i>
                                    <span>{{ props.profile.email }}</span>
                                </div>
                            </div>

                            <button
                                type="button"
                                @click="openEditModal"
                                class="mt-6 w-full rounded-2xl bg-slate-900 px-4 py-3 text-xs font-bold tracking-[0.2em] text-white uppercase transition hover:bg-orange-600"
                            >
                                Chỉnh sửa thông tin
                            </button>
                        </section>

                        <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                            <div class="flex items-center justify-between gap-3">
                                <h3 class="text-sm font-black tracking-wider uppercase text-slate-700">Gói đăng tin</h3>
                                <span class="rounded-full border px-3 py-1 text-xs font-bold" :class="packageTone">{{ packageLabel }}</span>
                            </div>

                            <div v-if="props.hasActivePackage" class="mt-4 space-y-2 text-sm text-slate-700">
                                <p>
                                    Tên gói: <span class="font-semibold text-slate-900">{{ props.activePackage?.package_name }}</span>
                                </p>
                                <p>
                                    Hết hạn: <span class="font-semibold text-slate-900">{{ formatDate(props.activePackage?.expiry_date) }}</span>
                                </p>
                            </div>

                            <div v-else class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
                                Bạn chưa có gói đăng bài còn hiệu lực.
                            </div>

                            <a
                                :href="packageAction.href"
                                class="mt-5 inline-flex w-full items-center justify-center rounded-2xl border border-orange-200 bg-orange-50 px-4 py-3 text-xs font-bold tracking-[0.18em] text-orange-700 uppercase transition hover:bg-orange-100"
                            >
                                {{ packageAction.label }}
                            </a>
                        </section>
                    </div>

                    <div class="lg:col-span-8">
                        <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <h3 class="text-base font-black text-slate-900">Tin đã đăng</h3>
                                    <p class="text-sm text-slate-500">Tổng cộng {{ props.posts.total }} tin</p>
                                </div>
                                <div v-if="props.hasActivePackage" class="flex rounded-2xl border border-slate-200 p-1">
                                    <button
                                        type="button"
                                        @click="isGridView = true"
                                        :class="[
                                            'h-9 w-10 rounded-xl transition',
                                            isGridView ? 'bg-orange-50 text-orange-600' : 'text-slate-400',
                                        ]"
                                    >
                                        <i class="fa-solid fa-table-cells-large"></i>
                                    </button>
                                    <button
                                        type="button"
                                        @click="isGridView = false"
                                        :class="[
                                            'h-9 w-10 rounded-xl transition',
                                            !isGridView ? 'bg-orange-50 text-orange-600' : 'text-slate-400',
                                        ]"
                                    >
                                        <i class="fa-solid fa-list"></i>
                                    </button>
                                </div>
                            </div>

                            <div v-if="!props.hasActivePackage" class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-orange-100 text-orange-600">
                                    <i class="fa-solid fa-lock text-xl"></i>
                                </div>
                                <h4 class="mt-4 text-lg font-bold text-slate-900">Danh sách bài viết đang bị ẩn</h4>
                                <p class="mx-auto mt-2 max-w-xl text-sm text-slate-600">
                                    Hệ thống chỉ hiển thị bài đăng khi tài khoản có gói đăng tin còn hiệu lực. Vui lòng mua hoặc gia hạn gói để mở lại khu vực quản lý bài viết.
                                </p>
                            </div>

                            <div
                                v-else-if="props.posts.data.length"
                                class="mt-6 grid gap-4"
                                :class="isGridView ? 'grid-cols-1 md:grid-cols-2' : 'grid-cols-1'"
                            >
                                <article
                                    v-for="post in props.posts.data"
                                    :key="post.id"
                                    class="overflow-hidden rounded-2xl border border-slate-200 transition hover:border-orange-200 hover:shadow-lg"
                                >
                                    <div :class="isGridView ? '' : 'flex flex-col sm:flex-row'">
                                        <div :class="isGridView ? 'h-44 w-full' : 'h-44 w-full sm:w-64'" class="overflow-hidden bg-slate-100">
                                            <img
                                                :src="resolveImageUrl(post.thumbnail) || 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800'"
                                                alt="thumbnail"
                                                class="h-full w-full object-cover"
                                            />
                                        </div>
                                        <div class="flex flex-1 flex-col justify-between p-4">
                                            <div>
                                                <div class="flex items-center justify-between gap-3">
                                                    <p class="text-lg font-black text-orange-600">{{ formatPrice(post.price) }}</p>
                                                    <span
                                                        class="rounded-full px-2.5 py-1 text-xs font-semibold"
                                                        :class="getPostStatusClass(post.status)"
                                                    >
                                                        {{ getPostStatusLabel(post.status) }}
                                                    </span>
                                                </div>
                                                <h4 class="mt-2 line-clamp-2 text-sm font-bold text-slate-900">{{ post.title }}</h4>
                                            </div>
                                            <div class="mt-3 space-y-1.5 text-sm text-slate-600">
                                                <p><i class="fa-solid fa-ruler-combined mr-2 text-slate-400"></i>{{ post.area }} m2</p>
                                                <p><i class="fa-solid fa-location-dot mr-2 text-slate-400"></i>{{ post.address }}</p>
                                                <p><i class="fa-regular fa-calendar mr-2 text-slate-400"></i>{{ post.created_at }}</p>
                                            </div>
                                            <a
                                                :href="post.edit_url"
                                                class="mt-4 inline-flex items-center justify-center rounded-xl border border-orange-200 bg-orange-50 px-4 py-2.5 text-xs font-bold tracking-[0.16em] text-orange-700 uppercase transition hover:bg-orange-100"
                                            >
                                                Sửa bài đăng
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            </div>

                            <div v-if="props.posts.data.length && props.posts.last_page > 1" class="mt-8 flex items-center justify-center gap-2">
                                <button
                                    type="button"
                                    @click="goToPage(props.posts.current_page - 1)"
                                    :disabled="props.posts.current_page === 1"
                                    class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    <i class="fa-solid fa-chevron-left"></i>
                                </button>

                                <div class="flex gap-1">
                                    <button
                                        v-for="page in Math.min(5, props.posts.last_page)"
                                        :key="page"
                                        type="button"
                                        @click="goToPage(page)"
                                        :class="[
                                            'h-9 min-w-9 rounded-lg text-sm font-semibold transition',
                                            page === props.posts.current_page
                                                ? 'bg-orange-600 text-white'
                                                : 'border border-slate-200 text-slate-700 hover:bg-slate-50',
                                        ]"
                                    >
                                        {{ page }}
                                    </button>
                                    <span v-if="props.posts.last_page > 5" class="flex items-center px-2 text-slate-500">...</span>
                                </div>

                                <button
                                    type="button"
                                    @click="goToPage(props.posts.current_page + 1)"
                                    :disabled="props.posts.current_page === props.posts.last_page"
                                    class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    <i class="fa-solid fa-chevron-right"></i>
                                </button>
                            </div>

                            <div v-else class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-8 text-center">
                                <h4 class="text-lg font-bold text-slate-900">Bạn chưa có bài đăng nào</h4>
                                <p class="mt-2 text-sm text-slate-600">Hãy tạo bài đăng đầu tiên để tiếp cận khách hàng tiềm năng.</p>
                                <a
                                    href="/dang-tin"
                                    class="mt-5 inline-flex rounded-xl bg-orange-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-orange-700"
                                >
                                    Tạo bài đăng
                                </a>
                            </div>
                        </section>
                    </div>
                </div>
            </div>

            <Transition name="fade">
                <div
                    v-if="isEditModalOpen"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 px-4 py-8 backdrop-blur-sm"
                >
                    <div class="w-full max-w-2xl rounded-3xl bg-white p-6 shadow-2xl sm:p-8">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h3 class="text-xl font-black text-slate-900">Cập nhật thông tin cá nhân</h3>
                                <p class="mt-1 text-sm text-slate-500">Chỉ giữ các trường thông tin đang được lưu trong hệ thống.</p>
                            </div>
                            <button
                                type="button"
                                @click="isEditModalOpen = false"
                                class="text-slate-400 transition hover:text-slate-700"
                            >
                                <i class="fa-solid fa-xmark text-xl"></i>
                            </button>
                        </div>

                        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label class="text-xs font-bold tracking-wider text-slate-500 uppercase">Tên hiển thị</label>
                                <input
                                    v-model="form.name"
                                    type="text"
                                    class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-orange-400 focus:ring-orange-400"
                                />
                                <InputError class="mt-1" :message="form.errors.name" />
                            </div>

                            <div>
                                <label class="text-xs font-bold tracking-wider text-slate-500 uppercase">Số điện thoại</label>
                                <input
                                    v-model="form.phone_number"
                                    type="text"
                                    class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-orange-400 focus:ring-orange-400"
                                />
                                <InputError class="mt-1" :message="form.errors.phone_number" />
                            </div>

                            <div class="sm:col-span-2">
                                <label class="text-xs font-bold tracking-wider text-slate-500 uppercase">Email</label>
                                <input
                                    v-model="form.email"
                                    type="email"
                                    class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-orange-400 focus:ring-orange-400"
                                />
                                <InputError class="mt-1" :message="form.errors.email" />
                            </div>
                        </div>

                        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                            <button
                                type="button"
                                @click="isEditModalOpen = false"
                                class="rounded-xl border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                            >
                                Hủy
                            </button>
                            <button
                                type="button"
                                @click="saveProfile"
                                :disabled="form.processing"
                                class="rounded-xl bg-orange-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-orange-700 disabled:cursor-not-allowed disabled:bg-orange-300"
                            >
                                {{ form.processing ? 'Đang lưu...' : 'Lưu thay đổi' }}
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
