<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ticket_attachments', function (Blueprint $table) {
            $table->longText('ai_technical_description')->nullable()->after('type');
            $table->longText('ai_ocr_text')->nullable()->after('ai_technical_description');
            $table->string('ai_suggested_category')->nullable()->after('ai_ocr_text');
            $table->json('ai_possible_causes')->nullable()->after('ai_suggested_category');
            $table->longText('ai_executive_summary')->nullable()->after('ai_possible_causes');
            $table->string('ai_status')->nullable()->after('ai_executive_summary');
            $table->text('ai_error')->nullable()->after('ai_status');
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->longText('ai_executive_summary')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('ticket_attachments', function (Blueprint $table) {
            $table->dropColumn([
                'ai_technical_description',
                'ai_ocr_text',
                'ai_suggested_category',
                'ai_possible_causes',
                'ai_executive_summary',
                'ai_status',
                'ai_error',
            ]);
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('ai_executive_summary');
        });
    }
};
