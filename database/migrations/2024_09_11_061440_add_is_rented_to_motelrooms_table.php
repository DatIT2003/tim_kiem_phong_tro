<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsRentedToMotelroomsTable extends Migration
{
    public function up()
    {
        Schema::table('motelrooms', function (Blueprint $table) {
            $table->boolean('is_rented')->default(0); // 0 = chưa thuê, 1 = đã thuê
        });
    }

    public function down()
    {
        Schema::table('motelrooms', function (Blueprint $table) {
            $table->dropColumn('is_rented');
        });
    }
}
