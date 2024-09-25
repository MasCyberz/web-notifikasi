<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Ambil nilai "search" dan "entries" dari request
        $search = $request->input('search');
        $entries = $request->input('entries', 10); // Default 8 entries per page

        // Query dasar
        $users = User::query();

        // Jika ada pencarian
        if ($search) {
            $users->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%');
        }

        $users->orderBy('created_at', 'desc');

        // Ambil data dengan pagination sesuai jumlah entries
        $users = $users->paginate($entries)->appends($request->all());

        return view('admin.management-user.index', compact('users'));

        // $users = User::all();
        // return view('admin.management-user.index', ['users' => $users]);
    }

    public function createUser()
    {
        $roles = Role::all();
        return view('admin.management-user.add', compact('roles'));
    }

    public function storeUser(request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'role_id' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::Create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role_id' => $request->input('role_id'),
            'password' => bcrypt($request->input('password')),
        ]);
        return redirect()->route('management-user-index')->with('success', 'User berhasil ditambahkan');
    }

    public function editUser($id)
    {
        $user = User::find($id);
        return view('admin.management-user.edit', ['user' => $user]);
    }

    public function updateUser(request $request, $id)
    {
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

    public function deleteUser($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('management-user-index')->with('success', 'User berhasil dihapus');
    }

    public function checkEmail(Request $request)
    {
        $emailExists = User::where('email', $request->email)->exists();

        return response()->json(['exists' => $emailExists]);
    }
}
