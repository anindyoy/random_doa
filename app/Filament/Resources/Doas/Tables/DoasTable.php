<?php

namespace App\Filament\Resources\Doas\Tables;

use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class DoasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn($query) => auth()->user()->is_admin
                    ? $query
                    : $query->orWhere('user_id', auth()->id())
            )
            ->columns([
                TextColumn::make('judul')
                    ->searchable()
                    ->wrap(),
                ImageColumn::make('gambar')
                    ->disk('public')
                    ->visibility('public'),
                TextColumn::make('sumber_desain')
                    ->searchable(),
                IconColumn::make('untuk_pribadi')
                    ->boolean(),
                TextColumn::make('user.name')
                    ->searchable()
                    ->visible(auth()->user()->is_admin),
                IconColumn::make('visibility')
                    ->boolean(),
                TextColumn::make('tags.nama'),
                TextColumn::make('ajuan')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->hiddenLabel()
                    ->visible(
                        fn($record) => auth()->user()->is_admin
                            || $record->user_id === auth()->id()
                    ),
                DeleteAction::make()->hiddenLabel()
                    ->visible(
                        fn($record) => auth()->user()->is_admin
                            || $record->user_id === auth()->id()
                    ),
            ])
            ->defaultSort('id', 'desc')
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
