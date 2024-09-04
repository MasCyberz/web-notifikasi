<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        $users = User::all();
        return view('admin.management-user.index', ['users' => $users]);
    }

    public function createUser(){
        return view('admin.management-user.add');
    }

    Public function storeUser(request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = User::Create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);
        return redirect()->route('management-user-index')->with('success', 'User berhasil ditambahkan');
    }

    Public Function editUser($id){
        $user = User::find($id);
        return view('admin.management-user.edit', ['user' => $user]);
    }

    public function updateUser(request $request, $id){
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'nullable',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        // Update the password if provided
        if ($request->filled('password')) {
            $user->password = bcrypt($request['password']);
        }

        $user->save();
        return redirect()->route('management-user-index')->with('success', 'User berhasil di-update');
    }

    public function deleteUser($id){
        $user = User::find($id);
        $user->delete();
        return redirect()->route('management-user-index')->with('success', 'User berhasil dihapus');
    }
}
