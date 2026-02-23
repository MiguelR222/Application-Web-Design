<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Categoria;

class ProductController extends Controller
{
    public function index()
    {
        $categorias = Categoria::all();
        return view('products.index', compact('categorias'));
    }

    public function show()
    {
        $productos = Product::with('categoria')->get();
        return view('products.show', compact('productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string',
            'precio' => 'required|numeric',
            'categoria_id' => 'required|integer|exists:categorias,idcategorias'
        ]);

        Product::create([
            'nombre' => $request->nombre,
            'precio' => $request->precio,
            'categoria_id' => $request->categoria_id
        ]);

        return redirect()->route('products.show')->with('success', 'Producto creado exitosamente');
    }
}
