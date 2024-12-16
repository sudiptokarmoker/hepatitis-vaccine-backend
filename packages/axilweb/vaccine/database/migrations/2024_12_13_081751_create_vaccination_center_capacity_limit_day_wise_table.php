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
        Schema::create('vaccination_center_capacity_limit_day_wise', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('center_id');
            $table->date('date_of_vaccination');
            $table->integer('capacity_limit');
            $table->timestamps();

            $table->foreign('center_id')->references('id')->on('vaccination_center');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccination_center_capacity_limit_day_wise');
    }
};
