<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // User::factory(10)->create();

    // User::factory()->create([
    //   'name' => 'Test User',
    //   'email' => 'test@example.com',
    // ]);

    $adminRole = Role::updateOrCreate(['name' => 'admin']);

    // Define permissions
    $permissions = [
      'view users',
      'edit users',
      'delete users',
      'edit images',
      'delete images',
      'view flags',
      'edit flags',
      'delete flags',
    ];

    // Create and assign permissions to the admin role
    foreach ($permissions as $permission) {
      $perm = Permission::updateOrCreate(['name' => $permission]);
      $adminRole->givePermissionTo($perm);
    }

    // Assign the admin role to a specific user
    $attributes = [
      'name' => config('admin.name'),
      'email' => config('admin.email'),
    ];

    $values = [
      'password' => Hash::make(config('admin.password')),
    ];

    $admin = User::updateOrCreate($attributes, $values); // Assuming the first user is the admin
    if ($admin) {
      $admin->assignRole('admin');
    }

    $this->call([TagSeeder::class]);
  }
}
