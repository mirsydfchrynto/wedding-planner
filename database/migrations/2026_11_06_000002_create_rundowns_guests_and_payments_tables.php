<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wedding_rundowns', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('wedding_profile_id')->constrained()->cascadeOnDelete();
            $table->string('start_time', 10);
            $table->string('end_time', 10);
            $table->string('session_name', 80);
            $table->string('activity', 200);
            $table->string('location', 160)->nullable();
            $table->string('pic_name', 120)->nullable();
            $table->string('pic_phone', 40)->nullable();
            $table->text('notes')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('vendor_payments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('wedding_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->string('payment_stage', 60); // DP, Termin 1, Pelunasan
            $table->unsignedBigInteger('amount');
            $table->date('paid_date');
            $table->string('receipt_ref', 100)->nullable();
            $table->string('notes', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_payments');
        Schema::dropIfExists('wedding_rundowns');
    }
};
