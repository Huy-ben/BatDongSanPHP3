<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostsResource;
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;

class EditPosts extends EditRecord
{
    protected static string $resource = PostsResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $hasImagesInput = array_key_exists('images', $data);
        $imageUrls = array_values(array_filter(is_array($data['images'] ?? null) ? $data['images'] : []));
        unset($data['images']);

        $record->update($data);

        if ($hasImagesInput && count($imageUrls) > 0) {
            /** @var Post $record */
            $record->syncImages($imageUrls);
        }

        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('previewClient')
                ->label('Xem trước như client')
                ->icon('heroicon-o-eye')
                ->url(fn () => route('post-detail', ['postIdentifier' => $this->record->id]))
                ->openUrlInNewTab(),
            DeleteAction::make(),
        ];
    }
}
