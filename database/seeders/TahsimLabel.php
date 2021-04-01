<?php

namespace Database\Seeders;

use App\Models\Tahsimlable;
use Illuminate\Database\Seeder;

class TahsimLabel extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tahsimlable::insert([
            ["name" => "تعداد پرسنل"],
            ["name" => "میزان تولید"],
            ["name" => "-"],
        ]);
    }
}
