<?php

namespace Database\Seeders;

use App\Handlers\UserTokenHandler;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AuthorizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdminRole = Role::create(['name' => 'super_admin']);
        $callcenterAgentRole = Role::create(['name' => 'callcenter_agent']);
        $supportAgentRole = Role::create(['name' => 'support_agent']);
        $customerRole = Role::create(['name' => 'customer']);


        $callcenterAgentCreatePermission = Permission::create(['name' => 'crud:callcenter_agent']);
        $supportAgentCreatePermission = Permission::create(['name' => 'crud:support_agent']);
        $customerCreatePermission = Permission::create(['name' => 'crud:customer']);
        $callcenterPermission = Permission::create(['name' => 'crud:callcenter']);
        $supportPermission = Permission::create(['name' => 'crud:support']);
        $complainPermission = Permission::create(['name' => 'crud:complain']);
        $departmentPermission = Permission::create(['name' => 'crud:department']);

        $callcenterAgentCreatePermission->syncRoles([$superAdminRole]);
        $supportAgentCreatePermission->syncRoles([$superAdminRole]);
        $customerCreatePermission->syncRoles([$superAdminRole]);
        $departmentPermission->syncRoles([$superAdminRole]);
        $callcenterPermission->syncRoles([$superAdminRole, $callcenterAgentRole]);
        $supportPermission->syncRoles([$superAdminRole, $supportAgentRole]);
        $complainPermission->syncRoles([$superAdminRole, $callcenterAgentRole, $supportAgentRole, $customerRole]);


        $userTokenHandler = new UserTokenHandler();
        $user = $userTokenHandler->createUser( 'superadmin', '00000000000', 'admin@infocom.com', 'admin123');
        $superadmin = new Admin();
        $superadmin->user_id = $user->id;
        $superadmin->save();
        $user->assignRole('super_admin');
    }
}
