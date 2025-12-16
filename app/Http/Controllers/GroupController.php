<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\MemberGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class GroupController extends Controller
{
  public function index() {
    return view('groups.index');
  }

  public function get_groups(Request $req) {
    Gate::authorize('admin');
    $groups = Group::select('id', 'nama', 'deskripsi');
    if($req->has('q')) {
      $keyword = $req->input('q');
      if(!is_string($keyword)) {
        return response([], 400);
      }
      if(strlen($keyword) < 3) {
        return response([], 400);
      }
      $groups = $groups->where('nama', 'like', '%'.$keyword.'%')->orWhere('deskripsi', 'like', '%'.$keyword.'%');
    }
    $groups = $groups->limit(10)->get();
    return response()->json($groups);
  }

  public function store(Request $req) {
    Gate::authorize('admin');
    $rule = [];
    if($req->has('users_id') && !empty($req->input('users_id'))) {
      $rule = [
        'users_id' => 'required|array',
        'users_id.*' => 'required|numeric|integer|exists:users,id'
      ];
    }
    $data = $req->validate($rule + [
      'nama' => 'required|string|unique:groups,nama',
      'deskripsi' => 'required|string|min:3',
      'users_id' => 'nullable'
    ]);
    $group = Group::create([
      'nama' => $data['nama'],
      'deskripsi' => $data['deskripsi']
    ]);
    foreach($data['users_id'] as $id) {
      MemberGroup::create([
        'user_id' => intval($id),
        'group_id' => $group->id
      ]);
    }
    return response('Grup berhasil dibuat!');
  }

  public function edit(Request $req, Group $group) {

  }

  public function update(Request $req, Group $group) {

  }

  public function delete(Group $group) {

  }
}
