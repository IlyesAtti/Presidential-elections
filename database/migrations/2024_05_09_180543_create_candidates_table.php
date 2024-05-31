<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id()->uniqid;
            $table->unsignedBigInteger('userId');
            $table->string('userName');
            $table->integer('roundIndex')->default(1);
            $table->integer('votes')->default(0);
            $table->boolean('isVoted')->default(false);
            $table->integer('votedFor')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};
