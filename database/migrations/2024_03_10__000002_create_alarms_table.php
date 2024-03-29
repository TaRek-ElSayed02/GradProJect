<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlarmsTable extends Migration
{
    public function up()
    {
        Schema::create('alarms', function (Blueprint $table) {
            $table->id()->unsigned()->foreign('Doctor.Alarm_id');
            $table->string('Message');
            $table->timestamp('Time');
        });

        // // Create foreign key constraint separately
        // Schema::table('alarms', function (Blueprint $table) {
        //     $table->foreignId('doctor_id')
        //           ->constrained('doctors')
        //           ->onDelete('cascade');
        // });
    }

    public function down()
    {
        Schema::table('alarms', function (Blueprint $table) {
            $table->dropForeign(['doctor_id']);
        });

        Schema::dropIfExists('alarms');
    }
}
