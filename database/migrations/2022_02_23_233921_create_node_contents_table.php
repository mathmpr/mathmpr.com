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
        Schema::create('node_contents', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('node_id');
            $table->foreign('node_id')->references('id')->on('nodes');
            $table->integer('order');
            $table->text('content');
            $table->string('lang', 20);
            $table->string('type', 50);
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
        Schema::dropIfExists('nodes');
    }
};
