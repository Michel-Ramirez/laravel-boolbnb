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
        Schema::create('house_sponsor', function (Blueprint $table) {
            $table->id();
            $table->foreignId("house_id")->constrained()->onDelete("cascade");
            $table->foreignId("sponsor_id")->constrained()->onDelete("cascade");
            $table->dateTime("sponsor_start");
            $table->dateTime("sponsor_end");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('house_sponsor');
    }
};
