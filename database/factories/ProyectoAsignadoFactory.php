<?php

namespace Database\Factories;

use App\Models\ProyectoAsignado;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProyectoAsignado>
 */
class ProyectoAsignadoFactory extends Factory
{

    protected $model = ProyectoAsignado::class;
    public static $DNI;
    public static $ID;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id_proyecto'=>self::$ID,
            'dni'=>self::$DNI,
        ];
    }
}
