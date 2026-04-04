<script setup>
import ClientLayout from '@/layouts/ClientLayout.vue';
</script>

<template>
  <ClientLayout>
    <div class="min-h-screen text-slate-900">
      <main class="mx-auto max-w-6xl px-4 py-8 lg:px-8">
        <div class="mb-6 rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
          <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-sm">
            <a href="/" class="font-medium text-slate-500 transition hover:text-slate-800">Trang chủ</a>
            <span class="text-slate-300">/</span>
            <a href="/blog" class="font-medium text-slate-500 transition hover:text-slate-800">Tin tức</a>
            <span class="text-slate-300">/</span>
            <span class="line-clamp-1 font-semibold text-brand">Dự án vành đai 3 mở ra cơ hội đầu tư BĐS vùng ven năm 2026</span>
          </nav>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
          <section class="space-y-6 lg:col-span-2">
            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
              <div class="border-b border-slate-100 px-5 py-5 sm:px-7">
                <span class="mb-3 inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-bold text-emerald-700">
                  Quy hoạch
                </span>
                <h1 class="mb-4 text-2xl font-black leading-tight text-slate-900 sm:text-3xl">
                  Dự án vành đai 3 mở ra cơ hội đầu tư BĐS vùng ven năm 2026
                </h1>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-slate-500">
                  <span><i class="fa-regular fa-calendar mr-1"></i>28 tháng 3, 2026</span>
                </div>
              </div>

              <div class="flex h-64 items-center justify-center overflow-hidden bg-slate-900 sm:h-80">
                <svg viewBox="0 0 260 140" width="520" height="280" xmlns="http://www.w3.org/2000/svg" class="h-full w-full object-cover">
                  <rect width="260" height="140" fill="#1e2d1e" />
                  <rect x="20" y="50" width="40" height="80" fill="#2a4a2a" rx="3" />
                  <rect x="70" y="30" width="50" height="100" fill="#366336" rx="3" />
                  <rect x="130" y="45" width="35" height="85" fill="#2a4a2a" rx="3" />
                  <rect x="175" y="20" width="60" height="110" fill="#3d7a3d" rx="3" />
                  <rect x="0" y="118" width="260" height="22" fill="#111" />
                  <rect x="73" y="48" width="8" height="6" fill="#90ee90" rx="1" opacity=".6" />
                  <rect x="84" y="48" width="8" height="6" fill="#90ee90" rx="1" opacity=".4" />
                  <rect x="180" y="38" width="9" height="7" fill="#F47920" rx="1" opacity=".8" />
                  <rect x="193" y="38" width="9" height="7" fill="#F47920" rx="1" opacity=".5" />
                </svg>
              </div>

              <div class="space-y-5 px-5 py-6 text-[15px] leading-7 text-slate-700 sm:px-7">
                <p>
                  Việc thông xe đoạn đầu tiên của tuyến vành đai 3 TP.HCM đang tạo ra một cú hích mới cho thị trường bất động sản vùng ven.
                  Các khu vực kết nối trực tiếp với tuyến đường này ghi nhận lượng tìm kiếm tăng mạnh, đặc biệt ở phân khúc đất nền pháp lý rõ ràng
                  và nhà phố có khả năng khai thác kinh doanh.
                </p>

                <p>
                  Theo đánh giá của nhiều đơn vị nghiên cứu thị trường, mặt bằng giá tại một số điểm nóng đã dịch chuyển từ 8% đến 15% chỉ trong
                  quý đầu năm 2026. Tuy nhiên, chuyên gia khuyến nghị nhà đầu tư không chạy theo tâm lý đám đông mà cần đánh giá kỹ các yếu tố
                  về hạ tầng kết nối thực tế, mật độ dân cư và tiến độ hoàn thiện tiện ích xung quanh.
                </p>

                <blockquote class="rounded-xl border-l-4 border-brand bg-orange-50 px-4 py-3 text-slate-800">
                  "Hạ tầng luôn là trục dẫn dắt dòng tiền trung và dài hạn. Nhưng cơ hội tốt nhất chỉ dành cho người mua có kế hoạch tài chính rõ ràng
                  và tầm nhìn đủ dài." - Chuyên gia thị trường BĐS
                </blockquote>

                <h2 class="text-xl font-extrabold text-slate-900">3 yếu tố cần kiểm tra trước khi xuống tiền</h2>
                <ul class="list-disc space-y-2 pl-5">
                  <li>Tính pháp lý của dự án và khả năng ra sổ trong khung thời gian cụ thể.</li>
                  <li>Khoảng cách kết nối thực tế từ dự án đến trục vành đai và các nút giao chính.</li>
                  <li>Khả năng khai thác dòng tiền: cho thuê, kinh doanh hoặc thanh khoản khi cần thoát hàng.</li>
                </ul>

                <p>
                  Trong bối cảnh lãi suất duy trì ở mức thấp, bất động sản vùng ven vẫn là nhóm được quan tâm nhiều trong năm 2026. Dù vậy,
                  chiến lược phù hợp ở giai đoạn này là ưu tiên sản phẩm có giá trị sử dụng thật, tránh sử dụng đòn bẩy quá cao để hạn chế áp lực
                  tài chính khi thị trường điều chỉnh ngắn hạn.
                </p>
              </div>

              <div class="border-t border-slate-100 px-5 py-4 sm:px-7">

                <div class="flex flex-wrap items-center gap-2">
                  <span class="text-sm font-semibold text-slate-700">Chia sẻ:</span>
                  <button class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-500 transition hover:border-brand hover:text-brand">
                    <i class="fa-brands fa-facebook-f"></i>
                  </button>
                  <button class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-500 transition hover:border-brand hover:text-brand">
                    <i class="fa-brands fa-x-twitter"></i>
                  </button>
                  <button class="inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 text-slate-500 transition hover:border-brand hover:text-brand">
                    <i class="fa-brands fa-linkedin-in"></i>
                  </button>
                </div>
              </div>
            </article>

            <section class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
              <h3 class="mb-4 text-lg font-extrabold text-slate-900">Bài viết liên quan</h3>
              <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <a href="/blog" class="rounded-xl border border-slate-200 p-4 transition hover:-translate-y-0.5 hover:border-brand">
                  <p class="mb-2 text-xs font-bold uppercase tracking-wide text-brand">Pháp lý</p>
                  <h4 class="mb-2 text-sm font-bold leading-5 text-slate-900">Luật Đất đai 2024 có hiệu lực toàn diện từ 2026</h4>
                  <p class="text-xs text-slate-500">Cập nhật nhóm quy định mới ảnh hưởng trực tiếp đến nhà đầu tư cá nhân.</p>
                </a>
                <a href="/blog" class="rounded-xl border border-slate-200 p-4 transition hover:-translate-y-0.5 hover:border-brand">
                  <p class="mb-2 text-xs font-bold uppercase tracking-wide text-brand">Đầu tư</p>
                  <h4 class="mb-2 text-sm font-bold leading-5 text-slate-900">Lãi suất thấp và câu chuyện dòng tiền quay lại BĐS</h4>
                  <p class="text-xs text-slate-500">So sánh các kênh giữ tiền 2026 và vị trí của bất động sản trong danh mục.</p>
                </a>
              </div>
            </section>
          </section>

          <aside class="space-y-5">
            <section class="rounded-xl border border-slate-200 bg-white p-5">
              <div class="mb-4 border-b border-slate-200 pb-3">
                <h3 class="inline-block border-b-2 border-brand pb-1 text-sm font-extrabold">Tìm kiếm</h3>
              </div>
              <div class="flex gap-2">
                <input
                  type="text"
                  placeholder="Nhập từ khóa..."
                  class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-brand"
                />
                <button class="rounded-lg bg-brand px-4 py-2 text-sm font-bold text-white">Tìm</button>
              </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white p-5">
              <div class="mb-3 border-b border-slate-200 pb-3">
                <h3 class="inline-block border-b-2 border-brand pb-1 text-sm font-extrabold">Bài viết mới nhất</h3>
              </div>
              <div class="divide-y divide-slate-100">
                <div class="flex gap-3 py-2.5">
                  <div class="min-w-6 text-2xl font-extrabold leading-none text-slate-200">01</div>
                  <div>
                    <p class="text-xs font-semibold leading-5 text-slate-800">Cơn sốt BĐS phía Đông và cơ hội cho nhà đầu tư</p>
                    <p class="text-xs text-slate-400">01 tháng 4, 2026</p>
                  </div>
                </div>
                <div class="flex gap-3 py-2.5">
                  <div class="min-w-6 text-2xl font-extrabold leading-none text-slate-200">02</div>
                  <div>
                    <p class="text-xs font-semibold leading-5 text-slate-800">Vành đai 3 mở ra cơ hội vùng ven TP.HCM</p>
                    <p class="text-xs text-slate-400">28 tháng 3, 2026</p>
                  </div>
                </div>
                <div class="flex gap-3 py-2.5">
                  <div class="min-w-6 text-2xl font-extrabold leading-none text-slate-200">03</div>
                  <div>
                    <p class="text-xs font-semibold leading-5 text-slate-800">Luật Đất đai 2024: Những thay đổi quan trọng</p>
                    <p class="text-xs text-slate-400">25 tháng 3, 2026</p>
                  </div>
                </div>
                <div class="flex gap-3 py-2.5">
                  <div class="min-w-6 text-2xl font-extrabold leading-none text-slate-200">04</div>
                  <div>
                    <p class="text-xs font-semibold leading-5 text-slate-800">Lãi suất giảm, dòng tiền có quay lại BĐS?</p>
                    <p class="text-xs text-slate-400">22 tháng 3, 2026</p>
                  </div>
                </div>
                <div class="flex gap-3 py-2.5">
                  <div class="min-w-6 text-2xl font-extrabold leading-none text-slate-200">05</div>
                  <div>
                    <p class="text-xs font-semibold leading-5 text-slate-800">Phong thủy nhà ở năm 2026: Lưu ý quan trọng</p>
                    <p class="text-xs text-slate-400">20 tháng 3, 2026</p>
                  </div>
                </div>
              </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white p-5">
              <div class="mb-3 border-b border-slate-200 pb-3">
                <h3 class="inline-block border-b-2 border-brand pb-1 text-sm font-extrabold">Danh mục</h3>
              </div>
              <div class="space-y-1.5">
                <button class="flex w-full items-center justify-between rounded-lg px-2.5 py-2 transition hover:bg-orange-50">
                  <span class="text-sm font-medium text-slate-700">Thị trường</span>
                  <span class="rounded-full bg-orange-100 px-2 py-0.5 text-xs font-bold text-orange-700">48</span>
                </button>
                <button class="flex w-full items-center justify-between rounded-lg px-2.5 py-2 transition hover:bg-orange-50">
                  <span class="text-sm font-medium text-slate-700">Pháp lý</span>
                  <span class="rounded-full bg-orange-100 px-2 py-0.5 text-xs font-bold text-orange-700">32</span>
                </button>
                <button class="flex w-full items-center justify-between rounded-lg px-2.5 py-2 transition hover:bg-orange-50">
                  <span class="text-sm font-medium text-slate-700">Đầu tư</span>
                  <span class="rounded-full bg-orange-100 px-2 py-0.5 text-xs font-bold text-orange-700">27</span>
                </button>
                <button class="flex w-full items-center justify-between rounded-lg px-2.5 py-2 transition hover:bg-orange-50">
                  <span class="text-sm font-medium text-slate-700">Quy hoạch</span>
                  <span class="rounded-full bg-orange-100 px-2 py-0.5 text-xs font-bold text-orange-700">19</span>
                </button>
                <button class="flex w-full items-center justify-between rounded-lg px-2.5 py-2 transition hover:bg-orange-50">
                  <span class="text-sm font-medium text-slate-700">Phong thủy</span>
                  <span class="rounded-full bg-orange-100 px-2 py-0.5 text-xs font-bold text-orange-700">14</span>
                </button>
              </div>
            </section>
          </aside>
        </div>
      </main>
    </div>
  </ClientLayout>
</template>