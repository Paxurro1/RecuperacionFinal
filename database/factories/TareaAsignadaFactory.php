<?php

namespace Database\Factories;

use App\Models\TareaAsignada;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TareaAsignada>
 */
class TareaAsignadaFactory extends Factory
{

    protected $model = TareaAsignada::class;
    public static $ID;
    public static $DNI;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id_tarea'=>self::$ID,
            'dni'=>self::$DNI
        ];
    }
}
