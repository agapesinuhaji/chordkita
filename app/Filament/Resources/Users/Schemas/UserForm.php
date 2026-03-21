<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('username')
                    ->unique(ignoreRecord: true)
                    ->validationMessages([
                        'unique' => 'Username sudah digunakan, silakan pakai username lain.',
                    ]),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->validationMessages([
                        'unique' => 'Email sudah digunakan, silakan pakai email lain.',
                    ])
                    ->visible(fn ($context) => $context === 'create')
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->required(fn ($context) => $context === 'create')
                    ->visible(fn ($context) => $context === 'create')
                    ->required(),
                FileUpload::make('photo')
                    ->image()
                    ->directory('users')
                    ->disk('public')
                    ->maxSize(2048)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->helperText('Upload gambar (JPG, PNG, WEBP) dengan ukuran maksimal 2MB'),
                Select::make('role')
                    ->options(['admin' => 'Admin', 'writer' => 'Writer', 'user' => 'User'])
                    ->default('user')
                    ->required(),
                Toggle::make('status')
                    ->default(true)
                    ->required(),
            ]);
    }
}
