<?php

namespace App\Filament\Clusters\Admin\ManageUsers;

use BackedEnum;
use UnitEnum;
use Filament\Clusters\Cluster;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Support\Icons\Heroicon;

class ManageUsersCluster extends Cluster
{
    protected static string|UnitEnum|null $navigationGroup = 'Sistema';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    protected static ?string $clusterBreadcrumb = 'Gerenciamento de usuários';

    protected static ?string $navigationLabel = 'Gerenciamento de usuários';
}
