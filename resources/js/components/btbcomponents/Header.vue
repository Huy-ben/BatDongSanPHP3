<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3'
import axios from 'axios'
import { computed, onMounted, ref, watch } from 'vue'
import { clearAuthToken } from '@/lib/authToken'

const navItems = [
    { label: 'Trang chủ', link: '/', match: 'exact' as const },
    { label: 'Nhà đất bán', link: '/post-sell', match: 'prefix' as const },
    { label: 'Nhà đất cho thuê', link: '/post-rent', match: 'prefix' as const },
    { label: 'Tin tức', link: '/blog', match: 'prefix' as const },
    { label: 'Về chúng tôi', link: '/about-us', match: 'exact' as const },
    { label: 'Liên hệ', link: '/contact', match: 'exact' as const },
]

const isMobileMenuOpen = ref(false)
const page = usePage()

const currentPath = computed(() => {
    const [pathWithoutQuery] = page.url.split('?')
    if (!pathWithoutQuery) return '/'

    if (pathWithoutQuery.length > 1 && pathWithoutQuery.endsWith('/')) {
        return pathWithoutQuery.slice(0, -1)
    }

    return pathWithoutQuery
})

const menuItems = computed(() => {
    return navItems.map((item) => {
        const isActive =
            item.match === 'exact'
                ? currentPath.value === item.link
                : currentPath.value === item.link || currentPath.value.startsWith(`${item.link}/`)

        return {
            ...item,
            active: isActive,
        }
    })
})

const authUser = computed(() => page.props.auth?.user)
const isAuthenticated = computed(() => Boolean(authUser.value))
const headerNotifications = ref<Array<{
    id: number
    title: string
    content: string
    is_read: boolean
    created_at: string
}>>([])
const isLoadingNotifications = ref(false)

const userInitial = computed(() => {
    const name = authUser.value?.name
    return name?.charAt(0).toUpperCase() ?? 'B'
})

const unreadNotificationCount = computed(() => {
    return headerNotifications.value.filter((item) => !item.is_read).length
})

const previewNotifications = computed(() => {
    return headerNotifications.value.slice(0, 5)
})

const unreadBadge = computed(() => {
    if (unreadNotificationCount.value <= 0) {
        return ''
    }

    return unreadNotificationCount.value > 99 ? '99+' : String(unreadNotificationCount.value)
})

const formatNotificationTime = (value: string) => {
    if (!value) {
        return ''
    }

    return new Intl.DateTimeFormat('vi-VN', {
        hour: '2-digit',
        minute: '2-digit',
        day: '2-digit',
        month: '2-digit',
    }).format(new Date(value))
}

const fetchHeaderNotifications = async () => {
    if (!isAuthenticated.value) {
        headerNotifications.value = []
        return
    }

    isLoadingNotifications.value = true

    try {
        const response = await axios.get('/notifications/data')
        headerNotifications.value = Array.isArray(response.data) ? response.data : []
    } catch {
        headerNotifications.value = []
    } finally {
        isLoadingNotifications.value = false
    }
}

const handleLogout = async () => {
    try {
        await axios.post('/api/logout')
    } catch {
        // Continue with web logout even if API token already invalid.
    }

    clearAuthToken()
    router.post('/logout')
}

onMounted(() => {
    fetchHeaderNotifications()
})

watch(
    () => authUser.value?.id,
    () => {
        fetchHeaderNotifications()
    }
)
</script>

<template>
    <header class="sticky top-0 z-50 border-b border-zinc-200 bg-white text-zinc-900">
        <div class="mx-auto flex h-24 w-full max-w-[1280px] items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-9">
                <a href="/" class="flex items-center" aria-label="Logo placeholder">
                    <img src="https://res.cloudinary.com/djbobb5oe/image/upload/v1774891267/BTB_Logo_lxnhfh_ainsl1_py1quw.png" alt="Logo" width="80"  />
                </a>

                <nav class="hidden items-center gap-8 lg:flex">
                    <Link
                        v-for="item in menuItems"
                        :key="item.label"
                        :href="item.link"
                        class="relative py-1 text-[15px] font-semibold transition-colors"
                        :class="item.active ? 'text-zinc-900 after:absolute after:-bottom-3 after:left-0 after:h-[3px] after:w-full after:rounded-full after:bg-[#FF9C22]' : 'text-zinc-700 hover:text-zinc-900'"
                    >
                        {{ item.label }}
                    </Link>

                    
                </nav>
            </div>

            <div class="hidden items-center gap-6 lg:flex">

                <Link
                    href="/favorites"
                    class="rounded-full p-2 text-zinc-700 transition hover:text-[#B76300]"
                    aria-label="Yêu thích"
                >
                    <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 20.5C11.8 20.5 11.6 20.4 11.4 20.2L4.7 13.8C3 12.1 3.1 9.4 4.9 7.8C6.6 6.3 9.2 6.5 10.6 8.1L12 9.7L13.4 8.1C14.8 6.5 17.4 6.3 19.1 7.8C20.9 9.4 21 12.1 19.3 13.8L12.6 20.2C12.4 20.4 12.2 20.5 12 20.5Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </Link>

                <div class="group relative">
                    <Link
                        href="/notifications"
                        class="relative inline-flex h-10 w-10 items-center justify-center rounded-full text-zinc-700 transition hover:text-[#B76300]"
                        aria-label="Thông báo"
                    >
                        <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 17H16M9 20H15M17 10.5C17 8 15 6 12.5 6H11.5C9 6 7 8 7 10.5V14L5.5 16H18.5L17 14V10.5Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span
                            v-if="unreadNotificationCount > 0"
                            class="absolute -right-0.5 -top-0.5 flex h-5 min-w-5 items-center justify-center rounded-full bg-[#FF9C22] px-1 text-xs font-bold leading-none text-white"
                        >
                            {{ unreadBadge }}
                        </span>
                    </Link>

                    <div
                        v-if="isAuthenticated"
                        class="invisible pointer-events-none absolute right-0 top-[calc(100%-6px)] z-30 w-96 overflow-hidden rounded-xl border border-zinc-200 bg-white p-3 opacity-0 shadow-[0_14px_34px_rgba(15,23,42,0.14)] transition-all duration-200 group-hover:visible group-hover:pointer-events-auto group-hover:opacity-100"
                    >
                        <div class="mb-2 flex items-center justify-between border-b border-zinc-200 pb-2">
                            <p class="text-sm font-bold text-zinc-900">Thông báo</p>
                            <Link href="/notifications" class="text-xs font-semibold text-[#B76300] hover:text-[#FF9C22]">Xem tất cả</Link>
                        </div>

                        <div v-if="isLoadingNotifications" class="py-6 text-center text-xs text-zinc-500">
                            Đang tải thông báo...
                        </div>

                        <div v-else-if="previewNotifications.length === 0" class="py-6 text-center text-xs text-zinc-500">
                            Bạn chưa có thông báo nào.
                        </div>

                        <div v-else class="notification-scroll max-h-80 space-y-2 overflow-y-auto pr-1">
                            <Link
                                v-for="item in previewNotifications"
                                :key="item.id"
                                href="/notifications"
                                class="block rounded-lg border px-3 py-2 transition"
                                :class="item.is_read ? 'border-zinc-200 bg-white hover:border-zinc-300' : 'border-orange-200 bg-orange-50/50 hover:border-orange-300'"
                            >
                                <div class="flex items-start justify-between gap-2">
                                    <p class="line-clamp-1 text-sm font-semibold text-zinc-900">{{ item.title }}</p>
                                    <span v-if="!item.is_read" class="mt-1 h-2 w-2 rounded-full bg-[#FF9C22]"></span>
                                </div>
                                <p class="mt-0.5 line-clamp-2 text-xs text-zinc-600">{{ item.content }}</p>
                                <p class="mt-1 text-[11px] text-zinc-400">{{ formatNotificationTime(item.created_at) }}</p>
                            </Link>
                        </div>
                    </div>

                </div>

                <div v-if="isAuthenticated" class="group relative">
                    <a
                        class="flex h-10 w-10 items-center justify-center rounded-full bg-[#FFF1E1] text-base font-bold text-[#B76300]"
                        aria-label="Tài khoản"
                        href="/profile"
                    >
                    <p v-if="!authUser.avatar">{{ userInitial }}</p>
                    <img v-else :src="authUser.avatar" alt="Avatar" class="h-full w-full rounded-full object-cover" />
                    </a>

                    <div
                        class="pointer-events-none absolute right-0 top-full z-30 w-52 translate-y-2 rounded-xl border border-zinc-200 bg-white p-2 opacity-0 shadow-lg transition-all duration-200 group-hover:pointer-events-auto group-hover:translate-y-0 group-hover:opacity-100"
                    >
                        <a v-if="authUser.role == 0"
                            href="/admin"
                            class="block rounded-lg px-3 py-2 text-sm font-medium text-red-500 transition hover:bg-zinc-100 hover:text-zinc-900"
                        >
                            Trang quản trị
                        </a>
                        <a
                            href="/profile"
                            class="block rounded-lg px-3 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100 hover:text-zinc-900"
                        >
                            Thông tin tài khoản
                        </a>
                        <a
                            href="/favorites"
                            class="block rounded-lg px-3 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100 hover:text-zinc-900"
                        >
                            Tin đã lưu
                        </a>
                        <a
                            href="/my-posts"
                            class="block rounded-lg px-3 py-2 text-sm font-medium text-zinc-700 transition hover:bg-zinc-100 hover:text-zinc-900"
                        >
                            Tin của tôi
                        </a>
                        <button
                            type="button"
                            class="block w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-red-600 transition hover:bg-red-50"
                            @click="handleLogout"
                        >
                            Đăng xuất
                        </button>
                    </div>
                </div>

                <div v-else class="flex items-center gap-3">
                    <Link
                        href="/login"
                        class="rounded-xl border border-[#FF9C22] bg-[#FF9C22] px-3 py-2 text-[15px] font-semibold text-white transition hover:bg-[#f28f13]"
                    >
                        Đăng nhập
                    </Link>
                </div>

                <Link
                    href="/dang-tin"
                    class="rounded-xl border border-zinc-300 bg-white px-3 py-2 text-[15px] font-semibold text-zinc-900 transition hover:border-[#FF9C22] hover:text-[#B76300]"
                >
                    Đăng tin
                </Link>
            </div>

            <button
                type="button"
                class="inline-flex items-center justify-center rounded-lg border border-zinc-300 p-2 text-zinc-700 lg:hidden"
                aria-label="Mở menu"
                :aria-expanded="isMobileMenuOpen"
                @click="isMobileMenuOpen = !isMobileMenuOpen"
            >
                <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        v-if="!isMobileMenuOpen"
                        d="M4 7H20M4 12H20M4 17H14"
                        stroke="currentColor"
                        stroke-width="1.9"
                        stroke-linecap="round"
                    />
                    <path
                        v-else
                        d="M6 6L18 18M18 6L6 18"
                        stroke="currentColor"
                        stroke-width="1.9"
                        stroke-linecap="round"
                    />
                </svg>
            </button>
        </div>

        <div
            v-if="isMobileMenuOpen"
            class="border-t border-zinc-200 bg-white px-4 pb-4 pt-3 sm:px-6 lg:hidden"
        >
            <nav class="space-y-1">
                <Link
                    v-for="item in menuItems"
                    :key="`mobile-${item.label}`"
                    :href="item.link"
                    class="block rounded-lg px-3 py-2 text-base font-medium transition-colors"
                    :class="item.active ? 'bg-[#FFF1E1] text-[#B76300]' : 'text-zinc-700 hover:bg-zinc-100 hover:text-zinc-900'"
                    @click="isMobileMenuOpen = false"
                >
                    {{ item.label }}
                </Link>
            </nav>

            <div class="mt-4 grid grid-cols-2 gap-2">
                <Link
                    href="/favorites"
                    class="rounded-lg border border-zinc-300 px-3 py-2 text-sm font-semibold text-zinc-700 transition hover:border-[#FF9C22] hover:text-[#B76300]"
                >
                    Yêu thích
                </Link>
                <Link
                    href="/notifications"
                    class="relative rounded-lg border border-zinc-300 px-3 py-2 text-sm font-semibold text-zinc-700 transition hover:border-[#FF9C22] hover:text-[#B76300]"
                >
                    Thông báo
                    <span
                        v-if="unreadNotificationCount > 0"
                        class="ml-1 inline-flex min-w-5 items-center justify-center rounded-full bg-[#FF9C22] px-1.5 py-0.5 text-[10px] font-bold leading-none text-white"
                    >
                        {{ unreadBadge }}
                    </span>
                </Link>
                <template v-if="isAuthenticated">
                    <a
                        class="col-span-2 rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm font-semibold text-zinc-700 transition hover:border-[#FF9C22] hover:text-[#B76300]"
                        href="/profile"
                    >
                        Tài khoản
                    </a>
                    <button
                        type="button"
                        class="col-span-2 rounded-lg border border-red-200 bg-white px-3 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-50"
                        @click="handleLogout"
                    >
                        Đăng xuất
                    </button>
                </template>
                <template v-else>
                    <Link
                        href="/login"
                        class="rounded-lg border border-zinc-300 bg-white px-3 py-2 text-center text-sm font-semibold text-zinc-700 transition hover:border-[#FF9C22] hover:text-[#B76300]"
                    >
                        Đăng nhập
                    </Link>
                    <Link
                        href="/register"
                        class="rounded-lg bg-[#FF9C22] px-3 py-2 text-center text-sm font-semibold text-white transition hover:bg-[#f28f13]"
                    >
                        Đăng ký
                    </Link>
                </template>
            </div>

            <button
                type="button"
                class="mt-4 w-full rounded-xl bg-[#FF9C22] px-4 py-3 text-base font-semibold text-white transition hover:bg-[#f28f13]"
            >
                Đăng tin
            </button>
        </div>
    </header>
</template>

<style scoped>
.notification-scroll {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.notification-scroll::-webkit-scrollbar {
    width: 0;
    height: 0;
}
</style>