<script setup lang="ts">
import { ref } from 'vue'

const navItems = [
    { label: 'Trang chủ', active: true },
    { label: 'Nhà đất bán', active: false },
    { label: 'Nhà đất cho thuê', active: false },
    { label: 'Dự án', active: false },
    { label: 'Tin tức', active: false },
]

const isMobileMenuOpen = ref(false)
</script>

<template>
    <header class="border-b border-zinc-200 bg-white text-zinc-900">
        <div class="mx-auto flex h-24 w-full max-w-[1280px] items-center justify-between px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-9">
                <a href="/" class="flex items-center" aria-label="Logo placeholder">
                    <img src="https://res.cloudinary.com/djbobb5oe/image/upload/v1774891267/BTB_Logo_lxnhfh_ainsl1_py1quw.png" alt="Logo" width="80"  />
                </a>

                <nav class="hidden items-center gap-8 lg:flex">
                    <a
                        v-for="item in navItems"
                        :key="item.label"
                        href="#"
                        class="relative py-1 text-[17px] font-semibold transition-colors"
                        :class="item.active ? 'text-zinc-900 after:absolute after:-bottom-3 after:left-0 after:h-[3px] after:w-full after:rounded-full after:bg-[#FF9C22]' : 'text-zinc-700 hover:text-zinc-900'"
                    >
                        {{ item.label }}
                    </a>

                    
                </nav>
            </div>

            <div class="hidden items-center gap-6 lg:flex">

                <button
                    type="button"
                    class="rounded-full p-2 text-zinc-700 transition hover:bg-zinc-100 hover:text-zinc-900"
                    aria-label="Yêu thích"
                >
                    <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 20.5C11.8 20.5 11.6 20.4 11.4 20.2L4.7 13.8C3 12.1 3.1 9.4 4.9 7.8C6.6 6.3 9.2 6.5 10.6 8.1L12 9.7L13.4 8.1C14.8 6.5 17.4 6.3 19.1 7.8C20.9 9.4 21 12.1 19.3 13.8L12.6 20.2C12.4 20.4 12.2 20.5 12 20.5Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>

                <button
                    type="button"
                    class="relative rounded-full p-2 text-zinc-700 transition hover:bg-zinc-100 hover:text-zinc-900"
                    aria-label="Thông báo"
                >
                    <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 17H16M9 20H15M17 10.5C17 8 15 6 12.5 6H11.5C9 6 7 8 7 10.5V14L5.5 16H18.5L17 14V10.5Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <span class="absolute right-1 top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-[#FF9C22] px-1 text-xs font-bold leading-none text-white">
                        1
                    </span>
                </button>

                <button
                    type="button"
                    class="flex h-10 w-10 items-center justify-center rounded-full bg-[#FFF1E1] text-base font-bold text-[#B76300]"
                    aria-label="Tài khoản"
                >
                    B
                </button>

                <button
                    type="button"
                    class="rounded-xl border border-zinc-300 bg-white px-6 py-3 text-lg font-semibold text-zinc-900 transition hover:border-[#FF9C22] hover:text-[#B76300]"
                >
                    Đăng tin
                </button>
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
                <a
                    v-for="item in navItems"
                    :key="`mobile-${item.label}`"
                    href="#"
                    class="block rounded-lg px-3 py-2 text-base font-medium transition-colors"
                    :class="item.active ? 'bg-[#FFF1E1] text-[#B76300]' : 'text-zinc-700 hover:bg-zinc-100 hover:text-zinc-900'"
                    @click="isMobileMenuOpen = false"
                >
                    {{ item.label }}
                </a>
            </nav>

            <div class="mt-4 grid grid-cols-2 gap-2">
                <button
                    type="button"
                    class="rounded-lg border border-zinc-300 px-3 py-2 text-sm font-semibold text-zinc-700 transition hover:border-[#FF9C22] hover:text-[#B76300]"
                >
                    Yêu thích
                </button>
                <button
                    type="button"
                    class="rounded-lg border border-zinc-300 px-3 py-2 text-sm font-semibold text-zinc-700 transition hover:border-[#FF9C22] hover:text-[#B76300]"
                >
                    Thông báo
                </button>
                <button
                    type="button"
                    class="col-span-2 rounded-lg border border-zinc-300 bg-white px-3 py-2 text-sm font-semibold text-zinc-700 transition hover:border-[#FF9C22] hover:text-[#B76300]"
                >
                    Tài khoản
                </button>
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
:global(body) {
    background: #ffffff;
}
</style>