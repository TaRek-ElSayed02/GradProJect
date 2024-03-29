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
        Schema::create('sensor__data', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('Oxygen Rate');
            $table->smallInteger('Heart Rate');
            // $table->foreignId('oxygen_generator_id')->constrained()->onDelete('cascade');
        });
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('sensor__data');
        Schema::enableForeignKeyConstraints();
        
    }
};
