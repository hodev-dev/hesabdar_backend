<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Group::insert([
            ["name" => "تولید"],
            ["name" => "خدمات"],
            ["name" => "اداری"],
        ]);
    }
}
