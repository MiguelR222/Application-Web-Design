<?php 
namespace App\Http\Controllers; 
use Illuminate\Http\Request; 
class AdminController extends Controller 
{ 
public function index() 
{ 
return "Bienvenido al panel de Admin.";  // O solo texto: return 'Bienvenido al panel de Admin.'; 
} 
}