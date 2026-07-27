<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ServiceFifDetails;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_fif_details', function (Blueprint $table) {
            $table->longText('definition_description')->nullable()->after('definition_image');
        });

        // Carry existing Definition/Concept cards into the new single rich-text field
        foreach (ServiceFifDetails::all() as $row) {
            if (!empty($row->definition_description)) {
                continue;
            }
            $cards = $row->definition_cards ?? [];
            if (empty($cards)) {
                continue;
            }
            $html = '';
            foreach ($cards as $card) {
                $heading = trim($card['heading'] ?? '');
                $content = trim($card['content'] ?? '');
                if ($heading !== '') {
                    $html .= '<h3>' . $heading . '</h3>';
                }
                foreach (preg_split('/\n\n+/', $content) as $para) {
                    $para = trim($para);
                    if ($para !== '') {
                        $html .= '<p>' . nl2br($para) . '</p>';
                    }
                }
            }
            if ($html !== '') {
                $row->definition_description = $html;
                $row->save();
            }
        }
    }

    public function down(): void
    {
        Schema::table('service_fif_details', function (Blueprint $table) {
            $table->dropColumn('definition_description');
        });
    }
};
