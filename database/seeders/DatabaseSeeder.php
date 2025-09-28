<?php

namespace Database\Seeders;

use App\Models\Note;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Note::factory()
            ->for($user)
            ->count(2)
            ->sequence(
                ['title' => 'React Performance Optimization'],
                ['title' => 'Japan Travel Planning'],
            )
            ->hasTags(2)
            ->create();
    }
}
