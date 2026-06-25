<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Eparchy;

class EparchySeeder extends Seeder
{
    public function run(): void
    {
$items = [
            'Eparhija žička',
            'Eparhija raško-prizrenska',
            'Eparhija beogradska',
            'Eparhija šumadijska',
            'Eparhija niška',
            'Eparhija banatska',
            'Eparhija bačka',
            'Eparhija braničevska',
            'Eparhija kruševačka',
            'Eparhija mileševska',
            'Eparhija sremska',
            'Eparhija timočka',
            'Eparhija valjevska',
            'Eparhija vranjska',
            'Eparhija šabačka',
        ];

       foreach ($items as $name) {
    Eparchy::updateOrCreate(
        ['name' => $name],
        ['slug' => Str::slug($name)]
    );
}
    }
}
