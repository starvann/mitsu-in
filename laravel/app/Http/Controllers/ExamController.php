<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class ExamController extends Controller
{
  public function index(Exam $exam) {
    if(ExamResult::where('user_id', Auth::id())->where('exam_id', $exam->id)->exists()) {
      return view('exams.result', ['result' => $exam->examResults()->where('user_id', Auth::id())->first()]);
    }
    $data = ['exam' => $exam];
    $questions = $exam->acak_soal ? $exam->questions->shuffle()->toArray() : $exam->questions->toArray();
    $data['questions_count'] = count($questions);
    $data['question'] = $questions[0];
    session([
      'questions' => $questions,
      'questions_count' => $data['questions_count'],
      'answers' => [],
      'exam_id' => $exam->id
    ]);
    return view('exams.main', $data);
  }
  public function get_question(Request $req) {
    $idx = $req->query('idx', 0);
    if(!isset(session('questions', [])[$idx])) return abort(404);
    $question = session('questions')[$idx];
    return response()->json([
      'soal' => $question['soal'],
      'jawaban' => $question['jawaban'],
      'chosenAnswer' => session('answers.'.$idx)
    ]);
  }
  public function save_answer(Request $req) {
    $idx = $req->query('idx', 0);
    $choice = intval($req->query('choice', 0));
    if(!isset(session('questions', [])[$idx])) return abort(404);
    $question = session('questions')[$idx];
    if($choice < 0) return abort(400);
    if($choice > (count($question['jawaban']) - 1)) return abort(400);
    $answers = session('questions', []);
    $answers[$idx] = $choice;
    Session::put($answers);
    return response(['message' => 'Answer Saved.', 'answers' => $answers], 200);
  }
  public function calc_result() {
    if(Session::has('questions')) return abort(400);
    if(Session::has('answers')) return abort(400);
    if(Session::has('exam_id')) return abort(400);
    if(ExamResult::where('user_id', Auth::id())->where('exam_id', $exam->id)->exists()) return abort(403);
    $questions = session('questions');
    $answers = session('answers');
    $correct = 0;
    $wrong = 0;
    foreach($questions as $i => $soal) {
      if($answers[$i] == $soal['jwbn_yg_benar']) {
        $correct++;
        continue;
      }
      $wrong++;
    }
    ExamResult::create([
      'user_id' => Auth::id(),
      'exam_id' => session('exam_id'),
      'nilai' => round(($correct / session('questions_count')) * 100, 2),
      'total_salah' => $wrong,
      'total_benar' => $correct
    ]);
    Session::forget(['questions', 'answers', 'questions_count', 'exam_id']);
    return redirect('/exams/score/'.session('exam_id'))->with(['message' => 'Terima kasih sudah mengerjakan!']);
  }
}
