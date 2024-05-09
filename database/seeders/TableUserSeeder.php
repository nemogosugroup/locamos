<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Acl;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;
use Spatie\Permission\PermissionRegistrar;

class TableUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions        
        Permission::create(['name' => Acl::PERMISSION_VIEW_MANAGER]);
        Permission::create(['name' => Acl::PERMISSION_VIEW_ADMINISTRATOR]);   
        Permission::create(['name' => Acl::PERMISSION_VIEW_USER]);

        // create roles and assign existing permissions
        
        $role = Role::create(['name' => Acl::ROLE_ADMIN]);
        $role->givePermissionTo(Acl::PERMISSION_VIEW_ADMINISTRATOR);
        $role->givePermissionTo(Acl::PERMISSION_VIEW_MANAGER);
        $role->givePermissionTo(Acl::PERMISSION_VIEW_USER);
        
        $role1 = Role::create(['name' => Acl::ROLE_MANAGER]);
        $role1->givePermissionTo(Acl::PERMISSION_VIEW_MANAGER);
        $role1->givePermissionTo(Acl::PERMISSION_VIEW_USER);

        $role5 = Role::create(['name' => Acl::ROLE_USER]);
        $role5->givePermissionTo(Acl::PERMISSION_VIEW_USER);

        $user = \App\Models\User::factory()->create([
            'email' => 'admin@gosu.vn',
            'profile_id' => 0,
            'first_name' => 'Gosu',
            'last_name' => 'VN',
            'avatar' => null,
            'gender' => 1,
            'password' => Hash::make(123456),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        $role = Role::findByName(Acl::ROLE_ADMIN);
        $user->syncRoles($role);
    }
}
