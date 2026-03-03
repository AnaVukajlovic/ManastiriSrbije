<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            MonasteriesCsvSeeder::class,
            KtitorSeeder::class,
            CalendarDaysSeeder::class,
        ]);
        $this->call(\Database\Seeders\CuriositiesSeeder::class);
    }
}