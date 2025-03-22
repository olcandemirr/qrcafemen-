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
    public function up()
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('table_id')->constrained()->onDelete('cascade');
            $table->string('player1_name');
            $table->string('player2_name')->nullable();
            $table->string('player3_name')->nullable();
            $table->string('player4_name')->nullable();
            $table->integer('player1_score')->default(0);
            $table->integer('player2_score')->default(0);
            $table->integer('player3_score')->default(0);
            $table->integer('player4_score')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scores');
    }
};
