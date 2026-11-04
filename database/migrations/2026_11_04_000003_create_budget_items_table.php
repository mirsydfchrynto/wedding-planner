<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budget_items', function (Blueprint $table): void {
            $table->id();
            $table->string('category', 40)->index();
            $table->string('title');
            $table->unsignedBigInteger('estimated_cost')->default(0);
            $table->unsignedBigInteger('actual_cost')->default(0);
            $table->unsignedBigInteger('paid_amount')->default(0);
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
            $table->date('due_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_items');
    }
};
