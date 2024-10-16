<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id'); // Đổi thành unsignedInteger để khớp với cột id của users
            $table->unsignedInteger('motelroom_id');
            $table->string('stripe_payment_id');
            $table->string('status');
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        
            // Đặt khóa ngoại với bảng users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('motelroom_id')->references('id')->on('motelrooms')->onDelete('cascade');

        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}

