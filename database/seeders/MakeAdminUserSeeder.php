<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class MakeAdminUserSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $userTest = User::factory()->create([
            'name' => 'Felipe Admin',
            'email' => 'felipeadmin@example.com',
            'password' => Hash::make('felipe123')
        ]);
        $userTest->assignRole($adminRole);
    }
}
