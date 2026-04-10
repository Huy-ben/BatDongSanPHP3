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
            'status' => ['required', 'in:0,1'],
            'images' => ['required', 'array', 'min:1', 'max:'.$limits['max_images']],
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'images.max' => 'Số lượng ảnh vượt quá giới hạn của gói hiện tại.',
        ]);

        $postData = [
            'title' => $validated['title'],
            'seller_id' => $user->id,
            'category_id' => (int) $validated['category_id'],
            'price' => $validated['price'],
            'area' => $validated['area'],
            'address' => $validated['address'],
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
    public function detail()
    {
        return inertia('Client/PostDetail');
    }
}
