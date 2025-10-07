<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Ejecuta los seeders principales del proyecto.
     */
    public function run(): void
    {
        // Llama al seeder personalizado que creará usuarios, tareas y tags.
        $this->call(DemoSeeder::class);
    }
}
