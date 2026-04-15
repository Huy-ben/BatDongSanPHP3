<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import ClientLayout from '@/layouts/ClientLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import {
    computed,
    markRaw,
    nextTick,
    onBeforeUnmount,
    onMounted,
    ref,
    shallowRef,
} from 'vue';

declare global {
    interface Window {
        maplibregl?: any;
    }
}

type CategoryOption = {
    id: number;
    category_name: string;
};

type PostImage = {
    id: number;
    image_url: string;
    is_thumbnail: boolean;
};

type PostData = {
    id: number;
    title: string;
    category_id: number;
    price: number;
    area: number;
    address: string;
    location: string;
    description: string;
    status: string;
    images: PostImage[];
};

type PackageLimits = {
    name: string;
    max_images: number;
    max_posts_per_day: number | null;
};

const props = defineProps<{
    categories: CategoryOption[];
    hasActivePackage: boolean;
    activePackage: {
        package_name: string;
        package_type: string;
        expiry_date: string;
    } | null;
    packageLimits: PackageLimits;
    todayPostCount: number;
    post?: PostData | null;
}>();

const statusOptions = [
    { label: 'Bản nháp', value: 'draft' },
    { label: 'Chờ duyệt', value: 'waiting' },
];

const localError = ref('');
const imageFiles = ref<File[]>([]);
const imagePreviews = ref<string[]>(props.post?.images?.map((image) => image.image_url) ?? []);
const initialThumbnailIndex = props.post?.images?.findIndex((image) => image.is_thumbnail) ?? -1;
const thumbnailIndex = ref(initialThumbnailIndex >= 0 ? initialThumbnailIndex : 0);
const previewSources = ref<Array<'existing' | 'new'>>(
    props.post?.images?.map(() => 'existing' as const) ?? [],
);
const isSearchingAddress = ref(false);
const suggestions = ref<any[]>([]);
const page = usePage();
const isEditing = computed(() => Boolean(props.post));

const GOONG_API_KEY = '9eQP8x4c9ulw5j1ycK7iAyTejVvUmHS7T9opYswn';
const GOONG_MAP_KEY = 'NDYPjCSusn6YSEozAla1mgcP7pTXoFU3tIamrb9M';
const DEFAULT_LOCATION = { lat: 16.047079, lng: 108.20623 };

const mapContainerRef = ref<HTMLDivElement | null>(null);
const mapRef = ref<any>(null);
const markerRef = ref<any>(null);
const ckEditorRef = ref<HTMLDivElement | null>(null);
const ckEditorInstance = shallowRef<any>(null);

const form = useForm({
    title: props.post?.title ?? '',
    category_id: props.post?.category_id ?? props.categories[0]?.id ?? '',
    price: props.post?.price ? String(props.post.price) : '',
    area: props.post?.area ? String(props.post.area) : '',
    address: props.post?.address ?? '',
    location: props.post?.location ?? '',
    description: props.post?.description ?? '',
    status: props.post?.status ?? 'draft',
    images: [] as File[],
    thumbnail_index: thumbnailIndex.value,
});

const coverImage = computed(() => {
    return imagePreviews.value[thumbnailIndex.value] || imagePreviews.value[0] || '';
});
const imageCount = computed(() => imageFiles.value.length);
const remainingPostsToday = computed(() => {
    if (props.packageLimits.max_posts_per_day === null) {
        return 'Không giới hạn';
    }

    return Math.max(
        props.packageLimits.max_posts_per_day - props.todayPostCount,
        0,
    );
});

const canSubmit = computed(() => {
    if (isEditing.value) {
        return true;
    }

    if (!props.hasActivePackage) {
        return false;
    }

    if (props.packageLimits.max_posts_per_day === null) {
        return true;
    }

    return props.todayPostCount < props.packageLimits.max_posts_per_day;
});

const packageError = computed(() => {
    return (page.props.errors?.package as string) || '';
});

function capitalizeFirst(value?: string) {
    if (!value) return '';
    return value.charAt(0).toUpperCase() + value.slice(1);
}

function parseLocation(value: string) {
    const [latRaw, lngRaw] = (value || '')
        .split(',')
        .map((item) => item.trim());
    const lat = Number(latRaw);
    const lng = Number(lngRaw);

    if (!Number.isFinite(lat) || !Number.isFinite(lng)) {
        return null;
    }

    return { lat, lng };
}

function setLocation(lat: number, lng: number) {
    form.location = `${lat.toFixed(6)},${lng.toFixed(6)}`;
}

function ensureCkEditorAssets() {
    return new Promise<void>((resolve, reject) => {
        const win = window as any;
        if (win.ClassicEditor) {
            resolve();
            return;
        }

        const scriptId = 'ckeditor-classic-script';
        if (document.getElementById(scriptId)) {
            const checkLoaded = () => {
                if (win.ClassicEditor) {
                    resolve();
                } else {
                    setTimeout(checkLoaded, 50);
                }
            };
            checkLoaded();
            return;
        }

        const script = document.createElement('script');
        script.id = scriptId;
        script.src =
            'https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js';
        script.async = true;
        script.onload = () => resolve();
        script.onerror = () => reject(new Error('Khong the tai CKEditor'));
        document.body.appendChild(script);
    });
}

async function initCkEditor() {
    await ensureCkEditorAssets();
    await nextTick();

    const win = window as any;
    if (!ckEditorRef.value || !win.ClassicEditor || ckEditorInstance.value) {
        return;
    }

    const editor = markRaw(
        await win.ClassicEditor.create(ckEditorRef.value, {
        toolbar: [
            'heading',
            '|',
            'bold',
            'italic',
            'underline',
            'link',
            'bulletedList',
            'numberedList',
            '|',
            'blockQuote',
            'insertTable',
            'undo',
            'redo',
        ],
        }),
    );

    ckEditorInstance.value = editor;

    editor.setData(form.description || '');
    editor.model.document.on('change:data', () => {
        form.description = editor.getData();
    });
}

function ensureMapLibreAssets() {
    return new Promise<void>((resolve, reject) => {
        if (window.maplibregl) {
            resolve();
            return;
        }

        const cssId = 'maplibre-css';
        if (!document.getElementById(cssId)) {
            const link = document.createElement('link');
            link.id = cssId;
            link.rel = 'stylesheet';
            link.href =
                'https://unpkg.com/maplibre-gl@4.7.1/dist/maplibre-gl.css';
            document.head.appendChild(link);
        }

        const script = document.createElement('script');
        script.src = 'https://unpkg.com/maplibre-gl@4.7.1/dist/maplibre-gl.js';
        script.async = true;
        script.onload = () => resolve();
        script.onerror = () => reject(new Error('Khong the tai MapLibre'));
        document.body.appendChild(script);
    });
}

function upsertMarker(lat: number, lng: number) {
    if (!window.maplibregl || !mapRef.value) {
        return;
    }

    if (!markerRef.value) {
        markerRef.value = new window.maplibregl.Marker({
            color: '#ef4444',
            draggable: true,
        })
            .setLngLat([lng, lat])
            .addTo(mapRef.value);

        markerRef.value.on('dragend', () => {
            const point = markerRef.value.getLngLat();
            setLocation(point.lat, point.lng);
            // Keep address unchanged as user requested.
        });
    } else {
        markerRef.value.setLngLat([lng, lat]);
    }
}

async function initMap() {
    await ensureMapLibreAssets();
    await nextTick();

    if (!mapContainerRef.value || !window.maplibregl || mapRef.value) {
        return;
    }

    const location = parseLocation(form.location) || DEFAULT_LOCATION;

    mapRef.value = new window.maplibregl.Map({
        container: mapContainerRef.value,
        style: `https://tiles.goong.io/assets/goong_map_web.json?api_key=${GOONG_MAP_KEY}`,
        center: [location.lng, location.lat],
        zoom: 14,
    });

    mapRef.value.on('load', () => {
        upsertMarker(location.lat, location.lng);
    });
}

function focusMapToLocation(lat: number, lng: number) {
    if (!mapRef.value) {
        return;
    }

    mapRef.value.easeTo({ center: [lng, lat], zoom: 15 });
    upsertMarker(lat, lng);
}

function handleImageChange(event: Event) {
    localError.value = '';
    const input = event.target as HTMLInputElement;
    const files = Array.from(input.files || []);

    if (!files.length) return;

    if (files.length > props.packageLimits.max_images) {
        localError.value = `Gói ${props.packageLimits.name} chỉ cho phép tối đa ${props.packageLimits.max_images} ảnh.`;
        input.value = '';
        return;
    }

    imageFiles.value = files;
    imagePreviews.value = files.map((file) => URL.createObjectURL(file));
    previewSources.value = files.map(() => 'new' as const);
    thumbnailIndex.value = 0;
    form.images = files;
    form.thumbnail_index = 0;
}

function removeImage(index: number) {
    if (previewSources.value[index] === 'existing') {
        return;
    }

    imageFiles.value.splice(index, 1);
    imagePreviews.value.splice(index, 1);
    previewSources.value.splice(index, 1);

    if (thumbnailIndex.value >= imagePreviews.value.length) {
        thumbnailIndex.value = Math.max(0, imagePreviews.value.length - 1);
    } else if (index < thumbnailIndex.value) {
        thumbnailIndex.value -= 1;
    }

    form.images = [...imageFiles.value];
    form.thumbnail_index = thumbnailIndex.value;
}

function setThumbnail(index: number) {
    thumbnailIndex.value = index;
    form.thumbnail_index = index;
}

async function searchPlace() {
    if (!form.address || form.address.trim().length < 3) {
        suggestions.value = [];
        return;
    }

    try {
        isSearchingAddress.value = true;
        const query = encodeURIComponent(form.address);
        const response = await fetch(
            `https://rsapi.goong.io/Place/AutoComplete?api_key=${GOONG_API_KEY}&input=${query}`,
        );
        const data = await response.json();
        suggestions.value = data.predictions || [];
    } catch {
        suggestions.value = [];
    } finally {
        isSearchingAddress.value = false;
    }
}

async function selectPlace(item: any) {
    localError.value = '';
    suggestions.value = [];

    try {
        const response = await fetch(
            `https://rsapi.goong.io/Place/Detail?place_id=${item.place_id}&api_key=${GOONG_API_KEY}`,
        );
        const data = await response.json();
        const location = data?.result?.geometry?.location;

        if (!location?.lat || !location?.lng) {
            localError.value = 'Không thể lấy tọa độ từ địa chỉ đã chọn.';
            return;
        }

        form.address = item.description || form.address;
        setLocation(location.lat, location.lng);
        focusMapToLocation(location.lat, location.lng);
    } catch {
        localError.value = 'Không thể lấy thông tin địa chỉ từ bản đồ.';
    }
}

function submitPost() {
    localError.value = '';

    if (ckEditorInstance.value) {
        form.description = ckEditorInstance.value.getData();
    }

    if (!isEditing.value && !props.hasActivePackage) {
        localError.value = 'Tài khoản chưa có gói đăng tin còn hiệu lực.';
        return;
    }

    if (!form.images.length) {
        localError.value = 'Vui lòng chọn ít nhất 1 ảnh.';
        return;
    }

    if (!form.location) {
        localError.value = 'Vui lòng chọn địa chỉ trên bản đồ để lấy tọa độ.';
        return;
    }

    form.thumbnail_index = thumbnailIndex.value;

    if (isEditing.value && props.post) {
        form.patch(`/dang-tin/${props.post.id}`, {
            forceFormData: true,
            preserveScroll: true,
        });

        return;
    }

    form.post('/dang-tin', {
        forceFormData: true,
        preserveScroll: true,
    });
}

onMounted(() => {
    initMap();
    initCkEditor();
});

onBeforeUnmount(() => {
    if (mapRef.value) {
        mapRef.value.remove();
        mapRef.value = null;
    }

    if (ckEditorInstance.value) {
        ckEditorInstance.value.destroy();
        ckEditorInstance.value = null;
    }
});
</script>

<template>
    <ClientLayout>
        <div class="min-h-screen bg-gray-50 text-slate-900">
            <section
                class="border-b border-orange-100 bg-linear-to-br from-white via-[#FFF8EF] to-[#FFE7C7]"
            >
                <div
                    class="mx-auto grid w-full max-w-6xl gap-8 px-4 py-8 lg:px-6 lg:py-10"
                >
                    <div class="mx-auto w-full max-w-4xl text-center">
                        <div
                            class="inline-flex items-center gap-2 rounded-full border border-orange-200 bg-white px-4 py-2 text-xs font-bold tracking-[0.28em] text-orange-600 uppercase shadow-sm"
                        >
                            <span
                                class="h-2 w-2 rounded-full bg-orange-500"
                            ></span>
                            {{ isEditing ? 'Chỉnh sửa bài đăng' : 'Đăng tin dành cho seller' }}
                        </div>
                        <h1
                            class="mx-auto mt-4 max-w-2xl text-3xl leading-tight font-black text-slate-950 md:text-4xl"
                        >
                            {{
                                isEditing
                                    ? 'Cập nhật tin đăng bất động sản của bạn.'
                                    : 'Tạo tin đăng bất động sản với đầy đủ thông tin chỉ trong một form.'
                            }}
                        </h1>
                        <p
                            class="mx-auto mt-3 max-w-2xl text-sm leading-7 text-slate-600"
                        >
                            Form được ràng buộc trực tiếp theo dữ liệu bảng
                            posts và quyền lợi gói bạn đang sở hữu.
                        </p>

                        <div
                            class="mx-auto mt-7 grid max-w-4xl gap-4 md:grid-cols-3"
                        >
                            <div
                                class="rounded-2xl border border-white/60 bg-white/80 p-3.5 text-left shadow-sm backdrop-blur"
                            >
                                <p
                                    class="text-[11px] font-bold tracking-[0.25em] text-slate-400 uppercase"
                                >
                                    Gói hiện tại
                                </p>
                                <p
                                    class="mt-1.5 text-base font-extrabold text-orange-600"
                                >
                                    {{
                                        hasActivePackage
                                            ? activePackage?.package_name
                                            : 'Chưa có gói'
                                    }}
                                </p>
                                <p class="mt-1 text-xs text-slate-500">
                                    Hết hạn:
                                    {{ activePackage?.expiry_date || '---' }}
                                </p>
                            </div>
                            <div
                                class="rounded-2xl border border-white/60 bg-white/80 p-3.5 text-left shadow-sm backdrop-blur"
                            >
                                <p
                                    class="text-[11px] font-bold tracking-[0.25em] text-slate-400 uppercase"
                                >
                                    Giới hạn ảnh/tin
                                </p>
                                <p
                                    class="mt-1.5 text-base font-extrabold text-orange-600"
                                >
                                    {{ packageLimits.max_images }} ảnh / tin
                                </p>
                                <p class="mt-1 text-xs text-slate-500">
                                    Áp dụng theo package_type
                                </p>
                            </div>
                            <div
                                class="rounded-2xl border border-white/60 bg-white/80 p-3.5 text-left shadow-sm backdrop-blur"
                            >
                                <p
                                    class="text-[11px] font-bold tracking-[0.25em] text-slate-400 uppercase"
                                >
                                    Số tin hôm nay còn lại
                                </p>
                                <p
                                    class="mt-1.5 text-base font-extrabold text-orange-600"
                                >
                                    {{ remainingPostsToday }}
                                </p>
                                <p class="mt-1 text-xs text-slate-500">
                                    Dựa vào gói đang sở hữu
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section
                v-if="!hasActivePackage && !isEditing"
                class="mx-auto w-full max-w-6xl px-4 pt-8 lg:px-6"
            >
                <div
                    class="rounded-3xl border border-amber-200 bg-amber-50 p-5 text-amber-700"
                >
                    <p class="text-sm font-bold">
                        Tài khoản chưa có gói đăng tin còn hiệu lực.
                    </p>
                    <p class="mt-1 text-sm">
                        Vui lòng mua gói tại trang package để có thể đăng bài.
                    </p>
                </div>
            </section>

            <section class="mx-auto w-full max-w-6xl px-4 py-8 lg:px-6 lg:py-9">
                <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
                    <form
                        class="space-y-6 rounded-4xl border border-slate-100 bg-white p-5 shadow-sm lg:p-6"
                        @submit.prevent="submitPost"
                    >
                        <div>
                            <p
                                class="text-xs font-semibold tracking-[0.25em] text-orange-600 uppercase"
                            >
                                {{ isEditing ? 'Thông tin bài đăng' : 'Thông tin bài đăng' }}
                            </p>
                            <h2 class="mt-2 text-xl font-black text-slate-950">
                                {{ isEditing ? 'Chỉnh sửa nội dung bài đăng' : 'Nhập các trường theo bảng posts' }}
                            </h2>
                        </div>

                        <InputError
                            class="text-sm"
                            :message="
                                capitalizeFirst(packageError || localError)
                            "
                        />

                        <div class="grid gap-5 md:grid-cols-2">
                            <label class="md:col-span-2">
                                <span
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                    >Tiêu đề bài đăng</span
                                >
                                <input
                                    v-model="form.title"
                                    type="text"
                                    placeholder="Ví dụ: Căn hộ 3PN view sông tại Thủ Đức"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 transition outline-none focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100"
                                />
                                <InputError
                                    class="mt-1"
                                    :message="
                                        capitalizeFirst(form.errors.title)
                                    "
                                />
                            </label>

                            <label>
                                <span
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                    >Danh mục</span
                                >
                                <select
                                    v-model="form.category_id"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 transition outline-none focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100"
                                >
                                    <option
                                        v-for="option in categories"
                                        :key="option.id"
                                        :value="option.id"
                                    >
                                        {{ option.category_name }}
                                    </option>
                                </select>
                                <InputError
                                    class="mt-1"
                                    :message="
                                        capitalizeFirst(form.errors.category_id)
                                    "
                                />
                            </label>

                            <label>
                                <span
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                    >Giá bán</span
                                >
                                <input
                                    v-model="form.price"
                                    type="number"
                                    min="0"
                                    step="1000"
                                    placeholder="Ví dụ: 3800000000"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 transition outline-none focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100"
                                />
                                <InputError
                                    class="mt-1"
                                    :message="
                                        capitalizeFirst(form.errors.price)
                                    "
                                />
                            </label>
                            <div class="md:col-span-2">
                                <span
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                    >Mô tả chi tiết</span
                                >
                                <div
                                    class="overflow-hidden border border-slate-200 bg-white"
                                >
                                    <textarea
                                        ref="ckEditorRef"
                                        class="min-h-96"
                                    ></textarea>
                                </div>
                                <InputError
                                    class="mt-1"
                                    :message="
                                        capitalizeFirst(form.errors.description)
                                    "
                                />
                            </div>
                            <label>
                                <span
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                    >Diện tích</span
                                >
                                <input
                                    v-model="form.area"
                                    type="number"
                                    min="1"
                                    step="0.1"
                                    placeholder="Ví dụ: 85"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 transition outline-none focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100"
                                />
                                <InputError
                                    class="mt-1"
                                    :message="capitalizeFirst(form.errors.area)"
                                />
                            </label>

                            <label class="md:col-span-2">
                                <span
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                    >Địa chỉ</span
                                >
                                <input
                                    v-model="form.address"
                                    @input="searchPlace"
                                    type="text"
                                    placeholder="Nhập địa chỉ để tìm trên bản đồ"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 transition outline-none focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100"
                                />

                                <div
                                    v-if="isSearchingAddress"
                                    class="mt-2 text-xs text-slate-500"
                                >
                                    Đang tìm địa chỉ...
                                </div>
                                <div
                                    v-if="suggestions.length"
                                    class="mt-2 max-h-44 overflow-auto rounded-2xl border border-slate-200 bg-white"
                                >
                                    <button
                                        v-for="item in suggestions"
                                        :key="item.place_id"
                                        type="button"
                                        @click="selectPlace(item)"
                                        class="block w-full border-b border-slate-100 px-3 py-2 text-left text-sm text-slate-700 last:border-b-0 hover:bg-slate-50"
                                    >
                                        {{ item.description }}
                                    </button>
                                </div>

                                <InputError
                                    class="mt-1"
                                    :message="
                                        capitalizeFirst(form.errors.address)
                                    "
                                />
                            </label>

                            <label class="md:col-span-2">
                                <span
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                    >Tọa độ location (lat,lng)</span
                                >
                                <input
                                    v-model="form.location"
                                    type="text"
                                    readonly
                                    placeholder="Tọa độ sẽ được điền sau khi chọn địa chỉ"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-100 px-4 py-3 text-slate-700 outline-none"
                                />
                                <InputError
                                    class="mt-1"
                                    :message="
                                        capitalizeFirst(form.errors.location)
                                    "
                                />
                            </label>

                            <div
                                class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 md:col-span-2"
                            >
                                <div
                                    class="border-b border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700"
                                >
                                    Bản đồ vị trí
                                </div>
                                <div class="h-64 w-full bg-slate-100">
                                    <div
                                        ref="mapContainerRef"
                                        class="h-full w-full"
                                    ></div>
                                </div>
                                <p
                                    class="border-t border-slate-200 px-4 py-2 text-xs text-slate-500"
                                >
                                    Bạn có thể kéo ghim để cập nhật tọa độ. Địa
                                    chỉ sẽ giữ nguyên theo nội dung bạn đã nhập.
                                </p>
                            </div>

                            <label>
                                <span
                                    class="mb-2 block text-sm font-semibold text-slate-700"
                                    >Trạng thái</span
                                >
                                <select
                                    v-model="form.status"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 transition outline-none focus:border-orange-400 focus:bg-white focus:ring-4 focus:ring-orange-100"
                                >
                                    <option
                                        v-for="option in statusOptions"
                                        :key="option.value"
                                        :value="option.value"
                                    >
                                        {{ option.label }}
                                    </option>
                                </select>
                                <InputError
                                    class="mt-1"
                                    :message="
                                        capitalizeFirst(form.errors.status)
                                    "
                                />
                            </label>

                            <div
                                class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600"
                            >
                                Ảnh tối đa mỗi bài:
                                <strong>{{ packageLimits.max_images }}</strong>
                            </div>
                        </div>

                        <div>
                            <label
                                class="mb-2 block text-sm font-semibold text-slate-700"
                                >Hình ảnh bài đăng</label
                            >
                            <label
                                class="flex cursor-pointer flex-col items-center justify-center rounded-[1.75rem] border-2 border-dashed border-orange-200 bg-orange-50/60 px-6 py-10 text-center transition hover:border-orange-400 hover:bg-orange-50"
                            >
                                <input
                                    type="file"
                                    multiple
                                    accept="image/*"
                                    class="hidden"
                                    @change="handleImageChange"
                                />
                                <span class="text-sm font-bold text-orange-700"
                                    >Bấm để tải ảnh lên</span
                                >
                                <span class="mt-2 text-sm text-slate-500"
                                    >Chọn nhiều ảnh và bấm "Đặt làm thumbnail"
                                    cho ảnh đại diện bạn muốn.</span
                                >
                            </label>
                            <InputError
                                class="mt-2"
                                :message="capitalizeFirst(form.errors.images)"
                            />

                            <div
                                v-if="imagePreviews.length"
                                class="mt-4 rounded-3xl border border-slate-100 bg-slate-50 p-4"
                            >
                                <div
                                    class="mb-3 flex items-center justify-between"
                                >
                                    <p
                                        class="text-sm font-semibold text-slate-700"
                                    >
                                        Ảnh đã chọn
                                    </p>
                                    <span
                                        class="rounded-full bg-white px-3 py-1 text-xs font-bold text-slate-500"
                                    >
                                        {{ imagePreviews.length }} ảnh
                                    </span>
                                </div>

                                <div
                                    class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4"
                                >
                                    <div
                                        v-for="(image, index) in imagePreviews"
                                        :key="`${image}-${index}`"
                                        class="group relative overflow-hidden rounded-2xl border border-white bg-white shadow-sm"
                                    >
                                        <img
                                            :src="image"
                                            :alt="`Ảnh đã chọn ${index + 1}`"
                                            class="h-28 w-full object-cover transition duration-300 group-hover:scale-105"
                                        />
                                        <div
                                            class="absolute top-2 left-2 rounded-full bg-black/60 px-2 py-1 text-[10px] font-bold text-white"
                                        >
                                            {{
                                                index === thumbnailIndex
                                                    ? 'Cover'
                                                    : `#${index + 1}`
                                            }}
                                        </div>
                                        <button
                                            v-if="previewSources[index] === 'new'"
                                            type="button"
                                            class="absolute bottom-2 left-2 rounded-full bg-white/90 px-2 py-1 text-[10px] font-bold text-slate-700"
                                            @click="setThumbnail(index)"
                                        >
                                            Đặt làm thumbnail
                                        </button>
                                        <button
                                            v-if="previewSources[index] === 'new'"
                                            type="button"
                                            class="absolute top-2 right-2 rounded-full bg-red-500 px-2 py-1 text-[10px] font-bold text-white"
                                            @click="removeImage(index)"
                                        >
                                            X
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="rounded-full bg-linear-to-r from-orange-500 to-amber-500 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-orange-200 transition hover:from-orange-600 hover:to-amber-600 disabled:cursor-not-allowed disabled:opacity-60"
                            >
                                {{
                                    form.processing
                                        ? isEditing
                                            ? 'Đang cập nhật...'
                                            : 'Đang đăng...'
                                        : isEditing
                                            ? 'Cập nhật bài đăng'
                                            : 'Đăng tin ngay'
                                }}
                            </button>
                            <p
                                v-if="!isEditing && hasActivePackage && !canSubmit"
                                class="text-sm font-medium text-amber-700"
                            >
                                Bạn đã chạm giới hạn số tin trong ngày theo gói
                                hiện tại. Nâng cấp gói hoặc chờ sang ngày mới để
                                đăng thêm.
                            </p>
                        </div>
                    </form>

                    <div class="space-y-6">
                        <aside
                            class="rounded-4xl border border-orange-100 bg-white p-5 shadow-xl shadow-orange-100/40 lg:p-6"
                        >
                            <div class="flex items-center justify-between">
                                <div>
                                    <p
                                        class="text-xs font-semibold tracking-[0.25em] text-orange-600 uppercase"
                                    >
                                        Tổng quan tin
                                    </p>
                                    <h2
                                        class="mt-1 text-xl font-black text-slate-950"
                                    >
                                        Bản xem nhanh
                                    </h2>
                                </div>
                                <span
                                    class="rounded-full bg-orange-50 px-3 py-1 text-xs font-bold text-orange-600"
                                    >Seller</span
                                >
                            </div>

                            <div
                                class="mt-5 overflow-hidden rounded-3xl border border-slate-100 bg-slate-50"
                            >
                                <div
                                    class="aspect-16/11 bg-linear-to-br from-slate-200 to-slate-100"
                                >
                                    <img
                                        v-if="coverImage"
                                        :src="coverImage"
                                        alt="Ảnh bài đăng"
                                        class="h-full w-full object-cover"
                                    />
                                    <div
                                        v-else
                                        class="flex h-full items-center justify-center px-8 text-center text-sm font-medium text-slate-500"
                                    >
                                        Ảnh đầu tiên sẽ hiển thị như thumbnail.
                                    </div>
                                </div>
                                <div class="p-5">
                                    <p
                                        class="text-[10px] font-bold tracking-[0.28em] text-orange-500 uppercase"
                                    >
                                        {{
                                            categories.find(
                                                (item) =>
                                                    item.id ===
                                                    Number(form.category_id),
                                            )?.category_name || 'Danh mục'
                                        }}
                                    </p>
                                    <h3
                                        class="mt-2 line-clamp-2 text-lg leading-snug font-black text-slate-950"
                                    >
                                        {{
                                            form.title ||
                                            'Tiêu đề bài đăng sẽ hiển thị ở đây'
                                        }}
                                    </h3>
                                    <div
                                        class="mt-4 flex flex-wrap gap-2 text-sm font-semibold"
                                    >
                                        <span
                                            class="rounded-full bg-orange-50 px-3 py-1 text-orange-600"
                                            >{{ form.price || 'Giá bán' }}</span
                                        >
                                        <span
                                            class="rounded-full bg-slate-100 px-3 py-1 text-slate-700"
                                            >{{
                                                form.area || 'Diện tích'
                                            }}
                                            m²</span
                                        >
                                    </div>
                                    <p
                                        class="mt-4 text-sm leading-6 text-slate-600"
                                    >
                                        {{
                                            form.address ||
                                            'Địa chỉ đăng tin sẽ xuất hiện tại đây.'
                                        }}
                                    </p>
                                    <div
                                        class="prose prose-sm mt-3 max-w-none text-slate-600"
                                        v-html="
                                            form.description ||
                                            '<p>Chưa có mô tả chi tiết.</p>'
                                        "
                                    ></div>
                                    <p class="mt-2 text-xs text-slate-500">
                                        Tọa độ:
                                        {{ form.location || 'Chưa có tọa độ' }}
                                    </p>
                                </div>
                            </div>

                            <div class="mt-5 grid grid-cols-2 gap-3 text-sm">
                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <p
                                        class="text-[10px] font-bold tracking-[0.2em] text-slate-400 uppercase"
                                    >
                                        Số ảnh
                                    </p>
                                    <p
                                        class="mt-1 text-xl font-black text-slate-900"
                                    >
                                        {{ imageCount }}
                                    </p>
                                </div>
                                <div class="rounded-2xl bg-slate-50 p-4">
                                    <p
                                        class="text-[10px] font-bold tracking-[0.2em] text-slate-400 uppercase"
                                    >
                                        Trạng thái
                                    </p>
                                    <p
                                        class="mt-1 text-xl font-black text-slate-900"
                                    >
                                        {{
                                            form.status === 'published'
                                                ? 'Đã đăng'
                                                : form.status === 'waiting'
                                                    ? 'Chờ duyệt'
                                                    : form.status === 'rejected'
                                                        ? 'Bị từ chối'
                                                        : 'Bản nháp'
                                        }}
                                    </p>
                                </div>
                            </div>
                        </aside>

                        <div
                            class="rounded-4xl border border-orange-100 bg-linear-to-br from-[#FFF8EF] to-[#FFEACB] p-5 shadow-sm lg:p-6"
                        >
                            <p
                                class="text-xs font-semibold tracking-[0.25em] text-orange-600 uppercase"
                            >
                                Gợi ý seller
                            </p>
                            <h3 class="mt-2 text-xl font-black text-slate-950">
                                Nên dùng ảnh đầu tiên thật đẹp
                            </h3>
                            <p class="mt-3 text-sm leading-7 text-slate-600">
                                Ảnh cover quyết định CTR. Hãy chọn ảnh mặt tiền
                                hoặc ảnh phòng khách rõ sáng để tin nổi bật hơn.
                            </p>
                            <div
                                class="mt-5 grid gap-3 sm:grid-cols-3 lg:grid-cols-1 xl:grid-cols-3"
                            >
                                <div
                                    class="rounded-2xl bg-white/80 p-4 text-sm"
                                >
                                    <p class="font-bold text-slate-900">
                                        Tiêu đề
                                    </p>
                                    <p class="mt-1 text-slate-500">
                                        Ngắn gọn, có điểm mạnh nổi bật.
                                    </p>
                                </div>
                                <div
                                    class="rounded-2xl bg-white/80 p-4 text-sm"
                                >
                                    <p class="font-bold text-slate-900">Giá</p>
                                    <p class="mt-1 text-slate-500">
                                        Nên ghi đúng kỳ vọng của khách hàng.
                                    </p>
                                </div>
                                <div
                                    class="rounded-2xl bg-white/80 p-4 text-sm"
                                >
                                    <p class="font-bold text-slate-900">
                                        Trạng thái
                                    </p>
                                    <p class="mt-1 text-slate-500">
                                        Ẩn khi cần duyệt, hiện khi đã sẵn sàng.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </ClientLayout>
</template>

<style scoped>
:deep(.ck-editor__editable_inline) {
    min-height: 420px;
}
</style>
