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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 256);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 1024);
            $table->string('hp_number', 16);
            $table->string('ref_code', 16);
            $table->unsignedTinyInteger('age');
            $table->enum('role', ['admn', 'user', 'rfrl']);
            $table->enum('gender', ['laki-laki', 'perempuan']);
            $table->enum('stat', ['terdaftar', 'daftar_ulang']);
            $table->unsignedSmallInteger('body_h');
            $table->unsignedSmallInteger('body_w');
            $table->boolean('have_married');
            $table->string('blood_t', 2);
            $table->string('religion', 32);
            $table->boolean('have_came_to_jp');
            $table->boolean('have_passport');
            $table->enum('main_hand', ['kanan', 'kiri']);
            $table->string('address', 512);
            /* education structure: [{
            'year': 2000, 
            'school_name': 'Idk Bruv', 
            'major': 'Software Engineering'
            }, ...]*/
            $table->json('education');
            $table->json('experience')->nullable(); # ['exp1', 'exp2', 'etc...']
            /* family structure: [{
            'relation': 'Father', 
            'name': 'Lloyd', 
            'age': 40,
            'job': 'Artist',
            'salary': 'Rp10.000.000 per month'
            }, ...]*/
            $table->json('family_structure');
            $table->string('purpose_to_jp', 256);
            $table->string('purpose_after_comeback', 256);
            $table->string('strengths', 256);
            $table->string('weaknesses', 256);
            $table->string('hobies', 256);
            $table->boolean('has_jlpt_cert');
            $table->boolean('has_sim_a');
            $table->string('other_cert', 256)->nullable();
            /* japan relation structure: {
            'name': 'Izumi Fujimiya', 
            'relation': 'Friend', 
            'job': 'Artist',
            'age': 40,
            'address': 'idk bruv'
            }*/
            $table->json('jp_relations')->nullable();
            $table->string('extra_notes', 512)->nullable();
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
