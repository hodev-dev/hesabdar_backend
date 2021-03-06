<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;
use Database\Seeders\GroupSeeder;
use Database\Seeders\TahsimLabel;
use Database\Seeders\SectionSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            GroupSeeder::class,
            TahsimLabel::class,
            SectionSeeder::class
        ]);
    }
}
