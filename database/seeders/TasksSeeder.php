<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->find(1);

        for ($i = 0; $i < 15; $i++) {
            Task::query()->create([
                'user_id' => $user->id,
                'priority' => fake()->boolean(),
                'frequency' => [null, 'daily', 'weekly', 'monthly'][rand(0, 3)],
                'name' => fake()->sentence(rand(3, 4)),
                'description' => fake()->paragraph(2),
                'due_at' => now()->addDays(rand(-3, 7)),
            ]);
        }
    }
}
