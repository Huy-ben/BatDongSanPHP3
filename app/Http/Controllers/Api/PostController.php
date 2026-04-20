<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function sell(Request $request)
    {
        return $this->listingByType('Bán', $request);
    }

    public function rent(Request $request)
    {
        return $this->listingByType('Cho thuê', $request);
    }

    private function listingByType(string $type, Request $request)
    {
        $query = Post::query()
            ->with([
                'category:id,category_name,parent_id',
                'thumbnailImage:id,product_id,image_url',
                'seller:id,name,phone_number',
            ])
            ->where('status', Post::STATUS_PUBLISHED)
            ->whereRelation('category.parentCategory', 'category_name', $type)
            ->when($request->filled('keyword'), function ($query) use ($request) {
                $keyword = trim((string) $request->query('keyword'));

                $query->where(function ($nestedQuery) use ($keyword) {
                    $nestedQuery->where('title', 'like', "%{$keyword}%")
                        ->orWhere('address', 'like', "%{$keyword}%")
                        ->orWhere('location', 'like', "%{$keyword}%")
                        ->orWhere('description', 'like', "%{$keyword}%")
                        ->orWhereHas('category', function ($categoryQuery) use ($keyword) {
                            $categoryQuery->where('category_name', 'like', "%{$keyword}%");
                        });
                });
            })
            ->when($request->filled('property_type'), function ($query) use ($request) {
                $propertyType = trim((string) $request->query('property_type'));

                $query->whereRelation('category', 'category_name', $propertyType);
            })
            ->when($request->filled('location'), function ($query) use ($request) {
                $location = trim((string) $request->query('location'));

                $query->where(function ($nestedQuery) use ($location) {
                    $nestedQuery->where('address', 'like', "%{$location}%")
                        ->orWhere('location', 'like', "%{$location}%");
                });
            })
            ->when($request->filled('price_min'), function ($query) use ($request) {
                $query->where('price', '>=', (int) $request->query('price_min'));
            })
            ->when($request->filled('price_max'), function ($query) use ($request) {
                $query->where('price', '<=', (int) $request->query('price_max'));
            })
            ->when($request->filled('area_min'), function ($query) use ($request) {
                $query->where('area', '>=', (float) $request->query('area_min'));
            })
            ->when($request->filled('area_max'), function ($query) use ($request) {
                $query->where('area', '<=', (float) $request->query('area_max'));
            });

        $posts = $query->orderByDesc('id')
            ->paginate(12, ['*'], 'page', max(1, (int) $request->query('page', 1)))
            ->through(function (Post $post) {
                return [
                    'id' => $post->id,
                    'slug' => $post->slug,
                    'title' => $post->title,
                    'price' => $post->price,
                    'area' => $post->area,
                    'address' => $post->address,
                    'location' => $post->location,
                    'description' => $post->description,
                    'created_at' => $post->created_at,
                    'category_name' => $post->category?->category_name ?? '',
                    'img' => $post->thumbnailImage?->image_url ?? '',
                    'seller_name' => $post->seller?->name ?? '',
                    'seller_phone' => $post->seller?->phone_number ?? '',
                ];
            });
        return response()->json($posts);
    }
}
