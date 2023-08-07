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
        Schema::table('votings', function (Blueprint $table) {
            $table->renameColumn('para_3', 'problem_solving_skill');
            $table->integer("para_3")->comment("Rating for problem solving skill")->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('votings', function (Blueprint $table) {
            $table->renameColumn('problem_solving_skill','para_3');
            $table->integer("problem_solving_skill")->comment("Rating for problem solving skill")->change();
        });
    }
};
