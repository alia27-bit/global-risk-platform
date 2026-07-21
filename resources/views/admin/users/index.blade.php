@extends('layouts.app')

@section('title', 'Pengguna')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3>Pengguna</h3>
        <p class="text-muted mb-0">Kelola akun admin dan user platform.</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="info-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Peran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.users.role', $user) }}" class="d-flex gap-2 align-items-center">
                                    @csrf @method('PATCH')
                                    <select name="peran" class="form-select form-select-sm" style="width: auto">
                                        <option value="admin" @selected($user->peran === 'admin')>Admin</option>
                                        <option value="user" @selected($user->peran === 'user')>User</option>
                                    </select>
                                    <button class="btn btn-sm btn-outline-primary">Simpan</button>
                                </form>
                            </td>
                            <td>
                                @if (! $user->is(auth()->user()))
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus pengguna ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $users->links() }}</div>
    </div>
</div>
@endsection
