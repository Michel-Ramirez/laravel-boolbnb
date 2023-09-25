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
            $table->tinyInteger("total_bath");
            $table->tinyInteger("total_rooms");
            $table->tinyInteger("total_beds");
            $table->smallInteger("mq");
            $table->string("photo");
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
