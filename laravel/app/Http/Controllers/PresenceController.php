<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PresenceController extends Controller
{
  public function store_presence(Request $req) {
    // jika rekaman presensi sudah ada maka alihkan
    if(Presence::where('user_id', Auth::user()->id)->whereDay('created_at', today())->exists()) return abort(403);
    // presensi lewat token (qr/link)
    if($req->query('token')) {
      $id = Cache::get($req->query('token'));
      // pengecekan user id dan rekaman presensi
      if(is_null($id)) return abort(404);
      if($id != Auth::user()->id) return abort(403);
      // catat presensi
      Presence::create([
        'user_id' => $id,
        'status' => 'hadir'
      ]);
      return redirect('/dashboard')->with('success', 'Berhasil presensi');
    }
    // validasi dan catat izin/sakit
    $data = $req->validate([
      'status' => ['required', Rule::in(['sakit', 'izin'])],
      'alasan' => 'required|min:24',
      'doc_xtra' => 'required|file|max:4096|mimetypes:image/png,image/jpeg,image/webp,application/pdf'
    ]);
    $data['user_id'] = Auth::user()->id;
    $data['doc_xtra'] = $req->file('doc_xtra')->store('assets/presence_docs');
    Presence::create($data);
    return redirect('/dashboard')->with('success', 'Berhasil presensi');
  }
  public function generate_presence_qr(Request $req, int $id) {
    $token = $this->get_presence_token(strval($id));
    $qr = QrCode::generate(url('presence/?token='.$token));
    return response($qr)->header('Content-Type', 'image/svg+xml')->header('Cache-Control', 'no-cache, no-store');
  }
  public static function get_presence_token(string $id) {
    if(Cache::has('pt_'.$id)) return Cache::get('pt_'.$id)['token'];
    $token = Str::random(32);
    Cache::put('pt_'.$id, [
      'token' => $token
    ], now()->addMinutes(15));
    Cache::put($token, intval($id), now()->addMinutes(15));
    return $token;
  }
}
