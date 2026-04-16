<script setup>
import ClientLayout from '@/layouts/ClientLayout.vue';
import axios from 'axios';
import { computed, onMounted, ref } from 'vue';

const activeFilter = ref('all');
const searchKeyword = ref('');
const isLoading = ref(false);
const error = ref('');

const notifications = ref([]);

const totalCount = computed(() => notifications.value.length);
const unreadCount = computed(() => notifications.value.filter((item) => !item.read).length);
const todayCount = computed(() => {
    const today = new Date().toDateString();
    return notifications.value.filter((item) => new Date(item.createdAt).toDateString() === today).length;
});

const filteredNotifications = computed(() => {
    const keyword = searchKeyword.value.trim().toLowerCase();

    return notifications.value.filter((item) => {
        const matchByFilter =
            activeFilter.value === 'all'
            || (activeFilter.value === 'unread' && !item.read)
            || (activeFilter.value === 'important' && item.important);

        if (!matchByFilter) {
            return false;
        }

        if (!keyword) {
            return true;
        }

        return [item.title, item.message]
            .map((value) => String(value || '').toLowerCase())
            .some((text) => text.includes(keyword));
    });
});

const hasUnread = computed(() => unreadCount.value > 0);

async function markAllAsRead() {
    try {
        await axios.patch('/notifications/read-all');
    } catch (err) {
        console.error('Error marking all notifications as read:', err);
    }

    notifications.value = notifications.value.map((item) => ({
        ...item,
        read: true,
    }));
}

async function fetchNotifications() {
    isLoading.value = true;
    error.value = '';

    try {
        const response = await axios.get('/notifications/data');

        notifications.value = Array.isArray(response.data)
            ? response.data.map((item) => ({
                id: item.id,
                title: item.title,
                message: item.content,
                createdAt: item.created_at,
                type: item.title?.toLowerCase().includes('gia hạn')
                    ? 'renewal_reminder'
                    : item.title?.toLowerCase().includes('từ chối')
                        ? 'review_rejected'
                        : 'review_approved',
                read: Boolean(item.is_read),
                important: Boolean(
                    item.title?.toLowerCase().includes('từ chối')
                    || item.title?.toLowerCase().includes('gia hạn')
                ),
                link: '/my-posts',
            }))
            : [];
    } catch (err) {
        console.error('Error fetching notifications:', err);
        notifications.value = [];
        error.value = 'Không thể tải danh sách thông báo. Vui lòng thử lại.';
    } finally {
        isLoading.value = false;
    }
}

onMounted(() => {
    fetchNotifications();
});

function formatDate(value) {
    if (!value) return '';

    return new Intl.DateTimeFormat('vi-VN', {
        hour: '2-digit',
        minute: '2-digit',
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    }).format(new Date(value));
}

function typeMeta(type) {
    if (type === 'review_approved') {
        return {
            text: 'Đã duyệt',
            badgeClass: 'border-emerald-200 bg-emerald-50 text-emerald-700',
        };
    }

    if (type === 'review_rejected') {
        return {
            text: 'Từ chối',
            badgeClass: 'border-rose-200 bg-rose-50 text-rose-700',
        };
    }

    if (type === 'renewal_reminder') {
        return {
            text: 'Gia hạn',
            badgeClass: 'border-amber-200 bg-amber-50 text-amber-700',
        };
    }

    return {
        text: 'Hệ thống',
        badgeClass: 'border-orange-200 bg-orange-50 text-orange-700',
    };
}

async function markAsRead(itemId) {
    try {
        await axios.patch(`/notifications/${itemId}/read`);
    } catch (err) {
        console.error('Error marking notification as read:', err);
    }

    notifications.value = notifications.value.map((item) => {
        if (item.id !== itemId) {
            return item;
        }

        return {
            ...item,
            read: true,
        };
    });
}
</script>

<template>
    <ClientLayout>
        <div class="min-h-screen bg-gray-50 py-8 text-gray-900">
            <main class="mx-auto max-w-6xl px-4 md:px-6">
                <section class="rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm md:p-6">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <p class="text-[11px] font-bold tracking-[0.22em] text-zinc-500 uppercase">Trung tâm cá nhân</p>
                            <h1 class="mt-1 text-2xl font-black tracking-tight text-zinc-900">Thông báo</h1>
                            <p class="mt-2 max-w-2xl text-sm text-zinc-500">
                                Theo dõi cập nhật về tin đăng, tương tác khách hàng và nhắc nhở thanh toán ở một nơi duy nhất.
                            </p>
                        </div>

                        <button
                            v-if="hasUnread"
                            type="button"
                            class="rounded-lg border border-zinc-300 bg-white px-4 py-2 text-sm font-semibold text-zinc-700 transition hover:border-[#ff9c22] hover:text-[#b76300]"
                            @click="markAllAsRead"
                        >
                            Đánh dấu đã đọc tất cả
                        </button>
                    </div>

                    <div class="mt-5 grid gap-3 sm:grid-cols-3">
                        <article class="rounded-xl border border-zinc-200 bg-zinc-50 p-4">
                            <p class="text-xs font-semibold text-zinc-500">Tổng thông báo</p>
                            <p class="mt-1 text-2xl font-black text-zinc-900">{{ totalCount }}</p>
                        </article>
                        <article class="rounded-xl border border-orange-200 bg-orange-50 p-4">
                            <p class="text-xs font-semibold text-orange-700">Chưa đọc</p>
                            <p class="mt-1 text-2xl font-black text-orange-700">{{ unreadCount }}</p>
                        </article>
                        <article class="rounded-xl border border-sky-200 bg-sky-50 p-4">
                            <p class="text-xs font-semibold text-sky-700">Hôm nay</p>
                            <p class="mt-1 text-2xl font-black text-sky-700">{{ todayCount }}</p>
                        </article>
                    </div>
                </section>

                <section class="mt-6 rounded-2xl border border-zinc-200 bg-white p-5 shadow-sm md:p-6">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex flex-wrap gap-2">
                            <button
                                type="button"
                                class="rounded-full border px-4 py-2 text-sm font-semibold transition"
                                :class="activeFilter === 'all' ? 'border-zinc-900 bg-zinc-900 text-white' : 'border-zinc-200 bg-white text-zinc-600 hover:border-zinc-300'"
                                @click="activeFilter = 'all'"
                            >
                                Tất cả
                            </button>
                            <button
                                type="button"
                                class="rounded-full border px-4 py-2 text-sm font-semibold transition"
                                :class="activeFilter === 'unread' ? 'border-[#ff9c22] bg-[#ff9c22] text-white' : 'border-zinc-200 bg-white text-zinc-600 hover:border-[#ff9c22]/50 hover:text-[#b76300]'"
                                @click="activeFilter = 'unread'"
                            >
                                Chưa đọc
                            </button>
                            <button
                                type="button"
                                class="rounded-full border px-4 py-2 text-sm font-semibold transition"
                                :class="activeFilter === 'important' ? 'border-sky-600 bg-sky-600 text-white' : 'border-zinc-200 bg-white text-zinc-600 hover:border-sky-300 hover:text-sky-700'"
                                @click="activeFilter = 'important'"
                            >
                                Quan trọng
                            </button>
                        </div>

                        <div class="relative w-full max-w-md lg:w-96">
                            <input
                                v-model="searchKeyword"
                                type="text"
                                placeholder="Tìm kiếm theo tiêu đề hoặc nội dung..."
                                class="w-full rounded-lg border border-zinc-200 py-2.5 pr-3 pl-10 text-sm outline-none transition focus:border-[#ff9c22]"
                            />
                            <svg viewBox="0 0 24 24" class="pointer-events-none absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-zinc-400" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11 18C14.866 18 18 14.866 18 11C18 7.13401 14.866 4 11 4C7.13401 4 4 7.13401 4 11C4 14.866 7.13401 18 11 18Z" stroke="currentColor" stroke-width="1.8"/>
                                <path d="M20 20L17 17" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                            </svg>
                        </div>
                    </div>

                    <div v-if="isLoading" class="mt-6 rounded-xl border border-zinc-200 bg-zinc-50 p-6 text-sm text-zinc-500">
                        Đang tải thông báo...
                    </div>

                    <div v-else-if="error" class="mt-6 rounded-xl border border-rose-200 bg-rose-50 p-6 text-sm text-rose-700">
                        {{ error }}
                    </div>

                    <div v-else-if="filteredNotifications.length === 0" class="mt-6 rounded-xl border border-dashed border-zinc-300 bg-zinc-50 p-8 text-center">
                        <h2 class="text-lg font-bold text-zinc-900">Không có thông báo phù hợp</h2>
                        <p class="mx-auto mt-2 max-w-lg text-sm text-zinc-500">
                            Hãy thử đổi bộ lọc hoặc từ khóa để tìm nhanh thông báo bạn cần.
                        </p>
                    </div>

                    <div v-else class="mt-6 space-y-3">
                        <article
                            v-for="item in filteredNotifications"
                            :key="item.id"
                            class="group rounded-xl border p-4 transition duration-200 md:p-5"
                            :class="item.read ? 'border-zinc-200 bg-white hover:border-zinc-300' : 'border-orange-200 bg-orange-50/40 hover:border-orange-300'"
                        >
                            <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                                <div class="flex-1">
                                    <div class="mb-2 flex flex-wrap items-center gap-2">
                                        <span class="rounded-full border px-2.5 py-1 text-[11px] font-bold" :class="typeMeta(item.type).badgeClass">
                                            {{ typeMeta(item.type).text }}
                                        </span>
                                        <span
                                            v-if="item.important"
                                            class="rounded-full border border-rose-200 bg-rose-50 px-2.5 py-1 text-[11px] font-bold text-rose-700"
                                        >
                                            Quan trọng
                                        </span>
                                        <span v-if="!item.read" class="h-2.5 w-2.5 rounded-full bg-[#ff9c22]"></span>
                                    </div>
                                    <h3 class="text-base font-extrabold text-zinc-900">
                                        {{ item.title }}
                                    </h3>
                                    <p class="mt-1 text-sm leading-6 text-zinc-600">
                                        {{ item.message }}
                                    </p>
                                    <p class="mt-2 text-xs font-medium text-zinc-400">
                                        {{ formatDate(item.createdAt) }}
                                    </p>
                                </div>

                                <div class="flex flex-wrap items-start gap-2 self-start md:justify-end">
                                    <button
                                        v-if="!item.read"
                                        type="button"
                                        class="inline-flex h-10 items-center justify-center whitespace-nowrap rounded-lg border border-orange-300 bg-orange-100 px-4 text-sm font-semibold text-orange-700 transition hover:bg-orange-200"
                                        @click="markAsRead(item.id)"
                                    >
                                        Đánh dấu đã đọc
                                    </button>
                                </div>
                            </div>
                        </article>
                    </div>
                </section>
            </main>
        </div>
    </ClientLayout>
</template>

<style scoped></style>
