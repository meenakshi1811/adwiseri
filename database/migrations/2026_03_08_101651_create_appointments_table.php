<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('subscriber_id')->nullable();
            $table->unsignedBigInteger('user_id');

            $table->date('appointment_date');
            $table->time('appointment_time');

            $table->text('remarks')->nullable();

            $table->enum('send_via', ['email','sms','both'])->default('email');

            $table->string('calendly_link')->nullable();
            $table->string('calendly_event_uri')->nullable();

            $table->enum('status', [
                'pending',
                'accepted',
                'canceled',
                'completed'
            ])->default('pending');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}