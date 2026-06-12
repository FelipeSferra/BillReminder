<?php

namespace App\Filament\Clusters\Admin\ManageUsers\Resources\Roles;

use App\Filament\Clusters\Admin\ManageUsers\ManageUsersCluster;
use App\Filament\Clusters\Admin\ManageUsers\Resources\Roles\Pages\ListRoles;
use App\Filament\Clusters\Admin\ManageUsers\Resources\Roles\Schemas\RoleForm;
use App\Filament\Clusters\Admin\ManageUsers\Resources\Roles\Tables\RolesTable;
use App\Models\Role;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static string|BackedEnum|null $navigationIcon = 'hugeicons-finger-access';

    protected static ?string $cluster = ManageUsersCluster::class;

    protected static ?string $recordTitleAttribute = 'Role';

    protected static ?int $navigationSort = 2;

    public static function getModelLabel(): string
    {
        return 'Perfil';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Perfis';
    }

    public static function form(Schema $schema): Schema
    {
        return RoleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RolesTable::configure($table);
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
            'index' => ListRoles::route('/'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
