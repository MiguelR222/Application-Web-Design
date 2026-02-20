<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AddController extends Controller
{
    public function index()
    {
        return view('add');
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'number1' => 'required|numeric',
            'number2' => 'required|numeric',
        ]);

        $number1 = $request->input('number1');
        $number2 = $request->input('number2');
        $result = $number1 + $number2;

        return view('add', [
            'number1' => $number1,
            'number2' => $number2,
            'result' => $result
        ]);
    }
}
