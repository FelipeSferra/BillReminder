<?php

namespace App\Filament\Clusters\Admin\ManageUsers\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome')
                    ->required()
                    ->disabled(fn(User $record) => $record->id === auth()->id()),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->disabled(fn(User $record) => $record->id === auth()->id()),
                TextInput::make('password')
                    ->label('Senha')
                    ->password()
                    ->disabled(fn(User $record) => $record->id === auth()->id()),
                Select::make('roles')
                    ->label('Perfil')
                    ->relationship('roles', 'name')
                    ->required(),
            ]);
    }
}
