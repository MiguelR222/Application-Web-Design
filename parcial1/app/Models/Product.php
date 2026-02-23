<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'productos';
    protected $primaryKey = 'idproductos';
    public $timestamps = true;
    
    protected $fillable = [
        'nombre',
        'precio',
        'categoria_id'
    ];
    
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id', 'idcategorias');
    }
}
