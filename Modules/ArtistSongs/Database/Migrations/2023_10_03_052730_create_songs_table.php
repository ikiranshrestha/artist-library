<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('artist_id');

            $table->foreign('artist_id')
                ->references('id')
                ->on('artists')
                ->onDelete('restrict');

            $table->string('album_name');
            $table->enum('genre', ['rnb', 'country', 'classic', 'rock', 'jazz']);
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
        Schema::dropIfExists('songs');
    }
};
