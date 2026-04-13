<?php 
namespace Database\Seeders; 
use Illuminate\Database\Seeder; 
use Illuminate\Support\Facades\Hash; 
use App\Models\User; 
class UsuariosRolesSeeder extends Seeder 
{ 
public function run(): void 
{ 
// ── Usuario ADMIN ────────────────────────── 
User::updateOrCreate([
'email'    => 'admin@tickets.com',
], [
'name'     => 'Administrador', 
'password' => Hash::make('password'), 
'rol'      => 'admin', 
]); 
// ── Usuario GERENTE ──────────────────────── 
User::updateOrCreate([
'email'    => 'gerente@tickets.com',
], [
'name'     => 'Gerente General', 
'password' => Hash::make('password'), 
'rol'      => 'gerente', 
]); 
// ── Usuario REGULAR ─────────────────────── 
User::updateOrCreate([
'email'    => 'usuario@tickets.com',
], [
'name'     => 'Juan Pérez', 
'password' => Hash::make('password'), 
'rol'      => 'usuario', 
]); 
} 
} 