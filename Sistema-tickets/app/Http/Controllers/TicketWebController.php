<?php 
namespace App\Http\Controllers; 
use App\Models\Ticket; 
use Illuminate\Http\Request; 
class TicketWebController extends Controller 
{ 
// GET /tickets 
public function index() 
{ 
$tickets = Ticket::orderBy('fecha_reporte', 'desc')->get(); 
return view('tickets.index', compact('tickets')); 
} 
// GET /tickets/create 
public function create() 
{ 
return view('tickets.create'); 
} 
// POST /tickets 
public function store(Request $request) 
{ 
Ticket::create($request->all()); 
return redirect()->route('tickets.index') ->with('success', 'Ticket creado exitosamente.'); 
} 
// GET /tickets/{ticket} 
public function show(Ticket $ticket) 
{ 
return view('tickets.show', compact('ticket')); 
} 
// GET /tickets/{ticket}/edit 
public function edit(Ticket $ticket) 
{ 
return view('tickets.edit', compact('ticket')); 
} 
// PUT /tickets/{ticket} 
public function update(Request $request, Ticket $ticket) 
{ 
$ticket->update($request->all()); 
return redirect()->route('tickets.index') ->with('success', 'Ticket actualizado correctamente.'); 
} 
// DELETE /tickets/{ticket} 
public function destroy(Ticket $ticket) 
{ 
$ticket->delete(); 
return redirect()->route('tickets.index') ->with('success', 'Ticket eliminado.'); 
} 

// PATCH /tickets/{ticket}/close
public function close(Ticket $ticket)
{
if (!in_array($ticket->status, ['pendiente', 'en_curso'], true)) {
return redirect()->route('tickets.index')->with('error', 'Solo se pueden cerrar tickets con estado pendiente o en curso.');
}

$ticket->update([
'status' => 'finalizada',
'fecha_resolucion' => now(),
]);

return redirect()->route('tickets.index')->with('success', 'Ticket cerrado correctamente.');
}
} 