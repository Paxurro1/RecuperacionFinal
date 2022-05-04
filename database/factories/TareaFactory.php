<?php

namespace Database\Factories;

use App\Models\Tarea;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tarea>
 */
class TareaFactory extends Factory
{

    protected $model = Tarea::class;
    public static $ID;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'descripcion' => $this->faker->text(30),
            'dificultad' => $this->faker->randomElement(['S', 'M', 'L', 'XL']),
            'estimacion' => rand(1,15),
            'f_comienzo' => $this->faker->dateTimeBetween('-1 week', '+1 days'),
            'f_fin' => $this->faker->dateTimeBetween('-1 days', '+2 week'),
            'porcentaje' => rand(1,100),
            'id_proyecto'=>self::$ID,
        ];
    }
}
