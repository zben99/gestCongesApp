<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RolePermissionSeeder extends Seeder
{
    public function run()
{
    // Permissions
    $permissions = [
        'insert', 'modifier', 'delete', 'supprime', 'view',
        'approved_by1', 'approved_by2', 'approved_permission'
    ];

    foreach ($permissions as $permission) {
        Permission::create(['name' => $permission]);
    }

    // Rôles et assignation des permissions
    $employeRole = Role::create(['name' => 'employés']);
    $managerRole = Role::create(['name' => 'manager']);
    $rhRole = Role::create(['name' => 'responsables RH']);
    $adminRole = Role::create(['name' => 'administrateurs']);

    // Exemples d'assignation
    $employeRole->givePermissionTo(['view','insert','modifier']);
    $managerRole->givePermissionTo(['view','insert','modifier', 'approved_by1']);
    $rhRole->givePermissionTo(['view', 'approved_by2','insert','modifier', 'approved_permission']);
    $adminRole->givePermissionTo(Permission::all());
}
}

