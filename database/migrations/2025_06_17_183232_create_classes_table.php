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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedBigInteger('instructor_id');
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('max_slot');
            $table->unsignedBigInteger('choreography_id');
            $table->string('location')->nullable();
            
            // ✅ Add status field
            $table->string('status')->default('Active'); 

            // ✅ Replace single documentation_url with multiple
            $table->string('doc_name1')->nullable();
            $table->string('doc_link1')->nullable();
            $table->string('doc_name2')->nullable();
            $table->string('doc_link2')->nullable();
            $table->string('doc_name3')->nullable();
            $table->string('doc_link3')->nullable();

            $table->timestamps();

            $table->foreign('instructor_id')->references('id')->on('users');
            $table->foreign('choreography_id')->references('id')->on('choreographies');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
