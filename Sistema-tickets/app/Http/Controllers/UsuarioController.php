<?php 
namespace App\Http\Controllers; 
use App\Models\TicketAttachment;
use App\Models\Ticket; 
use App\Services\OpenAIAttachmentAnalyzer;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
class UsuarioController extends Controller 
{ 
private OpenAIAttachmentAnalyzer $openAIAttachmentAnalyzer;

public function __construct(OpenAIAttachmentAnalyzer $openAIAttachmentAnalyzer)
{
$this->openAIAttachmentAnalyzer = $openAIAttachmentAnalyzer;
}

public function dashboard() 
{ 
$misTickets = Ticket::where('cliente_email', auth()->user()->email) ->orderBy('fecha_reporte', 'desc') ->take(5)->get(); 
return view('usuario.dashboard', compact('misTickets')); 
} 
public function index() 
{ 
$tickets = Ticket::where('cliente_email', auth()->user()->email) ->orderBy('fecha_reporte', 'desc')->get(); 
return view('usuario.tickets.index', compact('tickets')); 
} 
public function create() 
{ 
return view('usuario.tickets.create'); 
} 
public function store(Request $request) 
{ 
$datos = $request->validate([ 
'descripcion_corta'    => 'required|max:255', 
'categoria'            => 
'required|in:software,hardware,comunicaciones,plataformas,email,otro', 
'nivel_urgencia'       => 'required|in:baja,media,alta,critica',
'descripcion_detallada'=> 'nullable', 
'departamento'         => 'required|max:100', 
'attachments.*'       => 'nullable|file|max:10240',
]); 
// Completa los datos automáticamente 
$datos['numero_reporte'] = 'TKT-' . date('Y') . '-' . 
str_pad(Ticket::count() + 1, 4, '0', STR_PAD_LEFT); 
$datos['cliente_nombre'] = auth()->user()->name; 
$datos['cliente_email']  = auth()->user()->email; 
$datos['fecha_reporte']  = now(); 
$datos['status']         = 'pendiente'; 
$ticket = Ticket::create($datos); 

$this->storeAttachmentsAndRunAi($ticket, $request);

return redirect()->route('usuario.tickets.show', $ticket) ->with('success', 'Ticket creado exitosamente.'); 
} 
public function show(Ticket $ticket) 
{ 
// El usuario solo puede ver SUS tickets 
if ($ticket->cliente_email !== auth()->user()->email) { 
abort(403, 'No tienes acceso a este ticket.'); 
} 

$ticket->load('attachments');

return view('usuario.tickets.show', compact('ticket')); 
} 

private function storeAttachmentsAndRunAi(Ticket $ticket, Request $request): void
{
if (!$request->hasFile('attachments')) {
return;
}

foreach ($request->file('attachments') as $file) {
$path = Storage::disk('public')->putFile('ticket-attachments', $file);

$attachment = $ticket->attachments()->create([
'original_name' => $file->getClientOriginalName(),
'file_path' => $path,
'mime_type' => $file->getMimeType(),
'size' => $file->getSize(),
'type' => str_starts_with($file->getMimeType(), 'image/') ? 'image' : 'document',
]);

$this->runAiAnalysisForAttachment($ticket, $attachment);
}

$this->updateTicketExecutiveSummary($ticket);
}

private function runAiAnalysisForAttachment(Ticket $ticket, TicketAttachment $attachment): void
{
try {
$analysis = $this->openAIAttachmentAnalyzer->analyzeAttachment($ticket, $attachment);

$attachment->update([
'ai_technical_description' => $analysis['ai_technical_description'],
'ai_ocr_text' => $analysis['ai_ocr_text'],
'ai_suggested_category' => $analysis['ai_suggested_category'],
'ai_possible_causes' => $analysis['ai_possible_causes'],
'ai_executive_summary' => $analysis['ai_executive_summary'],
'ai_status' => $analysis['ai_status'],
'ai_error' => $analysis['ai_error'],
]);
} catch (\Throwable $exception) {
$attachment->update([
'ai_status' => 'error',
'ai_error' => $exception->getMessage(),
]);

Log::warning('No se pudo analizar adjunto de usuario con OpenAI.', [
'ticket_id' => $ticket->id,
'attachment_id' => $attachment->id,
'error' => $exception->getMessage(),
]);
}
}

private function updateTicketExecutiveSummary(Ticket $ticket): void
{
try {
$summary = $this->openAIAttachmentAnalyzer->buildTicketExecutiveSummary($ticket->fresh('attachments'));

if ($summary !== null && $summary !== '') {
$ticket->update(['ai_executive_summary' => $summary]);
}
} catch (\Throwable $exception) {
Log::warning('No se pudo generar resumen ejecutivo IA para ticket de usuario.', [
'ticket_id' => $ticket->id,
'error' => $exception->getMessage(),
]);
}
}
} 