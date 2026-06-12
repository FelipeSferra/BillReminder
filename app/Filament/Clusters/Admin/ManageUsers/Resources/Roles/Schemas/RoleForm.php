<?php

namespace App\Filament\Clusters\Admin\ManageUsers\Resources\Roles\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome')
                    ->maxLength(50)
                    ->required()
                    ->columnSpanFull(),
                ColorPicker::make('ID_HEX')
                    ->label('Cor')
                    ->required()
                    ->columnSpanFull()
            ]);
    }
}
