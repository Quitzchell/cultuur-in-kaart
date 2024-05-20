<?php

namespace Database\Seeders;

use App\Models\Discipline;
use Illuminate\Database\Seeder;

class DisciplineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $disciplines = [
            ['name' => 'Dans'],
            ['name' => 'Sport'],
            ['name' => 'Beeldende kunst'],
            ['name' => 'Theater'],
            ['name' => 'Muziek'],
            ['name' => 'Natuur'],
        ];

        foreach ($disciplines as $discipline) {
            Discipline::create($discipline);
        }
    }
}
