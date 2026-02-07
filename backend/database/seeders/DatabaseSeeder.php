<?php

namespace Database\Seeders;

use App\Models\BackendUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = BackendUser::query()->first();

        if (! $user) {
            $user = BackendUser::query()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        } elseif (! $user->email_verified_at) {
            $user->forceFill([
                'email_verified_at' => now(),
            ])->save();
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $permissions = [
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        $adminRole->syncPermissions($permissions);
        if (BackendUser::role('admin')->doesntExist()) {
            $user->assignRole($adminRole);
        }
    }
}
