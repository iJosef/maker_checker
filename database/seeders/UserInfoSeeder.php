<?php

namespace Database\Seeders;

use App\Models\UserInfo;
use Illuminate\Database\Seeder;

class UserInfoSeeder extends Seeder
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
                'first_name'          => "Ella",
                'last_name'           => "Michaels",
                'email'               => "ella.michaels@gmail.com",
                'created_by'          =>  "1",
                'approved_by'         =>  "2",
            ],
            [
                'first_name'          => "Peter",
                'last_name'           => "Richy",
                'email'               => "peter.richy@gmail.com",
                'created_by'          =>  "1",
                'approved_by'         =>  "2",
            ],
            [
                'first_name'          => "Mary",
                'last_name'           => "Harrt",
                'email'               => "mary.harry@gmail.com",
                'created_by'          =>  "1",
                'approved_by'         =>  "2",
            ],
        ];

        foreach ($admin_users as $key => $value) {
            if (UserInfo::where("email", $value['email'])->first() == null) {
                UserInfo::create($value);
            }
        }
    }
}
