<?php

namespace App\Filament\Resources\Songs\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SongsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('artist.name')
                    ->label('Artist')
                    ->sortable(),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),

                TextColumn::make('writer.name')
                    ->label('Writer')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'draft',
                        'warning' => 'assigned',
                        'info' => 'submitted',
                        'success' => 'published',
                    ]),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('submit')
                    ->label('Submit')
                    ->visible(fn ($record) =>
                        auth()->user()->hasRole('writer')
                        && $record->status === 'assigned'
                    )
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'submitted'
                        ]);
                    }),
                Action::make('publish')
                    ->label('Publish')
                    ->visible(fn ($record) =>
                        auth()->user()->hasRole('admin')
                        && $record->status === 'submitted'
                    )
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'published'
                        ]);
                    })
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
