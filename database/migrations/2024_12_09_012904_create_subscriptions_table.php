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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('created_by');
            $table->string('updated_by');
            $table->foreignUuid('member_id')->constrained()->onDelete('cascade');
            $table->foreignId('membership_id')->constrained();
            $table->foreignUuid('gym_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
