<?php

namespace App\Providers;

use Livewire\Livewire;
use Filament\Tables\Table;
use Illuminate\Support\Facades\URL;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Facades\Route;
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

        Livewire::setScriptRoute(function ($handle) {
            return Route::get('/randomdoa/livewire/livewire.js', $handle);
        });

        Livewire::setUpdateRoute(function ($handle) {
            return Route::post('/randomdoa/livewire/update', $handle);
        });

        if (app()->environment('production')) {
            \URL::forceScheme('https');
        }
    }
}
