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
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('site_id')->nullable();
            $table->date('date');
            $table->timestamp('clock_in')->nullable();
            $table->timestamp('clock_out')->nullable();
            $table->timestamp('break_start')->nullable();
            $table->timestamp('break_end')->nullable();
            $table->decimal('total_hours', 5, 2)->nullable();
            $table->integer('break_duration')->nullable();   // minutes
            $table->integer('work_duration')->nullable();    // minutes
            $table->enum('status', ['present','absent','late','half_day','remote'])->nullable();
            $table->json('location_clock_in')->nullable();   // {lat, lng, acc}
            $table->json('location_clock_out')->nullable();
            $table->string('ip_address_clock_in', 45)->nullable();
            $table->string('ip_address_clock_out', 45)->nullable();
            $table->uuid('device_id_clock_in')->nullable();
            $table->uuid('device_id_clock_out')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_validated')->default(false);
            $table->uuid('validated_by')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id','date']);
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('site_id')->references('id')->on('sites')->nullOnDelete();
            $table->foreign('device_id_clock_in')->references('id')->on('devices')->nullOnDelete();
            $table->foreign('device_id_clock_out')->references('id')->on('devices')->nullOnDelete();
            $table->foreign('validated_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
