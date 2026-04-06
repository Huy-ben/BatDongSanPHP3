<script setup>
import axios from 'axios';
import { reactive, ref } from 'vue';
import ClientLayout from '@/layouts/ClientLayout.vue';

const isSubmitting = ref(false);
const submitSuccess = ref('');
const submitError = ref('');

const subjectOptions = [
  'Tư vấn mua bán bất động sản',
  'Tư vấn cho thuê',
  'Hợp tác đăng tin',
  'Khiếu nại / Phản ánh',
  'Khác',
];

const form = reactive({
  name: '',
  phone: '',
  email: '',
  subject: '',
  message: '',
  agreed: false,
});

const resetForm = () => {
  form.name = '';
  form.phone = '';
  form.email = '';
  form.subject = '';
  form.message = '';
  form.agreed = false;
};

const handleSubmit = async () => {
  submitSuccess.value = '';
  submitError.value = '';
  isSubmitting.value = true;

  try {
    const response = await axios.post('/api/contact', form);
    submitSuccess.value = response.data?.message ?? 'Gửi liên hệ thành công.';
    resetForm();
  } catch (error) {
    if (error.response?.status === 422 && error.response?.data?.errors) {
      const firstError = Object.values(error.response.data.errors)[0];
      submitError.value = Array.isArray(firstError)
        ? firstError[0]
        : 'Thông tin không hợp lệ. Vui lòng kiểm tra lại.';
    } else {
      submitError.value = 'Có lỗi xảy ra khi gửi liên hệ. Vui lòng thử lại sau.';
    }
  } finally {
    isSubmitting.value = false;
  }
};
</script>
<template>
    <ClientLayout>
        <body class="bg-gray-50 text-gray-800">

  <!-- ===================== BREADCRUMB ===================== -->
  <div class="max-w-7xl mx-auto px-6 py-4">
    <nav class="flex items-center gap-2 text-sm text-gray-500">
      <a href="#" class="hover:text-orange-500 transition-colors">Trang chủ</a>
      <span class="text-gray-300">/</span>
      <span class="text-orange-500 font-medium">Liên hệ</span>
    </nav>
  </div>

  <!-- ===================== HERO BANNER ===================== -->
  <section class="max-w-7xl mx-auto px-6 mb-10 fade-up">
    <div class="relative rounded-2xl overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 h-48 flex items-center px-10">
      <!-- Decorative circles -->
      <div class="absolute -right-10 -top-10 w-56 h-56 bg-orange-500 rounded-full opacity-10"></div>
      <div class="absolute right-32 bottom-0 w-32 h-32 bg-orange-400 rounded-full opacity-10"></div>
      <div class="absolute right-10 top-8 w-16 h-16 bg-orange-300 rounded-full opacity-10"></div>

      <div class="relative z-10">
        <p class="text-orange-400 text-sm font-semibold uppercase tracking-widest mb-1">Kết nối với chúng tôi</p>
        <h1 class="text-white text-3xl font-extrabold leading-tight">Liên hệ – BTB Bất động sản</h1>
        <p class="text-gray-400 text-sm mt-2">Đội ngũ chuyên gia sẵn sàng tư vấn miễn phí 7 ngày / tuần</p>
      </div>
    </div>
  </section>

  <!-- ===================== MAIN CONTENT ===================== -->
  <main class="max-w-7xl mx-auto px-6 pb-16">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

      <!-- LEFT: Contact Form -->
      <div class="lg:col-span-2 fade-up delay-1">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
          <h2 class="section-title text-lg font-bold text-gray-900 mb-6">Gửi tin nhắn cho chúng tôi</h2>

          <form class="space-y-5" @submit.prevent="handleSubmit">
            <!-- Row 1 -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Họ và tên <span class="text-orange-500">*</span></label>
                <input v-model="form.name" type="text" placeholder="Nguyễn Văn A"
                  autocomplete="name" required
                  class="form-input w-full border border-gray-200 rounded-xl px-4 py-3 text-sm bg-gray-50 transition-all placeholder-gray-400" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Số điện thoại <span class="text-orange-500">*</span></label>
                <input v-model="form.phone" type="tel" placeholder="0901 234 567"
                  autocomplete="tel" required
                  class="form-input w-full border border-gray-200 rounded-xl px-4 py-3 text-sm bg-gray-50 transition-all placeholder-gray-400" />
              </div>
            </div>

            <!-- Row 2 -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
              <input v-model="form.email" type="email" placeholder="email@example.com"
                autocomplete="email"
                class="form-input w-full border border-gray-200 rounded-xl px-4 py-3 text-sm bg-gray-50 transition-all placeholder-gray-400" />
            </div>

            <!-- Row 3: Subject -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1.5">Chủ đề <span class="text-orange-500">*</span></label>
              <select v-model="form.subject" required class="form-input w-full border border-gray-200 rounded-xl px-4 py-3 text-sm bg-gray-50 transition-all text-gray-700 appearance-none cursor-pointer">
                <option value="">-- Chọn chủ đề --</option>
                <option v-for="option in subjectOptions" :key="option" :value="option">{{ option }}</option>
              </select>
            </div>

            <!-- Row 4: Message -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1.5">Nội dung <span class="text-orange-500">*</span></label>
              <textarea v-model="form.message" rows="5" placeholder="Nhập nội dung cần tư vấn hoặc phản hồi..."
                required
                class="form-input w-full border border-gray-200 rounded-xl px-4 py-3 text-sm bg-gray-50 transition-all placeholder-gray-400 resize-none"></textarea>
            </div>

            <!-- Checkbox -->
            <label class="flex items-start gap-3 cursor-pointer group">
              <input v-model="form.agreed" type="checkbox" required class="mt-0.5 w-4 h-4 accent-orange-500 cursor-pointer" />
              <span class="text-sm text-gray-500 group-hover:text-gray-700 transition-colors">
                Tôi đồng ý với <a href="#" class="text-orange-500 hover:underline font-medium">Điều khoản sử dụng</a> và <a href="#" class="text-orange-500 hover:underline font-medium">Chính sách bảo mật</a> của BTB.
              </span>
            </label>

            <p v-if="submitSuccess" class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
              {{ submitSuccess }}
            </p>
            <p v-if="submitError" class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
              {{ submitError }}
            </p>

            <!-- Submit -->
            <button type="submit" :disabled="isSubmitting"
              class="btn-send bg-orange-500 hover:bg-orange-600 text-white font-semibold px-8 py-3 rounded-xl transition-colors text-sm flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
              </svg>
              {{ isSubmitting ? 'Đang gửi...' : 'Gửi tin nhắn' }}
            </button>
          </form>
        </div>

        <!-- Map -->
        <div class="mt-8 map-wrap rounded-2xl overflow-hidden fade-up delay-2">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d62915.655624327185!2d105.7468858!3d10.0452388!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a0629f6de3b3c9%3A0x942f957a7bd84d9e!2zQ%E1%BA%A7n+Ninh+S%C3%B4ng%2C+C%E1%BA%A7n+Th%C6%A1!5e0!3m2!1svi!2s!4v1700000000000"
            width="100%" height="320" style="border:0; display:block;" allowfullscreen loading="lazy"
            referrerpolicy="no-referrer-when-downgrade" class="rounded-2xl">
          </iframe>
        </div>
      </div>

      <!-- RIGHT: Info + Socials -->
      <div class="space-y-5 fade-up delay-2">

        <!-- Phone -->
        <div class="info-card bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex gap-4 items-start">
          <div class="w-11 h-11 bg-orange-100 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
            </svg>
          </div>
          <div>
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-1">Hotline hỗ trợ</p>
            <p class="text-lg font-bold text-gray-900">1900 1881</p>
            <p class="text-xs text-gray-400 mt-0.5">Thứ 2 – CN: 8:00 – 21:00</p>
          </div>
        </div>

        <!-- Email -->
        <div class="info-card bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex gap-4 items-start">
          <div class="w-11 h-11 bg-orange-100 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
          </div>
          <div>
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-1">Email liên hệ</p>
            <p class="text-sm font-semibold text-gray-900">support@btb.vn</p>
            <p class="text-xs text-gray-400 mt-0.5">Phản hồi trong vòng 24 giờ</p>
          </div>
        </div>

        <!-- Address -->
        <div class="info-card bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex gap-4 items-start">
          <div class="w-11 h-11 bg-orange-100 rounded-xl flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
          </div>
          <div>
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-1">Văn phòng chính</p>
            <p class="text-sm font-semibold text-gray-900 leading-snug">123 Đường 30/4, Phường Xuân Khánh</p>
            <p class="text-xs text-gray-400 mt-0.5">Quận Ninh Kiều, Cần Thơ</p>
          </div>
        </div>

        <!-- Working hours -->
        <div class="info-card bg-white rounded-2xl shadow-sm border border-gray-100 p-6 fade-up delay-3">
          <h3 class="section-title text-sm font-bold text-gray-900 mb-4">Giờ làm việc</h3>
          <ul class="space-y-2.5 text-sm">
            <li class="flex justify-between">
              <span class="text-gray-500">Thứ 2 – Thứ 6</span>
              <span class="font-semibold text-gray-900">08:00 – 21:00</span>
            </li>
            <li class="flex justify-between">
              <span class="text-gray-500">Thứ 7</span>
              <span class="font-semibold text-gray-900">08:00 – 18:00</span>
            </li>
            <li class="flex justify-between">
              <span class="text-gray-500">Chủ nhật</span>
              <span class="font-semibold text-orange-500">09:00 – 17:00</span>
            </li>
          </ul>
          <div class="mt-4 bg-orange-50 border border-orange-100 rounded-xl px-4 py-3 flex items-center gap-2">
            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
            <p class="text-xs text-orange-700 font-medium">Đang hoạt động – Sẵn sàng hỗ trợ</p>
          </div>
        </div>

        <!-- Social -->
        <div class="info-card bg-white rounded-2xl shadow-sm border border-gray-100 p-6 fade-up delay-4">
          <h3 class="section-title text-sm font-bold text-gray-900 mb-4">Kết nối mạng xã hội</h3>
          <div class="space-y-3">
            <a href="#" class="flex items-center gap-3 group">
              <div class="w-9 h-9 bg-blue-600 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
              </div>
              <div>
                <p class="text-sm font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">Facebook Fanpage</p>
                <p class="text-xs text-gray-400">fb.com/btbbatdongsan</p>
              </div>
            </a>

            <a href="#" class="flex items-center gap-3 group">
              <div class="w-9 h-9 bg-red-500 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                </svg>
              </div>
              <div>
                <p class="text-sm font-semibold text-gray-900 group-hover:text-red-500 transition-colors">YouTube Channel</p>
                <p class="text-xs text-gray-400">youtube.com/BTBBatDongSan</p>
              </div>
            </a>

            <a href="#" class="flex items-center gap-3 group">
              <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform"
                style="background: linear-gradient(135deg,#f9a825,#e53935,#8e24aa)">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                </svg>
              </div>
              <div>
                <p class="text-sm font-semibold text-gray-900 group-hover:text-pink-600 transition-colors">Instagram</p>
                <p class="text-xs text-gray-400">@btb.batdongsan</p>
              </div>
            </a>
          </div>
        </div>

      </div>
    </div>
  </main>

  <!-- ===================== FAQ STRIP ===================== -->
  <section class="bg-white border-t border-gray-100 py-12">
    <div class="max-w-7xl mx-auto px-6">
      <h2 class="section-title text-lg font-bold text-gray-900 mb-7">Câu hỏi thường gặp</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <details class="group bg-gray-50 rounded-xl border border-gray-100 px-5 py-4 cursor-pointer">
          <summary class="flex items-center justify-between font-semibold text-sm text-gray-900 list-none">
            Tôi có thể đăng tin bất động sản miễn phí không?
            <svg class="w-4 h-4 text-orange-500 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
          </summary>
          <p class="mt-3 text-sm text-gray-500 leading-relaxed">Có, BTB cung cấp gói đăng tin miễn phí cho cá nhân với tối đa 3 tin/ tháng. Để đăng không giới hạn, bạn có thể nâng cấp lên gói Pro.</p>
        </details>

        <details class="group bg-gray-50 rounded-xl border border-gray-100 px-5 py-4 cursor-pointer">
          <summary class="flex items-center justify-between font-semibold text-sm text-gray-900 list-none">
            Làm thế nào để liên hệ tư vấn trực tiếp?
            <svg class="w-4 h-4 text-orange-500 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
          </summary>
          <p class="mt-3 text-sm text-gray-500 leading-relaxed">Bạn có thể gọi hotline <strong class="text-gray-700">1900 1881</strong>, chat trực tiếp qua Facebook hoặc điền form liên hệ trên trang này. Đội ngũ tư vấn sẽ phản hồi trong 15 phút trong giờ hành chính.</p>
        </details>

        <details class="group bg-gray-50 rounded-xl border border-gray-100 px-5 py-4 cursor-pointer">
          <summary class="flex items-center justify-between font-semibold text-sm text-gray-900 list-none">
            BTB có hỗ trợ pháp lý bất động sản không?
            <svg class="w-4 h-4 text-orange-500 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
          </summary>
          <p class="mt-3 text-sm text-gray-500 leading-relaxed">BTB có đội ngũ chuyên gia pháp lý hỗ trợ kiểm tra sổ đỏ, hợp đồng chuyển nhượng, thủ tục công chứng. Liên hệ để được tư vấn chi tiết và miễn phí.</p>
        </details>

        <details class="group bg-gray-50 rounded-xl border border-gray-100 px-5 py-4 cursor-pointer">
          <summary class="flex items-center justify-between font-semibold text-sm text-gray-900 list-none">
            Phí dịch vụ môi giới của BTB là bao nhiêu?
            <svg class="w-4 h-4 text-orange-500 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
          </summary>
          <p class="mt-3 text-sm text-gray-500 leading-relaxed">Phí môi giới thông thường từ 1–2% giá trị giao dịch, được thanh toán khi hợp đồng được ký kết thành công. Không phát sinh phí trước khi giao dịch hoàn tất.</p>
        </details>

      </div>
    </div>
  </section>
</body>
    </ClientLayout>
</template>
  <style scoped>
    * { font-family: 'Be Vietnam Pro', sans-serif; }

    /* Animated underline for nav */
    .nav-link { position: relative; }
    .nav-link::after {
      content: '';
      position: absolute;
      bottom: -4px; left: 0;
      width: 0; height: 2px;
      background: #f97316;
      transition: width .25s ease;
    }
    .nav-link:hover::after { width: 100%; }
    .nav-link.active::after { width: 100%; }

    /* Card hover lift */
    .info-card {
      transition: transform .25s ease, box-shadow .25s ease;
    }
    .info-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 20px 40px rgba(249,115,22,.12);
    }

    /* Input focus ring */
    .form-input:focus {
      outline: none;
      border-color: #f97316;
      box-shadow: 0 0 0 3px rgba(249,115,22,.15);
    }

    /* Send button pulse */
    .btn-send {
      position: relative;
      overflow: hidden;
    }
    .btn-send::after {
      content: '';
      position: absolute;
      inset: 0;
      background: rgba(255,255,255,.2);
      transform: scaleX(0);
      transform-origin: left;
      transition: transform .3s ease;
    }
    .btn-send:hover::after { transform: scaleX(1); }

    /* Fade-in on load */
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(24px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .fade-up { animation: fadeUp .55s ease both; }
    .delay-1 { animation-delay: .1s; }
    .delay-2 { animation-delay: .2s; }
    .delay-3 { animation-delay: .3s; }
    .delay-4 { animation-delay: .4s; }

    /* Orange accent line */
    .section-title::before {
      content: '';
      display: inline-block;
      width: 4px; height: 22px;
      background: #f97316;
      border-radius: 2px;
      margin-right: 10px;
      vertical-align: middle;
    }

    /* Map iframe overlay effect */
    .map-wrap { position: relative; }
    .map-wrap::after {
      content: '';
      position: absolute;
      inset: 0;
      pointer-events: none;
      border: 3px solid #f97316;
      border-radius: 16px;
    }
  </style>