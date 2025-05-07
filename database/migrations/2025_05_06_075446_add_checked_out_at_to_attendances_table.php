<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->timestamp('checked_out_at')->nullable();  // Thêm cột checked_out_at
        });
    }
    
    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('checked_out_at');  // Xóa cột checked_out_at khi rollback
        });
    }    
};
