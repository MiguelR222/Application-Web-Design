<?php 
namespace App\Http\Controllers; 
use App\Models\TicketAttachment;
use App\Models\Ticket; 
use App\Services\OpenAIAttachmentAnalyzer;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
class TicketWebController extends Controller 
{ 
private OpenAIAttachmentAnalyzer $openAIAttachmentAnalyzer;

public function __construct(OpenAIAttachmentAnalyzer $openAIAttachmentAnalyzer)
{
$this->openAIAttachmentAnalyzer = $openAIAttachmentAnalyzer;
}

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
$request->validate([
'attachments.*' => 'nullable|file|max:10240',
]);

$ticket = Ticket::create($request->except('attachments'));

$this->storeAttachmentsAndRunAi($ticket, $request);

return redirect()->route('admin.tickets.show', $ticket)->with('success', 'Ticket creado con adjuntos'); 
} 
// GET /tickets/{ticket} 
public function show(Ticket $ticket) 
{ 
$ticket->load('attachments');

return view('tickets.show')->with('ticket', $ticket); 
} 
// GET /tickets/{ticket}/edit 
public function edit(Ticket $ticket) 
{ 
return view('tickets.edit', compact('ticket')); 
} 
// PUT /tickets/{ticket} 
public function update(Request $request, Ticket $ticket) 
{ 
$request->validate([
'attachments.*' => 'nullable|file|max:10240',
]);

$ticket->update($request->except('attachments'));

$this->storeAttachmentsAndRunAi($ticket, $request);

return redirect()->route('admin.tickets.show', $ticket)->with('success', 'Ticket actualizado con adjuntos.'); 
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

Log::warning('No se pudo analizar adjunto con OpenAI.', [
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
Log::warning('No se pudo generar resumen ejecutivo del ticket con OpenAI.', [
'ticket_id' => $ticket->id,
'error' => $exception->getMessage(),
]);
}
}
} 