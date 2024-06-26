<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 20)->nullable();
            $table->string('mime', 40)->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->text('name')->nullable();
            $table->text('local')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
