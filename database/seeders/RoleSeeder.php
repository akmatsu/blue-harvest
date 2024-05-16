<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Create roles
    $adminRole = Role::create(['name' => 'admin']);

    // Define permissions
    $permissions = [
      'view users',
      'edit users',
      'delete users',
      'edit images',
      'delete images',
    ];

    // Create and assign permissions to the admin role
    foreach ($permissions as $permission) {
      $perm = Permission::create(['name' => $permission]);
      $adminRole->givePermissionTo($perm);
    }

    // Assign the admin role to a specific user
    $admin = User::find(13); // Assuming the first user is the admin
    if ($admin) {
      $admin->assignRole('admin');
    }
  }
}
