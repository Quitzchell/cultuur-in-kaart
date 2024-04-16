<?php

namespace Database\Seeders;

use App\Models\Neighbourhood;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NeighbourhoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $neighbourhoods = [
            ['name' => 'Alle Wijken'],
            ['name' => 'Nieuw-Krispijn'],
            ['name' => 'Noodflank/Lijnbaan'],
            ['name' => 'Crabbehof'],
            ['name' => 'Sterrenburg'],
            ['name' => 'Wielwijk'],
            ['name' => 'De staart'],
            ['name' => 'Centrum'],
            ['name' => 'Stadspolders'],
        ];

        foreach ($neighbourhoods as $neighbourhood) {
            Neighbourhood::create($neighbourhood);
        }
    }
}
