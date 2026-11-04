<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wedding_tasks', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category', 40)->index();
            $table->string('phase', 32)->index();
            $table->date('due_date')->index();
            $table->string('status', 24)->default('todo')->index();
            $table->string('priority', 20)->default('medium');
            $table->string('assigned_to', 64)->default('Pasangan');
            $table->boolean('is_milestone')->default(false);
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->nullOnDelete();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wedding_tasks');
    }
};
