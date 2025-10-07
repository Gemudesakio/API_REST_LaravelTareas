<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;
use App\Models\Tag;
use Illuminate\Support\Facades\Hash;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'Demo',
            'email' => 'demo@example.com',
            'password' => Hash::make('password'),
        ]);

        $tags = collect(['trabajo','personal','estudio'])
            ->map(fn($n) => Tag::firstOrCreate(['name' => $n]));

        foreach (range(1,8) as $i) {
            $task = Task::create([
                'user_id' => $user->id,
                'title' => "Tarea $i",
                'description' => "Descripción $i",
                'is_completed' => $i % 3 === 0,
                'completed_at' => $i % 3 === 0 ? now() : null,
            ]);
            $task->tags()->sync($tags->random(rand(0,3))->pluck('id')->toArray());
        }
    }
}
