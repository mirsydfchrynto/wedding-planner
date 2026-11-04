<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('category', 40)->index();
            $table->string('contact_person');
            $table->string('phone', 40);
            $table->string('email')->nullable();
            $table->string('instagram', 100)->nullable();
            $table->unsignedBigInteger('contract_amount')->default(0);
            $table->unsignedBigInteger('paid_amount')->default(0);
            $table->string('booking_status', 32)->default('researching')->index();
            $table->date('payment_due_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
