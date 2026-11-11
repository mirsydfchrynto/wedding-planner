<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wedding_profiles', function (Blueprint $table): void {
            $table->string('event_type')->default('akad_resepsi')->after('theme_concept');
            $table->string('helper_mode')->default('self')->after('event_type');
            $table->string('venue_type')->default('Gedung / Ballroom')->after('helper_mode');
        });
    }

    public function down(): void
    {
        Schema::table('wedding_profiles', function (Blueprint $table): void {
            $table->dropColumn(['event_type', 'helper_mode', 'venue_type']);
        });
    }
};
