<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoundsTable extends Migration {
    public function up() {
        Schema::create('rounds', function (Blueprint $table) {
            $table->id();
            $table->integer('index')->unique()->default(1);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('rounds');
    }
}