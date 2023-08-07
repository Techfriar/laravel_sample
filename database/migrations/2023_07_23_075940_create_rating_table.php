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
        Schema::create('votings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('voter_id')->comment("Voter id");
            $table->unsignedBigInteger('candidate_id')->comment("Candidate id");
            $table->integer("technical_skill")->comment("Rating for technical skill");
            $table->integer("attitude")->comment("Rating for attitude");
            $table->integer("para_3")->comment("Rating for para_3");
            $table->integer("total")->comment("Total ratings");
            $table->decimal('cumulative_rating', 3, 2)->comment('Cumulative rating in 10');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('voter_id')->references('id')->on('users');
            $table->foreign('candidate_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votings');
    }
};
