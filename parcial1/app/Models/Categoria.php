<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';
    protected $primaryKey = 'idcategorias';
    public $timestamps = true;
    
    protected $fillable = [
        'nombre'
    ];
    
    public function productos()
    {
        return $this->hasMany(Product::class, 'categoria_id', 'idcategorias');
    }
}
