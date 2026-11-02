<?php

use App\Models\User;
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
        Schema::create('user_details', function (Blueprint $table) {
            $table->foreignIdFor(User::class)->primary()->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('tinggi_badan');
            $table->unsignedSmallInteger('berat_badan');
            $table->string('status_pernikahan', 32);
            $table->string('golongan_darah', 2);
            $table->string('agama', 32);
            $table->boolean('pernah_ke_jepang');
            $table->boolean('punya_paspor');
            $table->enum('tangan_utama', ['kanan', 'kiri', 'keduanya']);
            $table->string('alamat', 512);
            /* struktur pendidikan: [{
            'tahun': 2000, 
            'nama_sekolah': 'SMK Negeri 0 Batang', 
            'jurusan': 'Rekayasa Perangkat Lunak'
            }, ...]*/
            $table->json('pendidikan');
            // struktur pengalaman: ['Bekerja di Bengkel Yamaha selama 2 tahun', ...]
            $table->json('pengalaman');
            /* struktur keluarga: [{
            'relasi': 'Ayah', 
            'nama': 'Lloyd', 
            'umur': 40,
            'pekerjaan': 'Artist',
            'gaji': 'Rp10.000.000 per bulan'
            }, ...]*/
            $table->json('struktur_keluarga');
            $table->string('tujuan_ke_jepang', 256);
            $table->string('tujuan_stlh_kembali', 256);
            $table->string('kelebihan', 256);
            $table->string('kekurangan', 256);
            $table->string('hobi', 256);
            $table->string('sertif_jlpt', 32)->nullable();
            $table->boolean('punya_sim_a');
            $table->string('sertif_lain', 256)->nullable();
            /* struktur relasi di jepang: {
            'nama': 'Izumi Fujimiya', 
            'relasi': 'Friend', 
            'pekerjaan': 'Artist',
            'umur': 40,
            'alamat': 'idk bruv'
            }*/
            $table->json('relasi_di_jepang')->nullable();
            $table->string('catatan_extra', 512)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
