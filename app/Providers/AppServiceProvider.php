<?php

namespace App\Providers;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\View\PanelsRenderHook;
use Filament\Schemas\Components\Grid;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentView;

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

        FilamentView::registerRenderHook(
            PanelsRenderHook::GLOBAL_SEARCH_AFTER,
            fn() => Action::make('mainPage')
                ->label('Halaman Utama')
                ->icon('heroicon-o-globe-alt')
                ->url(config('app.url'), shouldOpenInNewTab: true)
                ->toHtml()
        );
    }
}
