<?php

namespace Database\Seeders;

use App\Models\RequestType;
use Illuminate\Database\Seeder;

class RequestTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $request_types = ['Create', 'Update', 'Delete'];
        foreach ($request_types as $request_type) {
            $already_exist = RequestType::where('request_type_name', $request_type)->first();
            if ($already_exist == null) {
                RequestType::create(['request_type_name' => $request_type]);
            }
        }
    }
}
