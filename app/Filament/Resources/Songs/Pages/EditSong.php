<?php

namespace App\Filament\Resources\Songs\Pages;

use App\Filament\Resources\Songs\SongResource;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSong extends EditRecord
{
    protected static string $resource = SongResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
        ];
        
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
