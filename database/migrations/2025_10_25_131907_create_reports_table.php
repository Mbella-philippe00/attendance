<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('type', ['attendance_summary','lateness','overtime','absences','custom']);
            $table->json('params')->nullable();
            $table->uuid('generated_by')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->text('file_url')->nullable();
            $table->enum('status', ['queued','running','done','failed'])->default('queued');
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->foreign('generated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
