<?php

namespace App\Filament\Resources\Songs\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class SongForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([

                // 🔝 INFORMASI + ARTIST + GENRE
                Section::make('Informasi Lagu')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->required()
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

                        Select::make('key')
                            ->label('Nada Dasar')
                            ->options([
                                'C' => 'C',
                                'C#' => 'C#',
                                'D' => 'D',
                                'D#' => 'D#',
                                'E' => 'E',
                                'F' => 'F',
                                'F#' => 'F#',
                                'G' => 'G',
                                'G#' => 'G#',
                                'A' => 'A',
                                'A#' => 'A#',
                                'B' => 'B',
                            ])
                            ->searchable()
                            ->required(),

                        TextInput::make('youtube_url')
                            ->label('YouTube URL')
                            ->url()
                            ->prefixIcon('heroicon-o-link')
                            ->placeholder('https://www.youtube.com/watch?v=xxxx'),

                        Select::make('artists')
                            ->relationship('artists', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->required(),

                        Select::make('genres')
                            ->relationship('genres', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable(),

                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'review' => 'Published',
                            ])
                            ->default('draft')
                            ->required(),

                    ]),

                // 🔽 LYRICS + PREVIEW (SIDE BY SIDE)
                Section::make('Lyrics & Preview')
                    ->columns(2)
                    ->schema([

                        Textarea::make('lyrics')
                            ->required()
                            ->rows(40)
                            ->live()
                            ->columnSpan(1)
                            ->helperText('Gunakan format chord: [G] Lirik lagu [Am] lanjut'),

                        Placeholder::make('preview')
                        ->label('Preview')
                        ->columnSpan(1)
                        ->content(function ($get) {
                            $lyrics = $get('lyrics');

                            if (! $lyrics) {
                                return 'Preview akan muncul di sini...';
                            }

                            $lines = explode("\n", $lyrics);
                            $html = '';

                            foreach ($lines as $line) {
                                $parts = preg_split('/(\[[^\]]+\])/', $line, -1, PREG_SPLIT_DELIM_CAPTURE);

                                $chordLine = '';
                                $textLine  = '';

                                $currentChord = '';

                                foreach ($parts as $part) {
                                    if (preg_match('/\[([^\]]+)\]/', $part, $match)) {
                                        // ini chord
                                        $currentChord = $match[1];
                                    } else {
                                        // ini teks
                                        $words = explode(' ', $part);

                                        foreach ($words as $word) {
                                            if ($word === '') continue;

                                            $length = strlen($word) + 1;

                                            // chord di atas kata
                                            if ($currentChord) {
                                                $chordLine .= str_pad($currentChord, $length, ' ');
                                                $currentChord = '';
                                            } else {
                                                $chordLine .= str_repeat(' ', $length);
                                            }

                                            $textLine .= $word . ' ';
                                        }
                                    }
                                }

                                $html .= '
                                    <div style="margin-bottom:12px;font-family:monospace;">
                                        <div style="color:#2563eb;font-weight:bold;white-space:pre;">' . e($chordLine) . '</div>
                                        <div style="white-space:pre;">' . e($textLine) . '</div>
                                    </div>
                                ';
                            }

                            return new \Illuminate\Support\HtmlString($html);
                        }),
                    ]),
            ]);
    }
}
