@extends('layout.app')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-racing-orange">
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <h2 class="text-2xl font-bold uppercase text-gray-800"><i class="fa-solid fa-users"></i> Kelola User / Karyawan</h2>
        <a href="{{ route('users.create') }}" class="bg-racing-orange hover:bg-orange-700 text-white px-4 py-2 rounded font-bold shadow transition">
            <i class="fa-solid fa-plus"></i> Tambah User Baru
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-800 text-white uppercase text-sm">
                    <th class="p-3 border">No</th>
                    <th class="p-3 border">Nama Lengkap</th>
                    <th class="p-3 border">Username</th>
                    <th class="p-3 border">Hak Akses / Role</th>
                    <th class="p-3 border text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $index => $u)
                <tr class="hover:bg-gray-100 border-b">
                    <td class="p-3 border">{{ $index + 1 }}</td>
                    <td class="p-3 border font-semibold">{{ $u->nama }}</td>
                    <td class="p-3 border">{{ $u->username }}</td>
                    <td class="p-3 border">
                        @if($u->role == 'admin' || $u->role == 'pemilik')
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-bold uppercase">Admin</span>
                        @else
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold uppercase">Kasir</span>
                        @endif
                    </td>
                    <td class="p-3 border text-center space-x-2">
                        <form action="{{ route('users.destroy', $u->user_id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus {{ $u->nama }} ({{ $u->role }})?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700" title="Hapus User">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection