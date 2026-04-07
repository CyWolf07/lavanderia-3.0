<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Rol;
use App\Models\Prenda;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $rolAdmin = Rol::create(['nombre' => 'Admin', 'descripcion' => 'Administrador del sistema']);
        $rolEmpleado = Rol::create(['nombre' => 'Empleado', 'descripcion' => 'Personal de recepción o producción']);

        User::create([
            'name' => 'Administrador',
            'email' => 'admin@lavanderia.com',
            'password' => Hash::make('admin123'),
            'rol_id' => $rolAdmin->id,
        ]);

        User::create([
            'name' => 'Recepcionista 1',
            'email' => 'recepcion@lavanderia.com',
            'password' => Hash::make('recepcion123'),
            'rol_id' => $rolEmpleado->id,
        ]);

        Prenda::create(['nombre' => 'Camisa', 'tipo' => 'Normal', 'precio' => 12500]);
        Prenda::create(['nombre' => 'Pantalón', 'tipo' => 'Normal', 'precio' => 15000]);
        Prenda::create(['nombre' => 'Abrigo', 'tipo' => 'Lavado Seco', 'precio' => 45000]);
    }
}
