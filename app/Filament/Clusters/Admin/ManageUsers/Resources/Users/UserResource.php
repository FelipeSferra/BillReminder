<?php

namespace App\Filament\Clusters\Admin\ManageUsers\Resources\Users;

use App\Filament\Clusters\Admin\ManageUsers\ManageUsersCluster;
use App\Filament\Clusters\Admin\ManageUsers\Resources\Users\Pages\ListUsers;
use App\Filament\Clusters\Admin\ManageUsers\Resources\Users\Schemas\UserForm;
use App\Filament\Clusters\Admin\ManageUsers\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $cluster = ManageUsersCluster::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $recordTitleAttribute = 'User';

    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return 'Usuário';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Usuários';
    }

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
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
            'index' => ListUsers::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with('roles');
    }
}
