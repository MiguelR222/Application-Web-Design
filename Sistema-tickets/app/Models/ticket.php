<?php

namespace App\Models; 
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\Factories\HasFactory; 
class Ticket extends Model { 
use HasFactory; 
/** 
* Tabla asociada al modelo. 
* Laravel infiere 'tickets' automaticamente, pero es buena 
* practica ser explicito para evitar confusiones. 
*/ 
protected $table = 'tickets'; 
/** 
* Campos que PUEDEN ser asignados masivamente. 
* Es una medida de seguridad: solo los campos listados aqui 
* podran ser llenados con Ticket::create($request->all()). 
*/ 
protected $fillable = [ 
'numero_reporte', 
'cliente_nombre', 
'cliente_email', 
'departamento', 
'categoria', 
'nivel_urgencia', 
'descripcion_corta', 
'descripcion_detallada', 
'tecnico_asignado', 
'fecha_reporte', 
'fecha_promesa', 
'fecha_resolucion', 
'comentarios_tecnico', 
'status', 
]; 
/** 
* Casting automatico de tipos. 
* Laravel convertira estos campos al tipo PHP correcto 
* al leerlos de la base de datos. 
*/ 
protected $casts = [ 
'fecha_reporte'    => 'datetime', 
'fecha_promesa'    => 'datetime', 
'fecha_resolucion' => 'datetime', 
]; 
}