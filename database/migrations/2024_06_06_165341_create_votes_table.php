<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotesTable extends Migration {
    public function up() {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userId');
            $table->unsignedBigInteger('candidateId');
            $table->unsignedBigInteger('roundIndex');
            $table->timestamps();

            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('candidateId')->references('id')->on('candidates')->onDelete('cascade');
            $table->foreign('roundIndex')->references('id')->on('rounds')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('votes');
    }
}