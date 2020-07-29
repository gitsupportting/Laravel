<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => Role::ADMIN_ROLE,
                'display_name' => 'Administrator'
            ],
            [
                'name' => Role::MANAGER_ROLE,
                'display_name' => 'Manager',
            ],
            [
                'name' => Role::EMPLOYEE_ROLE,
                'display_name' => 'Employee'
            ],
            [
                'name' => Role::GROUP_MANAGER_ROLE,
                'display_name' => 'Group Manager',
            ]
        ];

        foreach ($roles as $roleAttributes) {
            $role = new Role();
            $role->forceFill($roleAttributes);
            $role->save();
        }
    }
}
