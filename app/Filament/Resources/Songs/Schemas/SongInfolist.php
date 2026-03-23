<?php

namespace App\Filament\Resources\Songs\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SongInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('slug'),
                TextEntry::make('lyrics')
                    ->columnSpanFull(),
                TextEntry::make('key')
                    ->placeholder('-'),
                TextEntry::make('youtube_url')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge(),
                IconEntry::make('is_published')
                    ->boolean(),
                TextEntry::make('published_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('views')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
