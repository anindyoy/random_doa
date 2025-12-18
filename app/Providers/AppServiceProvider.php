<?php

namespace App\Providers;

use Filament\Tables\Table;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Table::configureUsing(function (Table $table): void {
            $table->striped();
        });

        // Set default form grid to 3 columns
        Grid::configureUsing(function (Grid $grid): void {
            // $grid->columns(3);
        });
    }
}
