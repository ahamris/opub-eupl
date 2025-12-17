<?php

namespace Database\Seeders;

use App\Helpers\Variable;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Variable::$fullRoles as $role) {
            if (Role::where('name', $role)->doesntExist()) {
                Role::create(['name' => $role]);
            }
        }
        //create permissions
        foreach (Variable::$fullPermissions as $permission => $roles) {

            if (Permission::where('name', $permission)->doesntExist()) {
                //create permission
                $permissionInstance = Permission::create(['name' => $permission, 'guard_name' => Variable::GUARD_NAME]);
                //authorize roles to those permissions
                foreach ($roles as $role) {
                    Role::where('name', $role)->first()->givePermissionTo($permissionInstance);
                }
            }
        }

    }
}

