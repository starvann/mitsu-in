<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 256)->default("Tebak?");
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 1024);
            $table->string('hp_number', 16)->default('080000000000');
            $table->string('my_ref_code', 16)->nullable();
            $table->string('ref_code', 16)->nullable();
            $table->unsignedTinyInteger('age')->default(20);
            $table->enum('role', ['admn', 'stdn', 'refl'])->default('stdn');
            $table->enum('gender', ['laki-laki', 'perempuan'])->default('laki-laki');
            $table->enum('stat', ['pending', 'accepted'])->default('pending');
            $table->unsignedSmallInteger('body_h')->default(180);
            $table->unsignedSmallInteger('body_w')->default(60);
            $table->boolean('have_married')->default(false);
            $table->string('blood_t', 2)->default('A');
            $table->string('religion', 32)->default('none');
            $table->boolean('have_came_to_jp')->default(false);
            $table->boolean('have_passport')->default(false);
            $table->enum('main_hand', ['kanan', 'kiri'])->default('kanan');
            $table->string('address', 512)->default('Bumi');
            /* education structure: [{
            'year': 2000, 
            'school_name': 'Idk Bruv', 
            'major': 'Software Engineering'
            }, ...]*/
            $table->json('education')->default('[{"year":2000,"school_name":"Otodidak,"major":"kehidupan"}]');
            $table->json('experience')->nullable(); # ['exp1', 'exp2', 'etc...']
            /* family structure: [{
            'relation': 'Father', 
            'name': 'Lloyd', 
            'age': 40,
            'job': 'Artist',
            'salary': 'Rp10.000.000 per month'
            }, ...]*/
            $table->json('family_structure')->default('[{"relation":"Teman","name":"Sisi Lain diriku","age":20,"job":"ga ada","salary":"ga ada"}]');
            $table->string('purpose_to_jp', 256)->default("Menjadi manusia biasa");
            $table->string('purpose_after_comeback', 256)->default("Menjadi manusia sedikit tak biasa");
            $table->string('strengths', 256)->default("Dapat menahan intrusive thought");
            $table->string('weaknesses', 256)->default("Memiliki intrusive thought yang berbahaya");
            $table->string('hobies', 256)->default("Menikmati Keindahan Alam");
            $table->boolean('has_jlpt_cert')->default(false);
            $table->boolean('has_sim_a')->default(false);
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
