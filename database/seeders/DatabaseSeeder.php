<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::factory()->create([
            'id' => 1,
            'name' => 'Работа'
        ]);
        Category::factory()->create([
            'id' => 2,
            'name' => 'Дом'
        ]);
        Category::factory()->create([
            'id' => 3,
            'name' => 'Личное'
        ]);
    }
}
