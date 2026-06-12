<?php

namespace App\Filament\Pages;

use Filament\Notifications\Notification;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    public function getColumns(): int|array
    {
        return 6;
    }

    public function mount(): void
    {
        if (session()->has('filament_warning')) {
            Notification::make()
                ->title(session('filament_warning'))
                ->danger()
                ->send();
        }
    }
}
