<?php

namespace App\Filament\Resources\Doas\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;

class DoaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()->schema([
                    Select::make('user_id')
                        ->visible(auth()->user()->is_admin)
                        ->relationship('user', 'name')
                        ->required(),
                    TextInput::make('judul')->required()->columnSpan(2),
                    Textarea::make('keterangan')
                        ->default(null)
                        ->columnSpanFull(),
                    FileUpload::make('gambar')->image(),
                    Grid::make()->schema([
                        TextInput::make('sumber_desain')
                            ->default(null),
                        Toggle::make('visibility')->label('Tampilkan'),
                        Toggle::make('untuk_pribadi'),

                        Select::make('tags')
                            ->relationship('tags', 'nama')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->createOptionForm([
                                TextInput::make('nama')
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('deskripsi')
                                    ->maxLength(65535),
                            ]),
                    ])->columns(1),

                    TextInput::make('riwayat')
                        ->default(null)
                        ->columnSpan(2),

                    // TextInput::make('ajuan')
                    //     ->default(null),
                ])
                    ->columns(3)
                    ->columnSpanFull(),
            ]);
    }
}
