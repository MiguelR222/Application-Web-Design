<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketAttachment extends Model
{
    protected $fillable = [
        'ticket_id',
        'original_name',
        'file_path',
        'mime_type',
        'size',
        'type',
        'ai_technical_description',
        'ai_ocr_text',
        'ai_suggested_category',
        'ai_possible_causes',
        'ai_executive_summary',
        'ai_status',
        'ai_error',
    ];

    protected $casts = [
        'ai_possible_causes' => 'array',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
