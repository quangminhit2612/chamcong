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
        Schema::create('ot_requests', function (Blueprint $table) {
            $table->id();
            $table->date('ot_date');
            $table->time('ot_start_time');
            $table->time('ot_end_time');
            $table->decimal('ot_hours', 5, 2);
            $table->text('ot_reason');
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ot_requests');
    }
};
