<script setup>
import ClientLayout from '@/layouts/ClientLayout.vue';
import { computed, onMounted, ref } from 'vue';

const blog = ref(null);
const allBlogs = ref([]);
const latestBlogs = ref([]);
const loading = ref(true);
const error = ref('');

const blogId =
  typeof window !== 'undefined'
    ? new URLSearchParams(window.location.search).get('id')
    : null;

const formatDate = (value) => {
  if (!value) {
    return 'Chưa cập nhật';
  }

  return new Intl.DateTimeFormat('vi-VN', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
  }).format(new Date(value));
};

const imageUrl = (image) => {
  if (!image) {
    return '';
  }

  if (image.startsWith('http://') || image.startsWith('https://') || image.startsWith('/')) {
    return image;
  }

  return `/storage/${image}`;
};

const plainContent = computed(() => {
  if (!blog.value?.content) {
    return '';
  }

  return String(blog.value.content)
    .replace(/<[^>]*>?/gm, ' ')
    .replace(/\s+/g, ' ')
    .trim();
});

const contentParagraphs = computed(() => {
  if (!plainContent.value) {
    return [];
  }

  const chunks = plainContent.value.match(/.{1,400}(\s|$)/g) || [plainContent.value];
  return chunks.slice(0, 6);
});

const categoryStats = computed(() => {
  const stats = allBlogs.value.reduce((acc, item) => {
    const categoryName = item.category?.category_name || 'Chưa phân loại';
    acc[categoryName] = (acc[categoryName] || 0) + 1;
    return acc;
  }, {});

  return Object.entries(stats)
    .map(([name, count]) => ({ name, count }))
    .sort((a, b) => b.count - a.count || a.name.localeCompare(b.name, 'vi'));
});

const fetchDetail = async () => {
  if (!blogId) {
    error.value = 'Thiếu mã bài viết.';
    loading.value = false;
    return;
  }

  try {
    const [detailResponse, latestResponse] = await Promise.all([
      fetch(`/api/blog/${blogId}`, { headers: { Accept: 'application/json' } }),
      fetch('/api/blog', { headers: { Accept: 'application/json' } }),
    ]);

    if (!detailResponse.ok) {
      throw new Error('Không thể tải chi tiết bài viết.');
    }

    const detailPayload = await detailResponse.json();
    blog.value = detailPayload;

    if (latestResponse.ok) {
      const latestPayload = await latestResponse.json();
      allBlogs.value = Array.isArray(latestPayload) ? latestPayload : [];
      latestBlogs.value = allBlogs.value
        .filter((item) => item.id !== detailPayload.id)
        .slice(0, 5);
    }
  } catch (err) {
    error.value = err instanceof Error ? err.message : 'Đã xảy ra lỗi khi tải dữ liệu.';
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchDetail();
});
</script>

<template>
  <ClientLayout>
    <div class="min-h-screen bg-gray-50 text-slate-900">
      <main class="mx-auto max-w-6xl px-4 py-8 lg:px-8">
        <div class="mb-6 rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
          <nav aria-label="Breadcrumb" class="flex items-center gap-2 text-sm">
            <a href="/" class="font-medium text-slate-500 transition hover:text-slate-800">Trang chủ</a>
            <span class="text-slate-300">/</span>
            <a href="/blog" class="font-medium text-slate-500 transition hover:text-slate-800">Tin tức</a>
            <span class="text-slate-300">/</span>
            <span class="line-clamp-1 font-semibold text-brand">{{ blog?.title || 'Chi tiết bài viết' }}</span>
          </nav>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
          <section class="space-y-6 lg:col-span-2">
            <article v-if="loading" class="overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 text-sm text-slate-500">
              Đang tải chi tiết bài viết...
            </article>

            <article v-else-if="error" class="overflow-hidden rounded-2xl border border-rose-200 bg-rose-50 p-6 text-sm text-rose-700">
              {{ error }}
            </article>

            <article v-else-if="blog" class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
              <div class="border-b border-slate-100 px-5 py-5 sm:px-7">
                <span class="mb-3 inline-flex rounded-full bg-orange-100 px-2.5 py-1 text-xs font-bold text-orange-700">
                  {{ blog.category?.category_name || 'Chưa phân loại' }}
                </span>
                <h1 class="mb-4 text-2xl font-black leading-tight text-slate-900 sm:text-3xl">
                  {{ blog.title }}
                </h1>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm text-slate-500">
                  <span><i class="fa-regular fa-calendar mr-1"></i>{{ formatDate(blog.created_at) }}</span>
                  <span><i class="fa-regular fa-folder mr-1"></i>{{ blog.category?.category_name || 'Chưa phân loại' }}</span>
                </div>
              </div>

              <div class="flex h-64 items-center justify-center overflow-hidden bg-slate-900 sm:h-80">
                <img
                  v-if="blog.image"
                  :src="imageUrl(blog.image)"
                  :alt="blog.title"
                  class="h-full w-full object-cover"
                />
                <div v-else class="grid h-full w-full place-content-center bg-slate-900 text-sm font-bold tracking-[0.2em] text-slate-300 uppercase">
                  Blog Detail
                </div>
              </div>

              <div class="space-y-5 px-5 py-6 text-[15px] leading-7 text-slate-700 sm:px-7">
                <p v-if="blog.excerpt" class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-800">
                  {{ blog.excerpt }}
                </p>

                <p v-for="(paragraph, idx) in contentParagraphs" :key="idx">
                  {{ paragraph }}
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
                <a
                  v-for="item in latestBlogs.slice(0, 2)"
                  :key="item.id"
                  :href="`/blog-detail?id=${item.id}`"
                  class="rounded-xl border border-slate-200 p-4 transition hover:-translate-y-0.5 hover:border-brand"
                >
                  <p class="mb-2 text-xs font-bold uppercase tracking-wide text-brand">{{ item.category?.category_name || 'Chưa phân loại' }}</p>
                  <h4 class="mb-2 line-clamp-2 text-sm font-bold leading-5 text-slate-900">{{ item.title }}</h4>
                  <p class="line-clamp-2 text-xs text-slate-500">{{ item.excerpt || 'Xem chi tiết nội dung bài viết.' }}</p>
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
                <div
                  v-for="(item, index) in latestBlogs"
                  :key="item.id"
                  class="flex gap-3 py-2.5"
                >
                  <div class="min-w-6 text-2xl font-extrabold leading-none text-slate-200">{{ String(index + 1).padStart(2, '0') }}</div>
                  <div>
                    <a :href="`/blog-detail?id=${item.id}`" class="line-clamp-2 text-xs font-semibold leading-5 text-slate-800 transition hover:text-brand">{{ item.title }}</a>
                    <p class="text-[11px] text-orange-600">{{ item.category?.category_name || 'Chưa phân loại' }}</p>
                    <p class="text-xs text-slate-400">{{ formatDate(item.created_at) }}</p>
                  </div>
                </div>
              </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white p-5">
              <div class="mb-3 border-b border-slate-200 pb-3">
                <h3 class="inline-block border-b-2 border-brand pb-1 text-sm font-extrabold">Danh mục</h3>
              </div>
              <div class="space-y-1.5">
                <div
                  v-for="category in categoryStats"
                  :key="category.name"
                  class="flex w-full items-center justify-between rounded-lg px-2.5 py-2 transition hover:bg-orange-50"
                >
                  <span class="text-sm font-medium text-slate-700">{{ category.name }}</span>
                  <span class="rounded-full bg-orange-100 px-2 py-0.5 text-xs font-bold text-orange-700">{{ category.count }}</span>
                </div>
              </div>
            </section>
          </aside>
        </div>
      </main>
    </div>
  </ClientLayout>
</template>