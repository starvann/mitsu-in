<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Expression;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 256);
            $table->string('email')->unique();
            // $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 256);
            $table->string('no_hp', 16);
            $table->string('gambar_profil', 256)->default('default.webp');
            $table->string('kode_ref_saya', 8)->nullable();
            $table->string('kode_ref', 8)->nullable();
            $table->date('tanggal_lahir');
            $table->boolean('tanggal_lahir_estimasi')->default(false);
            $table->enum('role', ['admn', 'stdn', 'refl'])->default('stdn');
            $table->enum('gender', ['laki-laki', 'perempuan'])->default('laki-laki');
            $table->enum('status', ['pending', 'accepted'])->default('pending');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
