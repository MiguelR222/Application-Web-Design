<?php 
namespace Database\Seeders; 
 
use Illuminate\Database\Console\Seeds\WithoutModelEvents; 
use Illuminate\Database\Seeder; 
use App\Models\User; 
use Illuminate\Support\Facades\Hash; 
 
class UserSeeder extends Seeder 
{ 
    public function run(): void 
    { 
        User::create([ 
            'name' => 'Admin', 
            'email' => 'admin@empresa.com', 
            'password' => Hash::make('password'), 
            'role' => 'admin', 
        ]); 
 
        User::create([ 
            'name' => 'Gerente', 
            'email' => 'gerente@empresa.com', 
            'password' => Hash::make('password'), 
            'role' => 'gerente', 
        ]); 
 
        User::create([ 
            'name' => 'Empleado', 
            'email' => 'empleado@empresa.com', 
            'password' => Hash::make('password'), 
            'role' => 'empleado', 
]); 
} 
};