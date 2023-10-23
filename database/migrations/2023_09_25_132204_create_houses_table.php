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
        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->nullable()->constrained()->nullOnDelete();
            $table->foreignId("address_id")->nullable()->constrained();
            $table->string("name");
            $table->string("type");
            $table->text("description");
            $table->float("night_price", 9, 2);
            $table->unsignedTinyInteger("total_bath");
            $table->unsignedTinyInteger("total_rooms");
            $table->unsignedTinyInteger("total_beds");
            $table->smallInteger("mq")->nullable();
            $table->boolean("is_published")->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('houses');
    }
};
