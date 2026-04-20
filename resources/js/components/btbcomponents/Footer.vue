<script setup lang="ts">
const menuItems = [
    { label: 'Trang chủ', href: '/' },
    { label: 'Nhà đất bán', href: '/post-sell' },
    { label: 'Nhà đất cho thuê', href: '/post-rent' },
    { label: 'Wiki BĐS', href: '/blog' },
    { label: 'Về chúng tôi', href: '/about-us' },
    { label: 'Liên hệ', href: '/contact' },
];

const policyItems = [
    { label: 'Điều khoản sử dụng', href: '/terms' },
    { label: 'Chính sách bảo mật', href: '/privacy-policy' },
    { label: 'Quy chế hoạt động', href: '/operating-regulation' },
    { label: 'Cơ chế giải quyết khiếu nại', href: '/complaint-policy' },
];

const channelItems = [
    { label: 'Facebook Fanpage', href: '#' },
    { label: 'Google Maps', href: '#' },
    { label: 'Email hỗ trợ: hotro@batdongsan.vn', href: 'mailto:hotro@batdongsan.vn' },
];

const trustSignals = [
    'Xác thực thông tin người đăng',
    'Duyệt tin trước khi hiển thị',
    'Hỗ trợ khách hàng 7 ngày/tuần',
];
</script>

<template>
    <footer class="w-full bg-[#FF9C22] px-4 py-12 text-white sm:px-6 lg:px-8">
        <div
            class="mx-auto grid w-full max-w-7xl items-stretch gap-10 border-b border-white/20 pb-10 sm:grid-cols-2 lg:grid-cols-[280px_1fr_1fr_320px]"
        >
            <section>
                <div
                    class="flex mx-auto h-21 w-21 items-center justify-center rounded-full border-2 border-white/90 bg-white text-base font-semibold"
                >
                    <img
                        src="https://res.cloudinary.com/djbobb5oe/image/upload/v1774891267/BTB_Logo_lxnhfh_ainsl1_py1quw.png"
                        alt="Logo"
                        width="80"
                    />
                </div>
                <p class="mt-4 text-sm leading-6 text-white/90">
                    Nền tảng thông tin bất động sản hỗ trợ người dùng tìm kiếm,
                    so sánh và kết nối giao dịch minh bạch trên toàn quốc.
                </p>

                <div class="mt-5 space-y-2 text-xs leading-5 text-white/80">
                    <p>
                        Đơn vị vận hành: BatDongSan Clone Platform
                    </p>
                    <p>
                        Văn phòng: 168 Vo Van Kiet, Q1, TP. Ho Chi Minh
                    </p>
                    <p>
                        Giờ hỗ trợ: 08:00 - 21:00 (Thứ 2 - Chủ nhật)
                    </p>
                </div>

                <div class="mt-5 flex flex-wrap gap-2">
                    <span
                        v-for="signal in trustSignals"
                        :key="signal"
                        class="rounded-full border border-white/40 bg-white/20 px-3 py-1 text-[11px] font-semibold tracking-wide text-white"
                    >
                        {{ signal }}
                    </span>
                </div>
            </section>

            <section class="ms-16">
                <h3 class="text-lg font-extrabold tracking-wide uppercase text-white">Khám phá</h3>
                <span class="mt-2 block h-0.5 w-11 bg-white"></span>
                <ul class="mt-4 space-y-2 text-sm text-white/90">
                    <li v-for="item in menuItems" :key="item.label">
                        <a
                            :href="item.href"
                            class="transition hover:text-white hover:font-semibold"
                            >{{ item.label }}</a
                        >
                    </li>
                </ul>
            </section>

            <section>
                <h3 class="text-lg font-extrabold tracking-wide uppercase text-white">Pháp lý & bảo mật</h3>
                <span class="mt-2 block h-0.5 w-11 bg-white"></span>
                <ul class="mt-4 space-y-2 text-sm text-white/90">
                    <li v-for="item in policyItems" :key="item.label">
                        <a
                            :href="item.href"
                            class="transition hover:text-white hover:font-semibold"
                            >{{ item.label }}</a
                        >
                    </li>
                </ul>

                <p class="mt-4 text-xs leading-5 text-white/80">
                    Chúng tôi cam kết bảo vệ dữ liệu người dùng và kiểm duyệt nội dung
                    nhằm duy trì môi trường giao dịch an toàn.
                </p>
            </section>

            <section>
                <h3 class="text-lg font-extrabold tracking-wide uppercase text-white">Liên hệ & xác minh</h3>
                <span class="mt-2 block h-0.5 w-11 bg-white"></span>
                <ul class="mt-4 space-y-2 text-sm text-white/90">
                    <li v-for="item in channelItems" :key="item.label">
                        <a
                            :href="item.href"
                            class="transition hover:text-white hover:font-semibold"
                            >{{ item.label }}</a
                        >
                    </li>
                </ul>

                <div class="mt-4 text-sm">
                    <p class="font-semibold text-white">Hotline: 1900 1881</p>
                    <p class="mt-1 text-white/80">Bộ phận chăm sóc khách hàng luôn sẵn sàng tiếp nhận phản ánh và hỗ trợ xác minh tin đăng.</p>
                </div>

                <div class="mt-4 overflow-hidden rounded-lg">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3754.931495341734!2d105.77583127482015!3d10.027943590078856!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a088212fe0256f%3A0x3346a13a709ee705!2zMTIzIMSQLiAzMCBUaMOhbmcgNCwgWHXDom4gS2jDoW5oLCBOaW5oIEtp4buBdSwgQ-G6p24gVGjGoSwgVmnhu4d0IE5hbQ!5e1!3m2!1svi!2s!4v1776693382881!5m2!1svi!2s"
                        class="h-48 w-full"
                        style="border:0;"
                        :allowfullscreen="true"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="Vị trí văn phòng BatDongSan"
                    ></iframe>
                </div>
            </section>
        </div>

        <div
            class="mx-auto mt-6 max-w-7xl pt-4 text-xs text-white/70"
        >
            © {{ new Date().getFullYear() }} Batdongsan Clone. All rights
            reserved.
        </div>
    </footer>
</template>
