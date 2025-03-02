<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chenkis', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('member_id')->constrained()->onDelete('cascade');
            $table->foreignUuid('gym_id')->constrained()->onDelete('cascade');
            $table->string('created_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chenkis');
    }
};
