@extends('layouts.app')

@section('content')
<div class="flex items-center justify-between gap-3 text-black mb-6 mt-2">
    <div class="flex items-center gap-3">
        <i class="fas fa-user-cog text-2xl"></i>
        <h1 class="text-2xl font-semibold">Manajemen Akun</h1>
    </div>
    <a href="{{ route('user.create') }}" class="bg-brandMaroon text-white px-4 py-2 rounded-lg hover:bg-red-800 flex items-center gap-2">
        <i class="fas fa-plus"></i> Tambah User
    </a>
</div>

@if ($message = Session::get('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
        {{ $message }}
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
        {{ $message }}
    </div>
@endif

<div class="bg-white rounded-lg shadow-sm border border-gray-200">
    <div class="px-5 py-4 border-b border-gray-200 bg-gray-50">
        <span class="text-sm text-gray-700 font-medium">Total User: {{ $users->count() }}</span>
    </div>

    <div class="p-5">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead class="text-gray-700 bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">No</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Nama</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Email</th>
                        <th class="py-3 px-4 border-r border-gray-200 font-medium">Role</th>
                        <th class="py-3 px-4 font-medium text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $key => $user)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-4 px-4 border-r border-gray-200">{{ $key + 1 }}</td>
                        <td class="py-4 px-4 border-r border-gray-200">{{ $user->name }}</td>
                        <td class="py-4 px-4 border-r border-gray-200">{{ $user->email }}</td>
                        <td class="py-4 px-4 border-r border-gray-200">
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $user->role == 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <a href="{{ route('user.edit', $user->id) }}" class="text-blue-600 hover:text-blue-800 mr-3">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if (auth()->id() != $user->id)
                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Yakin ingin menghapus?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 px-4 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2"></i>
                            <p>Data user tidak ada</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
