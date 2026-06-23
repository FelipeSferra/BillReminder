<?php

namespace App\Providers;

use BezhanSalleh\PanelSwitch\PanelSwitch;
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
        PanelSwitch::configureUsing(function (PanelSwitch $panelSwitch) {
            $user = auth()->user();

            $panels = ['app'];

            if ($user?->hasRole('admin')) {
                $panels[] = 'admin';
            }

            $panelSwitch
                ->panels($panels)
                ->labels([
                    'app' => 'Aplicação',
                    'admin' => 'Administração',
                ])
                ->icons([
                    'app' => 'heroicon-o-home',
                    'admin' => 'heroicon-o-cog-6-tooth',
                ])
                ->renderHook('panels::topbar.end')
                ->iconSize(16)
                ->slideOver()
                ->modalHeading('Selecionar painel')
                ->modalWidth('sm');
        });
    }
}
