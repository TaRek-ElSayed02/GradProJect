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
        Schema::disableForeignKeyConstraints();
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('NewPassword')->nullable();
            $table->string('Email')->nullable();
            $table->string('Password')->nullable();
            $table->foreignId('Alarm_id')->references('id')->on('alarms')->nullable();
            $table->foreignId('Notification_id')->references('id')->on('notifications')->nullable();
            $table->string('Password_Confirmation');
            $table->timestamps();
           // $table->foreignId('alarm_id')->constrained()->onDelete('cascade');
            //$table->foreignId('notification_id')->constrained()->onDelete('cascade');
        });
        // Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('doctors');
        // Schema::enableForeignKeyConstraints();
    }
};
