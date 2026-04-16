<script setup>
import ClientLayout from '@/layouts/ClientLayout.vue';
import { computed, onMounted, ref } from 'vue';

const blogs = ref([]);
const loading = ref(true);
const error = ref('');
const keyword = ref('');
const currentPage = ref(1);
const pageSize = 6;

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

const readingMinutes = (content) => {
  if (!content) {
    return 1;
  }

  const words = String(content).trim().split(/\s+/).filter(Boolean).length;
  return Math.max(1, Math.ceil(words / 220));
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

const filteredBlogs = computed(() => {
  const normalizedKeyword = keyword.value.trim().toLowerCase();

  if (!normalizedKeyword) {
    return blogs.value;
  }

  return blogs.value.filter((item) => {
    const title = item.title?.toLowerCase() ?? '';
    const excerpt = item.excerpt?.toLowerCase() ?? '';
    const categoryName = item.category?.category_name?.toLowerCase() ?? '';

    return title.includes(normalizedKeyword) || excerpt.includes(normalizedKeyword) || categoryName.includes(normalizedKeyword);
  });
});

const totalPages = computed(() => Math.max(1, Math.ceil(filteredBlogs.value.length / pageSize)));

const pagedBlogs = computed(() => {
  const start = (currentPage.value - 1) * pageSize;
  return filteredBlogs.value.slice(start, start + pageSize);
});

const latestBlogs = computed(() => blogs.value.slice(0, 5));

const categoryStats = computed(() => {
  const stats = blogs.value.reduce((acc, item) => {
    const categoryName = item.category?.category_name || 'Chưa phân loại';
    acc[categoryName] = (acc[categoryName] || 0) + 1;
    return acc;
  }, {});

  return Object.entries(stats)
    .map(([name, count]) => ({ name, count }))
    .sort((a, b) => b.count - a.count || a.name.localeCompare(b.name, 'vi'));
});

const pageNumbers = computed(() => Array.from({ length: totalPages.value }, (_, index) => index + 1));

const goToPage = (page) => {
  if (page < 1 || page > totalPages.value) {
    return;
  }

  currentPage.value = page;
};

const fetchBlogs = async () => {
  loading.value = true;
  error.value = '';

  try {
    const response = await fetch('/api/blog', {
      headers: {
        Accept: 'application/json',
      },
    });

    if (!response.ok) {
      throw new Error('Không thể tải danh sách bài viết.');
    }

    const payload = await response.json();
    blogs.value = Array.isArray(payload) ? payload : [];
  } catch (err) {
    error.value = err instanceof Error ? err.message : 'Đã xảy ra lỗi khi tải dữ liệu.';
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  fetchBlogs();
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
            <span class="font-semibold text-brand">Tin tức</span>
          </nav>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
          <section class="space-y-6 lg:col-span-2">
            <div v-if="loading" class="rounded-xl border border-slate-200 bg-white p-6 text-sm text-slate-500 shadow-sm">
              Đang tải bài viết...
            </div>

            <div v-else-if="error" class="rounded-xl border border-rose-200 bg-rose-50 p-6 text-sm text-rose-700 shadow-sm">
              {{ error }}
            </div>

            <div v-else-if="pagedBlogs.length === 0" class="rounded-xl border border-slate-200 bg-white p-6 text-sm text-slate-500 shadow-sm">
              Không tìm thấy bài viết phù hợp.
            </div>

            <div v-else class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-2">
              <a
                v-for="blog in pagedBlogs"
                :key="blog.id"
                :href="`/blog-detail?id=${blog.id}`"
                class="overflow-hidden rounded-xl border border-slate-200 bg-white transition hover:-translate-y-0.5 hover:border-brand"
              >
                <div class="flex h-36 items-center justify-center overflow-hidden bg-slate-100">
                  <img
                    v-if="blog.image"
                    :src="imageUrl(blog.image)"
                    :alt="blog.title"
                    class="h-full w-full object-cover"
                  />
                  <div v-else class="grid h-full w-full place-content-center bg-slate-900 text-xs font-bold tracking-[0.2em] text-slate-300 uppercase">
                    Blog
                  </div>
                </div>
                <div class="p-4">
                  <span class="mb-2 inline-flex rounded-full bg-orange-100 px-2 py-0.5 text-[10px] font-bold text-orange-700">
                    {{ blog.category?.category_name || 'Chưa phân loại' }}
                  </span>
                  <h3 class="mb-2 line-clamp-2 text-sm font-bold leading-5">{{ blog.title }}</h3>
                  <p class="mb-3 line-clamp-2 text-xs leading-5 text-slate-500">{{ blog.excerpt || 'Chưa có mô tả ngắn cho bài viết này.' }}</p>
                  <div class="flex items-center justify-between text-[11px] text-slate-400">
                    <span>{{ formatDate(blog.created_at) }}</span>
                    <span>{{ readingMinutes(blog.content) }} phút đọc</span>
                  </div>
                </div>
              </a>
            </div>

            <div v-if="totalPages > 1" class="flex items-center justify-center gap-1.5 pt-2">
              <button
                class="flex h-8 w-8 items-center justify-center rounded-lg border text-sm"
                :class="currentPage === 1 ? 'cursor-not-allowed border-slate-200 bg-white text-slate-300' : 'border-slate-200 bg-white text-slate-600 transition hover:border-brand hover:text-brand'"
                :disabled="currentPage === 1"
                @click="goToPage(currentPage - 1)"
              >
                ‹
              </button>
              <button
                v-for="page in pageNumbers"
                :key="page"
                class="flex h-8 w-8 items-center justify-center rounded-lg border text-sm font-semibold"
                :class="page === currentPage ? 'border-brand bg-brand text-white' : 'border-slate-200 bg-white text-slate-600 transition hover:border-brand hover:text-brand'"
                @click="goToPage(page)"
              >
                {{ page }}
              </button>
              <button
                class="flex h-8 w-8 items-center justify-center rounded-lg border text-sm"
                :class="currentPage === totalPages ? 'cursor-not-allowed border-slate-200 bg-white text-slate-300' : 'border-slate-200 bg-white text-slate-600 transition hover:border-brand hover:text-brand'"
                :disabled="currentPage === totalPages"
                @click="goToPage(currentPage + 1)"
              >
                ›
              </button>
            </div>
          </section>

        <aside class="space-y-5">
          <section class="rounded-xl border border-slate-200 bg-white p-5">
            <div class="mb-4 border-b border-slate-200 pb-3">
              <h3 class="inline-block border-b-2 border-brand pb-1 text-sm font-extrabold">Tìm kiếm</h3>
            </div>
            <div class="flex gap-2">
              <input
                v-model="keyword"
                type="text"
                placeholder="Nhập từ khóa..."
                class="w-full rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none transition focus:border-brand"
                @input="goToPage(1)"
              />
            </div>
          </section>

          <section class="rounded-xl border border-slate-200 bg-white p-5">
            <div class="mb-3 border-b border-slate-200 pb-3">
              <h3 class="inline-block border-b-2 border-brand pb-1 text-sm font-extrabold">Bài viết mới nhất</h3>
            </div>
            <div class="divide-y divide-slate-100">
              <div
                v-for="(blog, index) in latestBlogs"
                :key="blog.id"
                class="flex gap-3 py-2.5"
              >
                <div class="min-w-6 text-2xl font-extrabold leading-none text-slate-200">
                  {{ String(index + 1).padStart(2, '0') }}
                </div>
                <div>
                  <a :href="`/blog-detail?id=${blog.id}`" class="line-clamp-2 text-xs font-semibold leading-5 text-slate-800 transition hover:text-brand">{{ blog.title }}</a>
                  <p class="text-[11px] text-orange-600">{{ blog.category?.category_name || 'Chưa phân loại' }}</p>
                  <p class="text-xs text-slate-400">{{ formatDate(blog.created_at) }}</p>
                </div>
              </div>
            </div>
          </section>

          <section class="rounded-xl border border-slate-200 bg-white p-5">
            <div class="mb-3 border-b border-slate-200 pb-3">
              <h3 class="inline-block border-b-2 border-brand pb-1 text-sm font-extrabold">Thống kê</h3>
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


<style scope>

</style>