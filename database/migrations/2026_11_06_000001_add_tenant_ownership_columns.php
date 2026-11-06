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
            $table->foreignId('user_id')->nullable()->unique()->constrained()->cascadeOnDelete();
        });

        foreach (['vendors', 'budget_items', 'wedding_tasks'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->foreignId('wedding_profile_id')->nullable()->constrained()->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        foreach (['vendors', 'budget_items', 'wedding_tasks'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table): void {
                $table->dropConstrainedForeignId('wedding_profile_id');
            });
        }

        Schema::table('wedding_profiles', function (Blueprint $table): void {
            $table->dropConstrainedForeignId('user_id');
        });
    }
};
