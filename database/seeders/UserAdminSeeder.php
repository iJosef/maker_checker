<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin_users = [
            [
                'name'          => "John Doe",
                'email'         => "john.doe@gmail.com",
                'password'      => bcrypt('password1'),
            ],
            [
                'name'          => "Jane Doe",
                'email'         => "jane.doe@gmail.com",
                'password'      => bcrypt('password1'),
            ],
        ];

        foreach ($admin_users as $key => $value) {
            if (User::where("email", $value['email'])->first() == null) {
                User::create($value);
            }
        }
    }
}
