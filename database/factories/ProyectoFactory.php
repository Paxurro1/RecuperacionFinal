<?php

namespace Database\Factories;

use App\Models\Proyecto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Proyecto>
 */
class ProyectoFactory extends Factory
{

    protected $model = Proyecto::class;
    public static $DNI;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nombre' => $this->faker->firstName(). ' ' .  $this->faker->lastName(). ' ' .  $this->faker->lastName(),
            'dni_jefe'=>self::$DNI
        ];
    }
}
