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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string("name")->comment('Name of employee');
            $table->string("phone")->nullable()->comment('Phone number of employee')->unique();
            $table->string("email")->unique()->comment('Email of employee');
            $table->string("password")->comment('Password of employee');
            $table->string('image_name')->nullable()->comment('Image of employee');
            $table->string('image_path')->nullable()->comment('path of employee image');
            $table->boolean("is_admin")->comment("Is this employee an admin");
            $table->boolean("status")->comment("Status of employee");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
