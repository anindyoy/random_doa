<?php

namespace App\Filament\Resources\Doas;

use BackedEnum;
use App\Models\Doa;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use App\Filament\Resources\Doas\Pages\EditDoa;
use App\Filament\Resources\Doas\Pages\ListDoas;
use App\Filament\Resources\Doas\Pages\CreateDoa;
use App\Filament\Resources\Doas\Schemas\DoaForm;
use App\Filament\Resources\Doas\Tables\DoasTable;

class DoaResource extends Resource
{
    protected static ?string $model = Doa::class;
    protected static ?string $modelLabel = 'Doa';
    protected static ?string $pluralModelLabel = 'Doa';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'judul';

    public static function form(Schema $schema): Schema
    {
        return DoaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DoasTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDoas::route('/'),
            'create' => CreateDoa::route('/create'),
            'edit' => EditDoa::route('/{record}/edit'),
        ];
    }
}
