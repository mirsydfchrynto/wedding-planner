<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wedding_profiles', function (Blueprint $table): void {
            $table->id();
            $table->string('groom_name');
            $table->string('bride_name');
            $table->date('wedding_date');
            $table->string('wedding_time')->default('08:30 WIB');
            $table->string('venue_name');
            $table->string('city')->default('Jakarta');
            $table->unsignedBigInteger('total_budget')->default(350000000);
            $table->unsignedInteger('guest_target')->default(500);
            $table->string('theme_concept')->default('Vogue Bridal Editorial — Warm Alabaster & Champagne Bronze');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wedding_profiles');
    }
};
