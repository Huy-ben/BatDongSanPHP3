<script setup lang="ts">
const menuItems = ['Trang chủ', 'Nhà đất bán', 'Nhà đất cho thuê', 'Dự án'];
const policyItems = [
    'Điều khoản sử dụng',
    'Chính sách bảo mật',
    'Quy chế hoạt động',
];
const channelItems = ['Facebook Fanpage', 'Google Maps'];
</script>

<template>
    <footer class="w-full bg-[#FF9C22] px-4 py-8 text-white sm:px-6 lg:px-8">
        <div
            class="mx-auto grid w-full max-w-[1280px] gap-8 sm:grid-cols-2 lg:grid-cols-[220px_1fr_1fr_260px]"
        >
            <section>
                <div
                    class="mx-auto flex bg-white h-21 w-21 items-center justify-center rounded-full border-2 border-white/90 text-base font-semibold"
                >
                    <img
                        src="https://res.cloudinary.com/djbobb5oe/image/upload/v1774891267/BTB_Logo_lxnhfh_ainsl1_py1quw.png"
                        alt="Logo"
                        width="80"
                    />
                </div>
                <p class="mt-4 text-sm leading-6 text-white/90">
                    Nền tảng thông tin bất động sản, hỗ trợ người dùng tìm kiếm
                    nhà đất nhanh và minh bạch.
                </p>
            </section>

            <section>
                <h3 class="text-xl font-bold">Menu</h3>
                <ul class="mt-3 space-y-2 text-sm text-white/90">
                    <li v-for="item in menuItems" :key="item">
                        <a
                            href="#"
                            class="transition hover:text-white hover:underline"
                            >{{ item }}</a
                        >
                    </li>
                </ul>
            </section>

            <section>
                <h3 class="text-xl font-bold">Chính sách</h3>
                <ul class="mt-3 space-y-2 text-sm text-white/90">
                    <li v-for="item in policyItems" :key="item">
                        <a
                            href="#"
                            class="transition hover:text-white hover:underline"
                            >{{ item }}</a
                        >
                    </li>
                </ul>
            </section>

            <section>
                <h3 class="text-xl font-bold">Kênh liên hệ</h3>
                <ul class="mt-3 space-y-2 text-sm text-white/90">
                    <li v-for="item in channelItems" :key="item">
                        <a
                            href="#"
                            class="transition hover:text-white hover:underline"
                            >{{ item }}</a
                        >
                    </li>
                </ul>
                <p class="mt-4 text-sm text-white/80">Hotline: 1900 1881</p>
            </section>
        </div>

        <div
            class="mx-auto mt-6 max-w-[1280px] border-t border-white/30 pt-4 text-xs text-white/80"
        >
            © {{ new Date().getFullYear() }} Batdongsan Clone. All rights
            reserved.
        </div>
    </footer>
</template>
