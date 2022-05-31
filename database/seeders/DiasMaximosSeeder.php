<?php

namespace Database\Seeders;

use App\Models\DiasMaximos;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DiasMaximosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DiasMaximos::create([
            'dias' => 60
        ]);
    }
}
