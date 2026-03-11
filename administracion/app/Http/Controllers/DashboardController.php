<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
public function index() 
{ 
return "Bienvenido a dashboard general.";  // O solo texto: return 'Bienvenido al panel de Admin.'; 
} 
}