<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Exam;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->string('judul', 256)->unique();
            $table->string('deskripsi', 512);
            $table->datetime('deadline')->nullable();
            $table->boolean('siap_rilis')->default(false);
            $table->boolean('acak_soal')->default(false);
            $table->timestamps();
        });
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Exam::class);
            $table->text('soal');
            $table->json('jawaban');# ['ans1', 'ans2', ...]
            $table->tinyInteger('jwbn_yg_benar');
            $table->timestamps();
        });
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Exam::class);
            $table->unsignedSmallInteger('nilai');
            $table->unsignedSmallInteger('total_salah');
            $table->unsignedSmallInteger('total_benar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('exam_results');
    }
};
