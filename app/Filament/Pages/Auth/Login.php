<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Notifications\Notification;

class Login extends BaseLogin
{
    public function mount(): void
    {
        parent::mount();

        if (session()->has('filament_warning')) {
            Notification::make()
                ->title(session('filament_warning'))
                ->danger()
                ->send();
        }
    }
}
