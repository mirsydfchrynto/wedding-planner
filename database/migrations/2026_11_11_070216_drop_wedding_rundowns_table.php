<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('wedding_rundowns');
    }

    public function down(): void
    {
        // No recreation needed; this migration only drops the table.
    }
};
