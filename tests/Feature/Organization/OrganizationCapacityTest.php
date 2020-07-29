<?php

class OrganizationCapacityTest extends \Tests\Feature\TestCase
{
    use \Tests\Feature\WorksWithUsers;

    public function test_cant_add_more_users_than_capacity()
    {
        $manager = $this->manager();
        $user = factory(\App\Models\User::class)->create();
        $user2 = factory(\App\Models\User::class)->create();

        $organization =  $manager->managerOrganization();
        $organization->employees()->delete();
        $organization->update(
            [
                'license_capacity' => 2,
            ]
        );

        $organization->addEmployee($user);

        $this->actingAs($manager);
        $this->expectException(\App\Exceptions\OrganizationCapacityOverflow::class);
        $this->post(route('employees.store'), [
            "first_name" => "test_first_name",
            "last_name" => "test_last_name",
            "email" => "user_capacity@example.com",
            "phone" => "",
            "employee_id" => "",
            "on_leave" => "0",
            "role_id" => \App\Models\Role::getEmployeeRole()->getKey(),
            "job_role" => "18",
            "username" => "ttest_last_name",
            "password" => "bHU09KGc",
        ]);
        $this->assertEquals(2, $organization->allEmployees()->count());
    }
}
