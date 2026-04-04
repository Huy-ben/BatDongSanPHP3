<script setup>
import ClientLayout from '@/layouts/ClientLayout.vue';

const tabBase = ["text-slate-600", "border-transparent"];
    const tabActive = ["text-brand", "border-brand", "active"];

    document.querySelectorAll(".cat-tab").forEach((tab) => {
      tab.addEventListener("click", () => {
        document.querySelectorAll(".cat-tab").forEach((item) => {
          item.classList.remove(...tabActive);
          item.classList.add(...tabBase);
        });
        tab.classList.remove(...tabBase);
        tab.classList.add(...tabActive);
      });
    });

    document.querySelectorAll(".pg-btn:not(.disabled)").forEach((btn) => {
      btn.addEventListener("click", function () {
        if (!this.classList.contains("active")) {
          document.querySelectorAll(".pg-btn").forEach((b) => {
            b.classList.remove("active", "bg-brand", "border-brand", "text-white");
            if (!b.classList.contains("disabled")) {
              b.classList.add("bg-white", "border-slate-200", "text-slate-600");
            }
          });
          this.classList.remove("bg-white", "border-slate-200", "text-slate-600");
          this.classList.add("active", "bg-brand", "border-brand", "text-white");
        }
      });
    });
</script>

<template>
  <ClientLayout>
    <div class="min-h-screen text-slate-900">
      <main class="mx-auto max-w-6xl px-4 py-8 lg:px-8">
        <div class="mb-6 rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
          <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-sm">
            <a href="/" class="font-medium text-slate-500 transition hover:text-slate-800">Trang chủ</a>
            <span class="text-slate-300">/</span>
            <span class="font-semibold text-brand">Tin tức</span>
          </nav>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
          <section class="space-y-6 lg:col-span-2">

          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-2">
            <article class="overflow-hidden rounded-xl border border-slate-200 bg-white transition hover:-translate-y-0.5 hover:border-brand">
              <div class="flex h-36 items-center justify-center overflow-hidden bg-slate-900">
                <svg viewBox="0 0 260 140" width="260" height="140" xmlns="http://www.w3.org/2000/svg">
                  <rect width="260" height="140" fill="#1e2d1e"/>
                  <rect x="20" y="50" width="40" height="80" fill="#2a4a2a" rx="3"/>
                  <rect x="70" y="30" width="50" height="100" fill="#366336" rx="3"/>
                  <rect x="130" y="45" width="35" height="85" fill="#2a4a2a" rx="3"/>
                  <rect x="175" y="20" width="60" height="110" fill="#3d7a3d" rx="3"/>
                  <rect x="0" y="118" width="260" height="22" fill="#111"/>
                  <rect x="73" y="48" width="8" height="6" fill="#90ee90" rx="1" opacity=".6"/>
                  <rect x="84" y="48" width="8" height="6" fill="#90ee90" rx="1" opacity=".4"/>
                  <rect x="180" y="38" width="9" height="7" fill="#F47920" rx="1" opacity=".8"/>
                  <rect x="193" y="38" width="9" height="7" fill="#F47920" rx="1" opacity=".5"/>
                </svg>
              </div>
              <div class="p-4">
                <span class="mb-2 inline-flex rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold text-emerald-700">Quy hoạch</span>
                <h3 class="mb-2 text-sm font-bold leading-5">Dự án vành đai 3 mở ra cơ hội đầu tư BĐS vùng ven năm 2026</h3>
                <p class="mb-3 text-xs leading-5 text-slate-500">Tuyến đường vành đai 3 TP.HCM chính thức thông xe đoạn đầu tiên, tạo cú hích lớn cho thị trường bất động sản các huyện ngoại thành...</p>
                <div class="flex items-center justify-between text-[11px] text-slate-400"><span>28 tháng 3, 2026</span><span>5 phút đọc</span></div>
              </div>
            </article>

            <article class="overflow-hidden rounded-xl border border-slate-200 bg-white transition hover:-translate-y-0.5 hover:border-brand">
              <div class="flex h-36 items-center justify-center overflow-hidden bg-slate-900">
                <svg viewBox="0 0 260 140" width="260" height="140" xmlns="http://www.w3.org/2000/svg">
                  <rect width="260" height="140" fill="#2a1a1a"/>
                  <rect x="30" y="40" width="200" height="70" fill="#3d2020" rx="6"/>
                  <rect x="45" y="55" width="80" height="8" fill="#F47920" rx="2" opacity=".9"/>
                  <rect x="45" y="70" width="140" height="5" fill="#666" rx="2" opacity=".5"/>
                  <rect x="45" y="82" width="110" height="5" fill="#666" rx="2" opacity=".4"/>
                  <rect x="45" y="94" width="60" height="5" fill="#666" rx="2" opacity=".3"/>
                  <circle cx="205" cy="75" r="18" fill="#F47920" opacity=".15"/>
                  <text x="205" y="80" text-anchor="middle" fill="#F47920" font-size="16" font-weight="800" opacity=".8">§</text>
                  <rect x="0" y="118" width="260" height="22" fill="#1a0d0d"/>
                </svg>
              </div>
              <div class="p-4">
                <span class="mb-2 inline-flex rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-bold text-blue-700">Pháp lý</span>
                <h3 class="mb-2 text-sm font-bold leading-5">Luật Đất đai 2024 có hiệu lực toàn diện: Những thay đổi quan trọng nhất</h3>
                <p class="mb-3 text-xs leading-5 text-slate-500">Các quy định mới về sổ đỏ, chuyển nhượng quyền sử dụng đất và bảng giá đất chính thức có hiệu lực từ tháng 1/2026...</p>
                <div class="flex items-center justify-between text-[11px] text-slate-400"><span>25 tháng 3, 2026</span><span>8 phút đọc</span></div>
              </div>
            </article>

            <article class="overflow-hidden rounded-xl border border-slate-200 bg-white transition hover:-translate-y-0.5 hover:border-brand">
              <div class="flex h-36 items-center justify-center overflow-hidden bg-slate-900">
                <svg viewBox="0 0 260 140" width="260" height="140" xmlns="http://www.w3.org/2000/svg">
                  <rect width="260" height="140" fill="#1a1a2e"/>
                  <polyline points="20,100 60,80 100,90 140,55 180,60 220,35 260,40" fill="none" stroke="#F47920" stroke-width="2.5"/>
                  <polyline points="20,100 60,80 100,90 140,55 180,60 220,35 260,40 260,140 20,140" fill="#F47920" opacity=".08"/>
                  <line x1="20" y1="120" x2="240" y2="120" stroke="#333" stroke-width="0.5"/>
                  <line x1="20" y1="40" x2="20" y2="120" stroke="#333" stroke-width="0.5"/>
                  <circle cx="220" cy="35" r="4" fill="#F47920"/>
                  <circle cx="140" cy="55" r="4" fill="#F47920"/>
                  <rect x="0" y="125" width="260" height="15" fill="#111"/>
                </svg>
              </div>
              <div class="p-4">
                <span class="mb-2 inline-flex rounded-full bg-green-100 px-2 py-0.5 text-[10px] font-bold text-green-700">Đầu tư</span>
                <h3 class="mb-2 text-sm font-bold leading-5">Lãi suất ngân hàng giảm mạnh, dòng tiền có quay lại BĐS?</h3>
                <p class="mb-3 text-xs leading-5 text-slate-500">Nhiều ngân hàng hạ lãi suất cho vay mua nhà xuống mức thấp kỷ lục, tạo điều kiện thuận lợi cho người mua nhà lần đầu...</p>
                <div class="flex items-center justify-between text-[11px] text-slate-400"><span>22 tháng 3, 2026</span><span>4 phút đọc</span></div>
              </div>
            </article>

            <article class="overflow-hidden rounded-xl border border-slate-200 bg-white transition hover:-translate-y-0.5 hover:border-brand">
              <div class="flex h-36 items-center justify-center overflow-hidden bg-slate-900">
                <svg viewBox="0 0 260 140" width="260" height="140" xmlns="http://www.w3.org/2000/svg">
                  <rect width="260" height="140" fill="#1e1a2a"/>
                  <rect x="25" y="30" width="65" height="90" fill="#2d2060" rx="4"/>
                  <rect x="100" y="50" width="55" height="70" fill="#3d2a80" rx="4"/>
                  <rect x="165" y="20" width="70" height="100" fill="#4a3490" rx="4"/>
                  <rect x="28" y="45" width="10" height="7" fill="#b39ddb" rx="1" opacity=".7"/>
                  <rect x="42" y="45" width="10" height="7" fill="#b39ddb" rx="1" opacity=".5"/>
                  <rect x="170" y="38" width="12" height="9" fill="#F47920" rx="1" opacity=".9"/>
                  <rect x="186" y="38" width="12" height="9" fill="#F47920" rx="1" opacity=".6"/>
                  <rect x="170" y="54" width="12" height="9" fill="#b39ddb" rx="1" opacity=".5"/>
                  <rect x="0" y="118" width="260" height="22" fill="#0d0a1a"/>
                </svg>
              </div>
              <div class="p-4">
                <span class="mb-2 inline-flex rounded-full bg-pink-100 px-2 py-0.5 text-[10px] font-bold text-pink-700">Phong thủy</span>
                <h3 class="mb-2 text-sm font-bold leading-5">Phong thủy nhà ở 2026: Những điều cần lưu ý khi mua nhà năm Bính Ngọ</h3>
                <p class="mb-3 text-xs leading-5 text-slate-500">Theo các chuyên gia phong thủy, năm 2026 cần chú ý hướng nhà, tuổi chủ nhà và vị trí cửa chính để đảm bảo may mắn...</p>
                <div class="flex items-center justify-between text-[11px] text-slate-400"><span>20 tháng 3, 2026</span><span>5 phút đọc</span></div>
              </div>
            </article>
          </div>



          <div class="flex items-center justify-center gap-1.5 pt-2">
            <button class="pg-btn disabled flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-300">‹</button>
            <button class="pg-btn active flex h-8 w-8 items-center justify-center rounded-lg border border-brand bg-brand text-sm font-semibold text-white">1</button>
            <button class="pg-btn flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-sm font-semibold text-slate-600 transition hover:border-brand hover:text-brand">2</button>
            <button class="pg-btn flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-sm font-semibold text-slate-600 transition hover:border-brand hover:text-brand">3</button>
            <button class="pg-btn flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-sm font-semibold text-slate-600 transition hover:border-brand hover:text-brand">4</button>
            <span class="px-1 text-sm text-slate-400">...</span>
            <button class="pg-btn flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-sm font-semibold text-slate-600 transition hover:border-brand hover:text-brand">12</button>
            <button class="pg-btn flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-sm font-semibold text-slate-600 transition hover:border-brand hover:text-brand">›</button>
          </div>
        </section>

        <aside class="space-y-5">
          <section class="rounded-xl border border-slate-200 bg-white p-5">
            <div class="mb-4 border-b border-slate-200 pb-3">
              <h3 class="inline-block border-b-2 border-brand pb-1 text-sm font-extrabold">Tìm kiếm</h3>
            </div>
            <div class="flex gap-2">
              <input type="text" placeholder="Nhập từ khóa..." class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-brand" />
              <button class="rounded-lg bg-brand px-4 py-2 text-sm font-bold text-white">Tìm</button>
            </div>
          </section>

          <section class="rounded-xl border border-slate-200 bg-white p-5">
            <div class="mb-3 border-b border-slate-200 pb-3">
              <h3 class="inline-block border-b-2 border-brand pb-1 text-sm font-extrabold">Bài viết mới nhất</h3>
            </div>
            <div class="divide-y divide-slate-100">
              <div class="flex gap-3 py-2.5"><div class="min-w-6 text-2xl font-extrabold leading-none text-slate-200">01</div><div><p class="text-xs font-semibold leading-5 text-slate-800">Cơn sốt BĐS phía Đông và cơ hội cho nhà đầu tư</p><p class="text-xs text-slate-400">01 tháng 4, 2026</p></div></div>
              <div class="flex gap-3 py-2.5"><div class="min-w-6 text-2xl font-extrabold leading-none text-slate-200">02</div><div><p class="text-xs font-semibold leading-5 text-slate-800">Vành đai 3 mở ra cơ hội vùng ven TP.HCM</p><p class="text-xs text-slate-400">28 tháng 3, 2026</p></div></div>
              <div class="flex gap-3 py-2.5"><div class="min-w-6 text-2xl font-extrabold leading-none text-slate-200">03</div><div><p class="text-xs font-semibold leading-5 text-slate-800">Luật Đất đai 2024: Những thay đổi quan trọng</p><p class="text-xs text-slate-400">25 tháng 3, 2026</p></div></div>
              <div class="flex gap-3 py-2.5"><div class="min-w-6 text-2xl font-extrabold leading-none text-slate-200">04</div><div><p class="text-xs font-semibold leading-5 text-slate-800">Lãi suất giảm, dòng tiền có quay lại BĐS?</p><p class="text-xs text-slate-400">22 tháng 3, 2026</p></div></div>
              <div class="flex gap-3 py-2.5"><div class="min-w-6 text-2xl font-extrabold leading-none text-slate-200">05</div><div><p class="text-xs font-semibold leading-5 text-slate-800">Phong thủy nhà ở năm 2026: Lưu ý quan trọng</p><p class="text-xs text-slate-400">20 tháng 3, 2026</p></div></div>
            </div>
          </section>

          <section class="rounded-xl border border-slate-200 bg-white p-5">
            <div class="mb-3 border-b border-slate-200 pb-3">
              <h3 class="inline-block border-b-2 border-brand pb-1 text-sm font-extrabold">Danh mục</h3>
            </div>
            <div class="space-y-1.5">
              <button class="flex w-full items-center justify-between rounded-lg px-2.5 py-2 transition hover:bg-orange-50"><span class="text-sm font-medium text-slate-700">Thị trường</span><span class="rounded-full bg-orange-100 px-2 py-0.5 text-xs font-bold text-orange-700">48</span></button>
              <button class="flex w-full items-center justify-between rounded-lg px-2.5 py-2 transition hover:bg-orange-50"><span class="text-sm font-medium text-slate-700">Pháp lý</span><span class="rounded-full bg-orange-100 px-2 py-0.5 text-xs font-bold text-orange-700">32</span></button>
              <button class="flex w-full items-center justify-between rounded-lg px-2.5 py-2 transition hover:bg-orange-50"><span class="text-sm font-medium text-slate-700">Đầu tư</span><span class="rounded-full bg-orange-100 px-2 py-0.5 text-xs font-bold text-orange-700">27</span></button>
              <button class="flex w-full items-center justify-between rounded-lg px-2.5 py-2 transition hover:bg-orange-50"><span class="text-sm font-medium text-slate-700">Quy hoạch</span><span class="rounded-full bg-orange-100 px-2 py-0.5 text-xs font-bold text-orange-700">19</span></button>
              <button class="flex w-full items-center justify-between rounded-lg px-2.5 py-2 transition hover:bg-orange-50"><span class="text-sm font-medium text-slate-700">Phong thủy</span><span class="rounded-full bg-orange-100 px-2 py-0.5 text-xs font-bold text-orange-700">14</span></button>
            </div>
          </section>
        </aside>
      </div>
    </main>
  </div>
    </ClientLayout>
</template>


<style scope>

</style>