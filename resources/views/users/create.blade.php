@extends('layout.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-lg border-t-4 border-racing-orange">
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <h2 class="text-2xl font-bold uppercase text-gray-800"><i class="fa-solid fa-user-plus"></i> Tambah Karyawan / User</h2>
        <a href="{{ route('users.index') }}" class="text-gray-600 hover:text-racing-orange">Kembali</a>
    </div>

    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 gap-4 mb-6">
            <div>
                <label class="block font-bold mb-1">Nama Lengkap</label>
                <input type="text" name="nama" class="w-full border-2 border-gray-300 p-2 rounded focus:border-racing-orange outline-none" required placeholder="Contoh: Budi Santoso">
            </div>

            <div>
                <label class="block font-bold mb-1">Username Login</label>
                <input type="text" name="username" class="w-full border-2 border-gray-300 p-2 rounded focus:border-racing-orange outline-none" required placeholder="Contoh: budi_kasir">
            </div>

            <div>
                <label class="block font-bold mb-1">Password</label>
                <input type="password" name="password" class="w-full border-2 border-gray-300 p-2 rounded focus:border-racing-orange outline-none" required placeholder="Minimal 6 karakter">
            </div>

            <div>
                <label class="block font-bold mb-1">Jabatan / Hak Akses</label>
                <select name="role" class="w-full border-2 border-gray-300 p-2 rounded focus:border-racing-orange outline-none" required>
                    <option value="">-- Pilih Jabatan --</option>
                    <option value="kasir">Kasir (Akses Transaksi Saja)</option>
                    <option value="admin">Admin / Pemilik (Akses Penuh)</option>
                </select>
            </div>
        </div>

        <button type="submit" class="w-full bg-racing-orange hover:bg-orange-700 text-white font-bold py-3 rounded shadow-lg uppercase tracking-wider">
            SIMPAN USER
        </button>
    </form>
</div>
@endsection