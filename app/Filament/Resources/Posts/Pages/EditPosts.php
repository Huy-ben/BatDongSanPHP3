<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostsResource;
use App\Models\Post;
use Filament\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;

class EditPosts extends EditRecord
{
    protected static string $resource = PostsResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $imageUrls = $data['images'] ?? [];
        unset($data['images']);

        $record->update($data);

        if (is_array($imageUrls)) {
            /** @var Post $record */
            $record->syncImages($imageUrls);
        }

        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
