<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostsResource;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;

class CreatePosts extends CreateRecord
{
    protected static string $resource = PostsResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $imageUrls = $data['images'] ?? [];
        unset($data['images']);

        $post = static::getModel()::create($data);

        /** @var Post $post */
        $post->syncImages($imageUrls);

        return $post;
    }
}
