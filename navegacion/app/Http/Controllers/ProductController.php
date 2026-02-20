<?php 
namespace App\Http\Controllers; 
use Illuminate\Http\Request; 
use Illuminate\View\View;   // Importante para tipar las vistas 
class ProductController extends Controller 
{ 
/** 
* Muestra la lista de productos (index.blade.php) 
     */ 
    public function index(): View 
    { 
        // Aquí irán tus productos (por ahora vacío, puedes agregar lógica después) 
        return view('products.index'); 
    } 
 
    /** 
     * Muestra el formulario para crear un producto (create.blade.php) 
     */ 
    public function create(): View 
    { 
        return view('products.create'); 
    } 
 
    /** 
     * Muestra el formulario para editar un producto (edit.blade.php) 
     * ← Este método y su ruta SE QUEDAN aunque no mostremos la vista 
     */ 
    public function edit($id): View 
    { 
        // Por ahora no necesitamos el producto real (puedes agregar después) 
        return view('products.edit'); 
    } 
 
    // Los otros métodos (store, show, update, destroy) los dejamos vacíos por ahora 
    public function store(Request $request) { } 
    public function show($id) { } 
    public function update(Request $request, $id) { } 
public function destroy($id) { } 
}