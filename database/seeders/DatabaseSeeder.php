<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {



        $role_admin = Role::create(['name' => 'admin']);
        $role_user = Role::create(['name' => 'user']);
        $permission_add = Permission::create(['name' => 'create']);
        $permission_edit = Permission::create(['name' => 'edit']);
        $permission_read = Permission::create(['name' => 'read']);
        $permission_delete = Permission::create(['name' => 'delete']);

        $role_admin->syncPermissions([
            $permission_add,
            $permission_edit,
            $permission_read,
            $permission_delete,

        ]);
        $role_user->syncPermissions([
            $permission_read,
        ]);


        User::factory(99)->create();

        $admin = User::create([

            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),


        ]);
        $admin->assignRole('admin');
        $user = User::create([

            'name' => 'user',
            'email' => 'user@user.com',
            'password' => Hash::make('password'),


        ]);
        $user->assignRole('user');

        Category::factory(100)->create();
        Article::factory(100)->create();
    }
}
