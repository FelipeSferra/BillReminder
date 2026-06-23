<?php

namespace App\Providers\Filament;

use Andreia\FilamentUiSwitcher\FilamentUiSwitcherPlugin;
use App\Filament\Clusters\App\UserSettings\UserSettingsCluster;
use App\Filament\Pages\Auth\Login;
use App\Filament\Resources\Bills\BillResource;
use App\Filament\Resources\Funds\FundResource;
use App\Filament\Resources\Incomes\IncomeResource;
use App\Filament\Resources\Investiments\InvestimentResource;
use Filament\Actions\Action;
use Filament\Enums\UserMenuPosition;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('app')
            ->path('/')
            ->viteTheme('resources/css/filament/app/theme.css')
            ->login(Login::class)
            ->registration()
            ->passwordReset()
            ->colors([
                'primary' => Color::Purple,
            ])
            ->resources([
                BillResource::class,
                IncomeResource::class,
                FundResource::class,
                InvestimentResource::class
            ])
            ->discoverClusters(in: app_path('Filament/Clusters/App'), for: 'App\Filament\Clusters\App')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->navigationGroups([
                NavigationGroup::make('Finanças')
                    ->collapsible(false),
            ])
            ->pages([])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->userMenu(position: UserMenuPosition::Sidebar)
            ->userMenuItems([
                'profile' => fn(Action $action) => $action
                    ->hidden(),
                Action::make('Configurações')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->url(fn(): string => UserSettingsCluster::getUrl()),
            ])
            ->plugin(
                FilamentUiSwitcherPlugin::make()
                    ->iconRenderHook(PanelsRenderHook::TOPBAR_END)
                    ->withModeSwitcher()
            )
            ->globalSearch(false);
    }
}
