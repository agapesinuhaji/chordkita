<?php

namespace App\Filament\Resources\Artists\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ArtistForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->trim() 
                    ->dehydrateStateUsing(fn (string $state): string => Str::title(trim($state)))
                    ->live(debounce: 500)
                    ->afterStateUpdated(function (Set $set, ?string $state) {
                        $set('slug', Str::slug(trim($state)));
                    }),
                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true) // Pastikan slug unik di database
                    ->readOnly(),
                FileUpload::make('photo')
                    ->image()
                    ->directory('artists')
                    ->disk('public')
                    ->maxSize(2048)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->helperText('Upload gambar (JPG, PNG, WEBP) dengan ukuran maksimal 2MB'),
            ]);
    }
}
