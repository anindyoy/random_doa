<?php

namespace App\Filament\Resources\Doas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DoaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('judul')
                    ->required(),
                TextInput::make('gambar')
                    ->default(null),
                Textarea::make('keterangan')
                    ->default(null)
                    ->columnSpanFull(),
                TextInput::make('sumber_desain')
                    ->default(null),
                Textarea::make('riwayat')
                    ->default(null)
                    ->columnSpanFull(),
                Toggle::make('untuk_pribadi')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Toggle::make('visibility')
                    ->required(),
                TextInput::make('ajuan')
                    ->default(null),
            ]);
    }
}
