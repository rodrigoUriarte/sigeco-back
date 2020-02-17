<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\BackpackUser;
use App\Models\Persona;
use Faker\Generator as Faker;

$factory->define(Persona::class, function (Faker $faker) {
    // $comedor = App\Models\Comedor::random();
    // $idComedor = $comedor->id;
    // $nombreComedor = $comedor->descripcion;
    $idc = App\Models\Comedor::pluck('id')->toArray();
    $uas = App\Models\UnidadAcademica::pluck('id')->toArray();
    return [
        'dni' => $faker->unique()->randomNumber($nbDigits = 8, $strict = true),
        'nombre' => $faker->firstName,
        'apellido' => $faker->lastName,
        'telefono' => $faker->phoneNumber,
        'email' => $faker->unique()->safeEmail,
        'comedor_id' => $faker->randomElement($idc),
        'unidad_academica_id' => $faker->randomElement($uas),
    ];
});
