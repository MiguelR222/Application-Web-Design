<?php

namespace App\Http\Controllers;

use App\Models\Character;

class SuperheroController extends Controller
{
    public function index() {
        $characters = Character::with('universe')->get();
        return view('superheroes.index', compact('characters'));
    }
}
