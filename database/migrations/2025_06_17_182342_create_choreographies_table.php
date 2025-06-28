<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('choreographies', function (Blueprint $table) {
            $table->id();
            $table->string('style');          // e.g. Kpop, Jazzfunk, etc.
            $table->string('difficulty');     // e.g. Beginner, Intermediate
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('choreographies');
    }
};
