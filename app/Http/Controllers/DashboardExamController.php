<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class DashboardExamController extends Controller
{
    // exams
  public function manage_exams() {
    Gate::authorize('admin');
    return view('users.dashboard.exams.manage', ['exams' => Exam::all()]);
  }
  public function exam_result(Exam $exam) {
    Gate::authorize('admin');
    return view('users.dashboard.exams.result', ['exam' => $exam, 'results' => $exam->examResults()->with('user')->get()]);
  }
  public function get_exam_results(Request $req, Exam $exam) {
    Gate::authorize('admin');
    $users = [];
    $datas = [];
    if($req->query('q')) {
      $keyword = $req->query('q');
      if(!is_string($keyword)) {
        return response()->json([], 400);
      }
      if(strlen($keyword) < 3) {
        return response()->json([], 400);
      }
        $users = User::where('nama', 'like', '%'.$keyword.'%')->orWhere('email', 'like', '%'.$keyword.'%')->pluck('id');
        $datas = $exam->examResults()->whereIn('user_id', $users);
      } else {
        $datas = $exam->examResults();
      }
      $datas = $datas->with('user:id,nama,email')->get();
      $datas = $datas->map(function($item, $key) {
        return [
          'id' => $item->id,
          'nama' => $item->user->nama,
          'email' => $item->user->email,
          'nilai' => $item->nilai,
          'total_benar' => $item->total_benar,
          'total_salah' => $item->total_salah
        ];
      });
      return response()->json($datas);
  }
  public function delete_exam_result(ExamResult $exam_res) {
    Gate::authorize('admin');
    $exam_res->delete();
    return redirect('/dashboard/exam-result/'.$exam_res->exam_id)->with('success', 'Hasil ujian telah dihapus.');
  }
  public function delete_all_exam_result(Exam $exam) {
    Gate::authorize('admin');
    $exam->examResults()->delete();
    return redirect('/dashboard/exam-result/'.$exam->id)->with('success', 'Semua hasil ujian telah dihapus.');
  }
  public function create_exam() {
    Gate::authorize('admin');
    return view('users.dashboard.exams.create');
  }
  public function store_exam(Request $req) {
    Gate::authorize('admin');
    //dd($req->input());
    $data = $req->validate([
      'judul' => 'required|string|unique:exams,judul',
      'deskripsi' => 'required|string',
      'soal' => 'required|array|list',
      'soal.*.jwbn_yg_benar' => 'required|numeric|max_digits:2',
      'soal.*.soal' => 'required|string',
      'soal.*.jawaban' => 'required|array|list',
      'soal.*.jawaban.*' => 'required|string',
      'deadline' => 'nullable|datetime',
      'siap_rilis' => 'nullable',
      'acak_soal' => 'nullable',
    ]);
    if(isset($data['siap_rilis'])) $data['siap_rilis'] = true;
    else $data['siap_rilis'] = false;
    if(isset($data['acak_soal'])) $data['acak_soal'] = true;
    else $data['acak_soal'] = false;
    $soals = $data['soal'];
    unset($data['soal']);
    $data['user_id'] = Auth::user()->id;
    $exam = Exam::create($data);
    $exam->questions()->createMany($soals);
    return redirect('/dashboard/manage-exam')->with('success', 'Ujian berhasil dibuat!');
  }
  public function edit_exam(Exam $exam) {
    Gate::authorize('admin');
    return view('users.dashboard.exams.edit', ['exam' => $exam, 'questions' => $exam->questions]);
  }
  public function update_exam(Request $req, Exam $exam) {
    Gate::authorize('admin');
    $data = $req->validate([
      'judul' => ['required', 'string', Rule::unique('exams', 'judul')->ignore($exam->id)],
      'deskripsi' => 'required|string',
      'soal' => 'required|array|list',
      'soal.*.jwbn_yg_benar' => 'required|numeric|max_digits:2',
      'soal.*.soal' => 'required|string',
      'soal.*.jawaban' => 'required|array|list',
      'soal.*.jawaban.*' => 'required|string',
      'deadline' => 'nullable|datetime',
      'siap_rilis' => 'nullable',
      'acak_soal' => 'nullable',
    ]);
    if(isset($data['siap_rilis'])) $data['siap_rilis'] = true;
    else $data['siap_rilis'] = false;
    if(isset($data['acak_soal'])) $data['acak_soal'] = true;
    else $data['acak_soal'] = false;
    $soals = $data['soal'];
    unset($data['soal']);
    $exam->update($data);
    foreach($exam->questions as $i => $question) {
      if(!isset($soals[$i])) {
        $question->delete();
        continue;
      }
      $question->soal = $soals[$i]['soal'];
      $question->jawaban = $soals[$i]['jawaban'];
      $question->jwbn_yg_benar = $soals[$i]['jwbn_yg_benar'];
      $question->save();
    }
    if(count($soals) > $exam->questions->count()) {
      for($i = $exam->questions->count(); $i < count($soals); $i++) {
        Question::create([
          'exam_id' => $exam->id,
          'soal' => $soals[$i]['soal'],
          'jawaban' => $soals[$i]['jawaban'],
          'jwbn_yg_benar' => $soals[$i]['jwbn_yg_benar']
        ]);
      }
    }
    return redirect('/dashboard/manage-exam')->with('success', 'Ujian berhasil diupdate!');
  }
  public function delete_exam(Exam $exam) {
    Gate::authorize('admin');
    $exam->delete();
    return redirect('/dashboard/manage-exam')->with('success', 'Ujian telah dihapus.');
  }
}
