<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::create('contributors', function (Blueprint $table) {
            $table->id();
            $table->enum('civility', ['mr','mrs'])->nullable();
            $table->string('lastname');
            $table->string('firstname');
            $table->string('trigram', 3)->nullable();
            $table->string('avatar')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contributors');
    }
};
