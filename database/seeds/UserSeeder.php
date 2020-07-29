<?php

use App\Models\Role;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $admin = new User();
        $admin->forceFill([
            'email' => 'admin@example.com',
            'password' => Hash::make('secret'),
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'role_id' => Role::getAdminRole()->id,
            'email_verified_at' => now(),
        ]);
        $admin->save();

        $admin = new User();
        $admin->forceFill([
            'email' => 'group_manager@example.com',
            'password' => Hash::make('secret'),
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'role_id' => Role::getGroupManagerRole()->id,
            'email_verified_at' => now(),
        ]);
        $admin->save();

        for($i = 1; $i <= 3; $i++) {
            $manager = new User();
            $manager->forceFill([
                'email' => 'manager'.$i.'@example.com',
                'password' => Hash::make('secret'),
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'role_id' => Role::getManagerRole()->id,
                'phone' => $faker->phoneNumber,
                'last_login_at' => $faker->dateTime,
                'email_verified_at' => now(),
            ]);
            $manager->save();

            $manager->organization()->create([
                'name' => $faker->company,
                'license_capacity' => 100,
            ]);
            $manager->fresh('organization');

            for($k = 1; $k <= 10; $k++) {
                $number = ($i - 1) * 10 + $k;
                $employee = new User();
                $employee->forceFill([
                    'email' => 'employee'.$number.'@example.com',
                    'password' => Hash::make('secret'),
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'role_id' => Role::getEmployeeRole()->id,
                    'phone' => $faker->phoneNumber,
                    'organization_id' => $manager->organization->id,
                    'employee_id' => $faker->bankAccountNumber,
                    'job_role' => $faker->jobTitle,
                    'username' => $faker->userName,
                    'date_of_birth' => $faker->date(),
                    'email_verified_at' => now(),
                ]);
                $employee->save();
            }
        }
    }
}
