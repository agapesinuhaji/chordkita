<?php

namespace App\Filament\Resources\Songs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SongForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) =>
                        $set('slug', \Illuminate\Support\Str::slug($state))
                    ),

                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),

                Select::make('artist_id')
                    ->relationship('artist', 'name')
                    ->required(),

                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),

                Select::make('writer_id')
                    ->relationship('writer', 'name'),

                Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'assigned' => 'Assigned',
                        'submitted' => 'Submitted',
                        'published' => 'Published',
                    ]),

                Textarea::make('chord')
                    ->rows(20)
                    ->columnSpanFull(),
            ]);
    }
}
