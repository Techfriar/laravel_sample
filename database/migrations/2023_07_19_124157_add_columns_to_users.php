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
        Schema::table('users', function (Blueprint $table) {
            $table->string("name")->comment('Name of user')->change();
            $table->string("phone")->nullable()->comment('Phone number of user')->unique()->after('name');
            $table->string("email")->comment('Email id of user')->change();
            $table->string("email_verified_at")->nullable()->comment('Email verified time')->change();
            $table->string("password")->comment('Password of user')->change();
            $table->string('image_name')->nullable()->comment('Image of user')->after('password');
            $table->string('image_path')->nullable()->comment('Path of user image')->after('image_name');
            $table->boolean("is_admin")->comment("Is this user an admin")->after('image_path');
            $table->boolean("status")->comment("Status of user")->after('is_admin');
            $table->softDeletes()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->string('email')->comment('Email of employee')->change();
            $table->timestamp('email_verified_at')->nullable()->comment('Email verified at')->change();
            $table->string('password')->comment('Password of user')->change();
            $table->dropColumn('image_name');
            $table->dropColumn('image_path');
            $table->dropColumn('is_admin');
            $table->dropColumn('status');
        });
    }
};
