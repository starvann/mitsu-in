<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\MemberGroup;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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

  public function get_users_of_group(Request $req, Group $group) {
    Gate::authorize('admin');
    $users = MemberGroup::where('group_id', $group->id)->with('user')->get();
    if($req->has('q')) {
      $keyword = $req->input('q');
      if(!is_string($keyword)) {
        return response([], 400);
      }
      if(strlen($keyword) < 3) {
        return response([], 400);
      }
      $users = $users->where('nama', 'like', '%'.$keyword.'%')->orWhere('deskripsi', 'like', '%'.$keyword.'%')->get();
    }
    $users = $users->map(function($item, $key) {
      return [
        'id' => $item->user->id,
        'nama' => $item->user->nama,
        'email' => $item->user->email,
        'gmb_profil' => $item->user->gmb_profil,
      ];
    });
    return response()->json($users);
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
    $group->users()->syncWithoutDetaching($data['users_id']);
    return response('Grup berhasil dibuat!');
  }

  public function view(Group $group) {
    Gate::authorize('admin');
    return view('groups.view', ['group' => $group]);
  }

  public function edit(Group $group) {
    Gate::authorize('admin');
    return view('groups.edit', ['group' => $group]);
  }

  public function update(Request $req, Group $group) {
    Gate::authorize('admin');
    $data = [];
    if($req->has('users_id')) {
      $data = $req->validate([
        'users_id' => 'required|array',
        'users_id.*' => 'required|numeric|integer|exists:users,id'
      ]);
      $group->users()->sync($data['users_id']);
      return response('Berhasil update!');
    } else {
      $data = $req->validate([
        'nama' => ['required', 'string', Rule::unique('exams', 'judul')->ignore($group->id)],
        'deskripsi' => 'required|string|min:3',
      ]);
      $group->update($data);
    }
    return redirect("dashboard/view-group/$group->id")->with('success', 'Grup telah diupdate!');
  }

  public function delete(Group $group) {
    Gate::authorize('admin');
    $group->delete();
    return redirect("dashboard/groups")->with('success', 'Grup telah dihapus.');
  }
}
