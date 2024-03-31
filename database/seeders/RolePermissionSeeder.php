<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::create(['name' => 'admin']);

        $models = ['users', 'roles','permissions', 'posts', 'categories'];

        foreach ($models as $model) {
            // Criar permissions para cada ação em cada modelo
            foreach (['view_any', 'view', 'create', 'update', 'delete'] as $action) {
                $permissionName = "{$action}_{$model}";
                $permission = Permission::create(['name' => $permissionName]);

                // Atribuir permission ao role admin
                $role->givePermissionTo($permission);
            }
        }

        $user = User::find(1);
        $adminRole = Role::where('name', 'admin')->first();
        $user->assignRole($adminRole);
    }
}
