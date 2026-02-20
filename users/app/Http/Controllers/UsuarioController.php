<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function create() {
    return view('registro');
}

public function store(Request $request) {
    $data = $request->validate([
        'name' => 'required|min:3',
        'email' => 'required|email|unique:usuarios',
        'password' => 'required|min:6'
    ]);

    \App\Models\Usuario::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => bcrypt($data['password']),
    ]);

    return redirect('/usuarios')->with('success', 'Usuario creado');
}

public function index() {
    $usuarios = \App\Models\Usuario::all();
    return view('lista', compact('usuarios'));
}

}