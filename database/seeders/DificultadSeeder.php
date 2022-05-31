<?php

namespace Database\Seeders;

use App\Models\Dificultad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DificultadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Dificultad::create([
            'dificultad' => 'S'
        ]);
        Dificultad::create([
            'dificultad' => 'M'
        ]);
        Dificultad::create([
            'dificultad' => 'L'
        ]);
        Dificultad::create([
            'dificultad' => 'XL'
        ]);
    }
}
