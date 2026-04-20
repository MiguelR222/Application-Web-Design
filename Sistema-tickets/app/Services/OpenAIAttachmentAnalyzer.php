<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\TicketAttachment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class OpenAIAttachmentAnalyzer
{
    public function analyzeAttachment(Ticket $ticket, TicketAttachment $attachment): array
    {
        $apiKey = trim((string) config('services.openai.api_key'));

        if ($apiKey === '') {
            throw new RuntimeException('OPENAI_API_KEY no esta configurada.');
        }

        $disk = Storage::disk('public');

        if (!$disk->exists($attachment->file_path)) {
            throw new RuntimeException('No se encontro el archivo del adjunto para analisis IA.');
        }

        $binary = $disk->get($attachment->file_path);
        $mimeType = $attachment->mime_type ?: 'application/octet-stream';
        $base64 = base64_encode($binary);

        $fileInput = str_starts_with($mimeType, 'image/')
            ? [
                'type' => 'input_image',
                'image_url' => 'data:' . $mimeType . ';base64,' . $base64,
            ]
            : [
                'type' => 'input_file',
                'filename' => $attachment->original_name,
                'file_data' => 'data:' . $mimeType . ';base64,' . $base64,
            ];

        $request = Http::withToken($apiKey)
            ->acceptJson()
            ->connectTimeout((int) config('services.openai.connect_timeout', 5))
            ->timeout((int) config('services.openai.http_timeout', 20));

        $retries = (int) config('services.openai.http_retries', 0);

        if ($retries > 0) {
            $request = $request->retry($retries, 500);
        }

        $response = $request->post('https://api.openai.com/v1/responses', [
                'model' => config('services.openai.vision_model', 'gpt-4.1-mini'),
                'input' => [[
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'input_text',
                            'text' => $this->analysisPrompt($ticket, $attachment),
                        ],
                        $fileInput,
                    ],
                ]],
                'temperature' => 0.1,
            ]);

        if (!$response->successful()) {
            throw new RuntimeException('OpenAI respondio con error HTTP ' . $response->status());
        }

        $payload = $response->json();
        $outputText = $this->extractOutputText($payload);
        $decoded = $this->decodeJsonOutput($outputText);

        return [
            'ai_technical_description' => $decoded['technical_description'] ?? null,
            'ai_ocr_text' => $decoded['ocr_text'] ?? null,
            'ai_suggested_category' => $decoded['suggested_category'] ?? null,
            'ai_possible_causes' => $decoded['possible_causes'] ?? [],
            'ai_executive_summary' => $decoded['executive_summary'] ?? null,
            'ai_status' => 'ok',
            'ai_error' => null,
            'raw' => $payload,
        ];
    }

    public function buildTicketExecutiveSummary(Ticket $ticket): ?string
    {
        $apiKey = trim((string) config('services.openai.api_key'));

        if ($apiKey === '') {
            return null;
        }

        $attachments = $ticket->attachments;

        if ($attachments->isEmpty()) {
            return null;
        }

        $attachmentContext = $attachments->map(function (TicketAttachment $attachment, int $index) {
            $causes = $attachment->ai_possible_causes;

            if (is_array($causes)) {
                $causes = implode(', ', $causes);
            }

            return [
                'index' => $index + 1,
                'name' => $attachment->original_name,
                'mime' => $attachment->mime_type,
                'technical_description' => $attachment->ai_technical_description,
                'ocr_text' => $attachment->ai_ocr_text,
                'possible_causes' => $causes,
                'suggested_category' => $attachment->ai_suggested_category,
            ];
        })->toArray();

        $request = Http::withToken($apiKey)
            ->acceptJson()
            ->connectTimeout((int) config('services.openai.connect_timeout', 5))
            ->timeout((int) config('services.openai.http_timeout', 20));

        $retries = (int) config('services.openai.http_retries', 0);

        if ($retries > 0) {
            $request = $request->retry($retries, 500);
        }

        $response = $request->post('https://api.openai.com/v1/responses', [
                'model' => config('services.openai.summary_model', config('services.openai.vision_model', 'gpt-4.1-mini')),
                'input' => [[
                    'role' => 'user',
                    'content' => [[
                        'type' => 'input_text',
                        'text' => $this->summaryPrompt($ticket, $attachmentContext),
                    ]],
                ]],
                'temperature' => 0.2,
            ]);

        if (!$response->successful()) {
            Log::warning('No se pudo generar resumen ejecutivo con OpenAI.', [
                'ticket_id' => $ticket->id,
                'status' => $response->status(),
            ]);

            return null;
        }

        $payload = $response->json();

        return trim((string) $this->extractOutputText($payload));
    }

    private function analysisPrompt(Ticket $ticket, TicketAttachment $attachment): string
    {
        return 'Eres un analista tecnico de mesa de ayuda. Analiza el archivo adjunto de un ticket y responde SOLO JSON valido con esta estructura exacta: ' .
            '{"technical_description":"", "ocr_text":"", "suggested_category":"software|hardware|comunicaciones|plataformas|email|otro", "possible_causes":["",""], "executive_summary":""}. ' .
            'Reglas: technical_description debe ser detallada y tecnica; ocr_text debe incluir texto extraido literal (si no hay texto, usa cadena vacia); suggested_category debe elegir una de esas opciones; possible_causes debe tener de 1 a 3 causas probables; executive_summary debe ser breve para directivos. ' .
            'Contexto del ticket: numero_reporte=' . $ticket->numero_reporte . ', categoria_actual=' . $ticket->categoria . ', urgencia=' . $ticket->nivel_urgencia . ', descripcion_corta=' . $ticket->descripcion_corta . '. ' .
            'Nombre del archivo: ' . $attachment->original_name . ', mime: ' . $attachment->mime_type . '.';
    }

    private function summaryPrompt(Ticket $ticket, array $attachmentContext): string
    {
        return "Genera un resumen ejecutivo del ticket para directivos en 5-8 lineas, incluyendo lo que dicen imagenes/documentos. " .
            "Incluye: problema principal, impacto, categoria sugerida y siguientes pasos recomendados. " .
            "Contexto del ticket: " . json_encode([
                'numero_reporte' => $ticket->numero_reporte,
                'cliente_nombre' => $ticket->cliente_nombre,
                'departamento' => $ticket->departamento,
                'categoria' => $ticket->categoria,
                'nivel_urgencia' => $ticket->nivel_urgencia,
                'descripcion_corta' => $ticket->descripcion_corta,
                'descripcion_detallada' => $ticket->descripcion_detallada,
            ], JSON_UNESCAPED_UNICODE) .
            ". Analisis de adjuntos: " . json_encode($attachmentContext, JSON_UNESCAPED_UNICODE);
    }

    private function extractOutputText(array $payload): string
    {
        if (!empty($payload['output_text']) && is_string($payload['output_text'])) {
            return $payload['output_text'];
        }

        if (!empty($payload['output']) && is_array($payload['output'])) {
            $parts = [];

            foreach ($payload['output'] as $outputItem) {
                if (!is_array($outputItem) || empty($outputItem['content']) || !is_array($outputItem['content'])) {
                    continue;
                }

                foreach ($outputItem['content'] as $contentItem) {
                    if (is_array($contentItem) && isset($contentItem['text']) && is_string($contentItem['text'])) {
                        $parts[] = $contentItem['text'];
                    }
                }
            }

            if (!empty($parts)) {
                return implode("\n", $parts);
            }
        }

        return '';
    }

    private function decodeJsonOutput(string $outputText): array
    {
        $clean = trim($outputText);

        if (str_starts_with($clean, '```')) {
            $clean = preg_replace('/^```(?:json)?/i', '', $clean) ?? $clean;
            $clean = preg_replace('/```$/', '', $clean) ?? $clean;
            $clean = trim($clean);
        }

        $decoded = json_decode($clean, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            throw new RuntimeException('No se pudo decodificar la respuesta JSON de OpenAI.');
        }

        return $decoded;
    }
}
