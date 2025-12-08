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
            $table->string('nama', 256)->default("Tebak?");
            $table->string('email')->unique();
            // $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 1024);
            $table->string('no_hp', 16)->default('080000000000');
            $table->string('gmb_profil', 256)->default('assets/profiles/default.webp');
            $table->string('kode_ref_saya', 8)->nullable();
            $table->string('kode_ref', 8)->nullable();
            $table->unsignedTinyInteger('umur')->default(20);
            $table->enum('role', ['admn', 'stdn', 'refl'])->default('stdn');
            $table->enum('gender', ['laki-laki', 'perempuan'])->default('laki-laki');
            $table->enum('stat', ['pending', 'accepted'])->default('pending');
            $table->unsignedSmallInteger('tinggi_badan')->default(180);
            $table->unsignedSmallInteger('berat_badan')->default(60);
            $table->boolean('pernah_menikah')->default(false);
            $table->string('gol_darah', 2)->default('A');
            $table->string('agama', 32)->default('none');
            $table->boolean('pernah_ke_jepang')->default(false);
            $table->boolean('punya_paspor')->default(false);
            $table->enum('tangan_utama', ['kanan', 'kiri'])->default('kanan');
            $table->string('alamat', 512)->default('Bumi');
            /* education structure: [{
            'tahun': 2000, 
            'nam_sekolah': 'Idk Bruv', 
            'jurusan': 'Software Engineering'
            }, ...]*/
            $table->json('pendidikan')->default('[{"tahun":2000,"nama_sekolah":"Otodidak","jurusan":"kehidupan"}]');
            $table->json('pengalaman')->default('[]'); # ['exp1', 'exp2', 'etc...']
            /* family structure: [{
            'relasi': 'Father', 
            'nama': 'Lloyd', 
            'umur': 40,
            'pekerjaan': 'Artist',
            'gaji': 'Rp10.000.000 per month'
            }, ...]*/
            $table->json('struktur_keluarga')->default('[{"relasi":"Teman","nama":"Sisi Lain diriku","umur":20,"pekerjaan":"ga ada","gaji":"ga ada"}]');
            $table->string('tujuan_ke_jepang', 256)->default("Menjadi manusia yang lebih baik");
            $table->string('tujuan_stlh_kembali', 256)->default("Menjadi manusia yang lebih baik");
            $table->string('kelebihan', 256)->default("Dapat menahan intrusive thought");
            $table->string('kekurangan', 256)->default("Memiliki intrusive thought yang berbahaya");
            $table->string('hobi', 256)->default("Menikmati Keindahan Alam");
            $table->boolean('punya_sertif_jlpt')->default(false);
            $table->boolean('punya_sim_a')->default(false);
            $table->string('sertif_lain', 256)->nullable();
            /* japan relation structure: {
            'nama': 'Izumi Fujimiya', 
            'relasi': 'Friend', 
            'pekerjaan': 'Artist',
            'umur': 40,
            'alamat': 'idk bruv'
            }*/
            $table->json('relasi_di_jepang')->nullable();
            $table->string('catatan_xtra', 512)->nullable();
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
