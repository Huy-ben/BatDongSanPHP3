<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Image;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Inertia\Response;

class PostController extends Controller
{
    /**
     * Package limits by package_type from listing_packages table.
     * 0: Trial, 1: VIP, 2: SVIP
     */
    private const PACKAGE_LIMITS = [
        '0' => ['name' => 'Trial', 'max_images' => 5, 'max_posts_per_day' => 1],
        '1' => ['name' => 'VIP', 'max_images' => 15, 'max_posts_per_day' => 5],
        '2' => ['name' => 'SVIP', 'max_images' => 30, 'max_posts_per_day' => null],
    ];

    public function create(Request $request): Response
    {
        $user = $request->user();

        $activePackage = DB::table('listing_packages')
            ->where('user_id', $user?->id)
            ->where('status', '1')
            ->whereDate('expiry_date', '>=', now()->toDateString())
            ->orderByDesc('expiry_date')
            ->first(['id', 'package_name', 'package_type', 'expiry_date']);

        $limits = self::PACKAGE_LIMITS[$activePackage->package_type ?? ''] ?? [
            'name' => 'Chưa có gói',
            'max_images' => 0,
            'max_posts_per_day' => 0,
        ];

        $todayPostCount = 0;
        if ($user) {
            $todayPostCount = Post::query()
                ->where('seller_id', $user->id)
                ->whereDate('created_at', now()->toDateString())
                ->count();
        }

        $categories = Category::query()
            ->where('status', '1')
            ->orderBy('category_name')
            ->get(['id', 'category_name']);

        return inertia('Client/PostCreate', [
            'categories' => $categories,
            'hasActivePackage' => (bool) $activePackage,
            'activePackage' => $activePackage,
            'packageLimits' => $limits,
            'todayPostCount' => $todayPostCount,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        $activePackage = DB::table('listing_packages')
            ->where('user_id', $user->id)
            ->where('status', '1')
            ->whereDate('expiry_date', '>=', now()->toDateString())
            ->orderByDesc('expiry_date')
            ->first(['id', 'package_name', 'package_type', 'expiry_date']);

        if (! $activePackage) {
            return back()->withErrors([
                'package' => 'Tài khoản chưa có gói đăng tin còn hiệu lực. Vui lòng mua gói để đăng bài.',
            ]);
        }

        $limits = self::PACKAGE_LIMITS[$activePackage->package_type] ?? null;

        if (! $limits) {
            return back()->withErrors([
                'package' => 'Không xác định được quyền lợi gói hiện tại.',
            ]);
        }

        $todayPostCount = Post::query()
            ->where('seller_id', $user->id)
            ->whereDate('created_at', now()->toDateString())
            ->count();

        if ($limits['max_posts_per_day'] !== null && $todayPostCount >= $limits['max_posts_per_day']) {
            return back()->withErrors([
                'package' => 'Bạn đã dùng hết số lượng tin được đăng trong ngày theo gói hiện tại.',
            ]);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'area' => ['required', 'numeric', 'gt:0'],
            'address' => ['required', 'string', 'max:255'],
            'location' => ['required', 'regex:/^-?\d{1,2}(?:\.\d+)?,\s?-?\d{1,3}(?:\.\d+)?$/'],
            'description' => ['required', 'string', 'min:10'],
            'status' => ['required', 'in:0,1'],
            'images' => ['required', 'array', 'min:1', 'max:'.$limits['max_images']],
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'images.max' => 'Số lượng ảnh vượt quá giới hạn của gói hiện tại.',
            'location.required' => 'Vui lòng chọn địa chỉ trên bản đồ để lấy tọa độ.',
            'location.regex' => 'Tọa độ không hợp lệ. Vui lòng chọn lại địa chỉ trên bản đồ.',
        ]);

        $postData = [
            'title' => $validated['title'],
            'seller_id' => $user->id,
            'category_id' => (int) $validated['category_id'],
            'price' => $validated['price'],
            'area' => $validated['area'],
            'address' => $validated['address'],
            'location' => $validated['location'],
            'description' => $validated['description'],
            'status' => $validated['status'],
        ];

        if (Schema::hasColumn('posts', 'slug')) {
            $postData['slug'] = Str::slug($validated['title']).'-'.Str::random(8);
        }

        $post = Post::create($postData);

        foreach ($validated['images'] as $index => $imageFile) {
            $path = $imageFile->store('posts', 'public');

            Image::create([
                'product_id' => $post->id,
                'image_url' => '/storage/'.$path,
                'is_thumbnail' => $index === 0,
            ]);
        }

        return redirect()->route('profile')->with('status', 'post-created');
    }

    public function postsell()
    {
        return inertia('Client/PostSell');
    }
    public function postrent()
    {
        return inertia('Client/PostRent');
    }
    public function detail(Request $request, ?string $postIdentifier = null): Response
    {
        $postQuery = Post::query()
            ->with([
                'images:id,product_id,image_url,is_thumbnail',
                'category:id,category_name,parent_id',
                'category.parentCategory:id,category_name',
                'seller:id,name,phone_number,avatar',
            ]);

        if ($postIdentifier !== null) {
            $postQuery->where(function ($query) use ($request) {
                $query->where('status', '1');

                if ($request->user()) {
                    $query->orWhere('seller_id', $request->user()->id);
                }
            });

            $postQuery->where(function ($query) use ($postIdentifier) {
                if (ctype_digit($postIdentifier)) {
                    $query->whereKey((int) $postIdentifier);

                    return;
                }

                $query->where('slug', $postIdentifier);
            });

            $post = $postQuery->firstOrFail();
        } else {
            $post = $postQuery
                ->where('status', '1')
                ->latest('id')
                ->first();
        }

        if (! $post) {
            return inertia('Client/PostDetail', [
                'post' => null,
                'relatedPosts' => [],
            ]);
        }

        $sellerPostCount = $post->seller
            ? Post::query()
                ->where('seller_id', $post->seller_id)
                ->where('status', '1')
                ->count()
            : 0;

        $relatedPosts = Post::query()
            ->with('thumbnailImage:id,product_id,image_url')
            ->where('status', '1')
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->latest('id')
            ->limit(3)
            ->get()
            ->map(fn (Post $item) => [
                'id' => $item->id,
                'title' => $item->title,
                'price' => $item->price,
                'address' => $item->address,
                'img' => $item->thumbnailImage?->image_url ?? '',
            ])
            ->values()
            ->all();

        $featuredPosts = Post::query()
            ->with([
                'thumbnailImage:id,product_id,image_url',
                'category:id,category_name,parent_id',
                'category.parentCategory:id,category_name',
                'seller:id,name,phone_number,avatar',
            ])
            ->where('status', '1')
            ->whereExists(function ($query) {
                $query->selectRaw('1')
                    ->from('listing_packages')
                    ->whereColumn('listing_packages.user_id', 'posts.seller_id')
                    ->where('listing_packages.status', '1')
                    ->whereIn('listing_packages.package_type', ['1', '2'])
                    ->whereDate('listing_packages.expiry_date', '>=', now()->toDateString());
            })
            ->latest('id')
            ->limit(6)
            ->get()
            ->map(fn (Post $item) => [
                'id' => $item->id,
                'title' => $item->title,
                'price' => $item->price,
                'address' => $item->address,
                'img' => $item->thumbnailImage?->image_url ?? '',
                'category_name' => $item->category?->category_name ?? '',
                'listing_type' => $item->category?->parentCategory?->category_name ?? '',
                'seller_name' => $item->seller?->name ?? '',
                'seller_avatar' => $item->seller?->avatar ?? '',
            ])
            ->values()
            ->all();

        return inertia('Client/PostDetail', [
            'post' => [
                'id' => $post->id,
                'slug' => $post->slug,
                'title' => $post->title,
                'price' => $post->price,
                'area' => $post->area,
                'address' => $post->address,
                'location' => $post->location,
                'description' => $post->description,
                'created_at' => $post->created_at,
                'expires_at' => $post->created_at?->copy()?->addDays(30),
                'category_name' => $post->category?->category_name ?? '',
                'listing_type' => $post->category?->parentCategory?->category_name ?? '',
                'images' => $post->images
                    ->sortByDesc(fn (Image $image) => (int) $image->is_thumbnail)
                    ->values()
                    ->map(fn (Image $image) => $image->image_url)
                    ->all(),
                'seller_name' => $post->seller?->name ?? 'Chính chủ',
                'seller_phone' => $post->seller?->phone_number ?? '',
                'seller_avatar' => $post->seller?->avatar ?? '',
                'seller_post_count' => $sellerPostCount,
            ],
            'relatedPosts' => $relatedPosts,
            'featuredPosts' => $featuredPosts,
        ]);
    }
}
