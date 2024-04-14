<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasks = [
            ['name' => 'Kennisdeling'],
            ['name' => 'Aansluiten bij'],
            ['name' => 'Opzetten van'],
            ['name' => 'Projectleider'],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
