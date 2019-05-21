<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
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
                'name' => 'teacher',
                'display_name' => 'Teacher',
                'description' => 'Only teacher',
            ],
            [
                'name' => 'student',
                'display_name' => 'Student',
                'description' => 'Only registered student',
            ]
        ];
        foreach ($roles as $key => $value) {
            Role::create($value);
        }
    }
}
