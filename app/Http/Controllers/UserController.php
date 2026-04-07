<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //Tampilan halaman daftar user
    public function index() {
        $users = Users::all();
        return view('users.index', compact('users'));
    }


    //Tampilan halaman tambah user
    public function create() {
        return view('users.create');
    }

    // Menyimpan data user baru ke database
    public function store(Request $request) {
        //Validasi input
        $request->validate([
            'nama' => 'required',
            'username' => 'required|unique:user,username',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        //Simpan ke database
        Users::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password), 
            'role' => $request->role,
        ]);
        return redirect()->route('users.index')->with('User/Kasir baru berhasil ditambahkan!');
    }

    //Menampilkan halaman edit user
    public function edit($id) {
        $user = Users::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    //Memperbarui data user
    public function update(Request $request, $id) {
        //Validasi input
        $request->validate([
            'nama' => 'required',
            'username' => 'required|unique:user,username,'.$id,
            'password' => 'nullable|min:6',
            'role' => 'required'
        ]);

        //Simpan ke database
        $user = Users::findOrFail($id);
        $user->update([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'role' => $request->role,
        ]);
        return redirect()->route('users.index')->with('User/Kasir berhasil diperbarui!');
    }

    //Menghapus data user
    public function destroy($id) {
        $user = Users::findOrFail($id);
        if ($user->role == 'admin') {
            return back()->with('error', 'Pemilik tidak dapat dihapus!');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }
}