<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->boolean('is_external')->default(false);
            $table->boolean('is_default')->nullable()->default(null);

            $table->unique(['is_external', 'is_default']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropUnique(['is_external', 'is_default']);
            $table->dropColumn('is_external');
            $table->dropColumn('is_default');
            $table->dropConstrainedForeignId('entity_id');
        });
    }
};
