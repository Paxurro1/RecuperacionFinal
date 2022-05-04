<?php

namespace Database\Factories;

use App\Models\RolAsignado;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RolAsignado>
 */
class RolAsignadoFactory extends Factory
{
    protected $model = RolAsignado::class;
    public static $ROL;
    public static $DNI;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id_rol'=>self::$ROL,
            'dni'=>self::$DNI
        ];
    }
}
