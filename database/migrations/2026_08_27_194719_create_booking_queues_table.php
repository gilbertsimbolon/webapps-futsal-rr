<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_queues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('field_id')->constrained('fields')->onDelete('cascade');
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('customer_name')->nullable();
            $table->integer('queue_order')->default(1);
            $table->enum('status', ['active_turn', 'waiting', 'completed', 'preempted'])->default('waiting');
            $table->dateTime('quantum_start')->nullable();
            $table->dateTime('quantum_end')->nullable();
            $table->timestamps();

            $table->index(['field_id', 'booking_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_queues');
    }
};
