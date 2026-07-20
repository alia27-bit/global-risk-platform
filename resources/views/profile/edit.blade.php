@extends('layouts.app')
@section('title', 'Profil')
@section('content')
<div class="container-fluid">
    <div class="mb-4"><h3 class="mb-1">Profil Akun</h3><p class="text-muted mb-0">Kelola informasi akun dan keamanan Anda.</p></div>
    @if(session('status'))<div class="alert alert-success">Perubahan berhasil disimpan.</div>@endif
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="info-card mb-4"><div class="card-header-navy"><i class="bi bi-person"></i>Informasi Profil</div><div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}">@csrf @method('PATCH')
                    <div class="mb-3"><label class="form-label" for="name">Nama Lengkap</label><input class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="mb-3"><label class="form-label" for="email">Email</label><input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>@error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <button class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan Profil</button>
                </form>
            </div></div>
            <div class="info-card"><div class="card-header-navy"><i class="bi bi-shield-lock"></i>Ubah Password</div><div class="card-body">
                <form method="POST" action="{{ route('password.update') }}">@csrf @method('PUT')
                    <div class="mb-3"><label class="form-label">Password Saat Ini</label><input type="password" name="current_password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" autocomplete="current-password">@error('current_password', 'updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="mb-3"><label class="form-label">Password Baru</label><input type="password" name="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" autocomplete="new-password">@error('password', 'updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
                    <div class="mb-3"><label class="form-label">Konfirmasi Password Baru</label><input type="password" name="password_confirmation" class="form-control" autocomplete="new-password"></div>
                    <button class="btn btn-primary"><i class="bi bi-key me-1"></i>Perbarui Password</button>
                </form>
            </div></div>
        </div>
        <div class="col-lg-5">
            <div class="info-card"><div class="card-header-navy"><i class="bi bi-person-badge"></i>Ringkasan Akun</div><div class="card-body text-center">
                <div class="rounded-circle bg-primary-subtle text-primary d-inline-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px"><i class="bi bi-person fs-1"></i></div>
                <h5>{{ $user->name }}</h5><p class="text-muted">{{ $user->email }}</p><span class="badge text-bg-primary text-uppercase">{{ $user->peran }}</span>
            </div></div>
            <div class="info-card mt-4 border-danger"><div class="card-body"><h6 class="text-danger"><i class="bi bi-exclamation-triangle me-1"></i>Hapus Akun</h6><p class="small text-muted">Tindakan ini permanen dan tidak dapat dibatalkan.</p>
                <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Yakin ingin menghapus akun secara permanen?')">@csrf @method('DELETE')
                    <input type="password" name="password" class="form-control form-control-sm mb-2 @error('password', 'userDeletion') is-invalid @enderror" placeholder="Masukkan password untuk konfirmasi">@error('password', 'userDeletion')<div class="invalid-feedback mb-2">{{ $message }}</div>@enderror
                    <button class="btn btn-outline-danger btn-sm">Hapus Akun</button>
                </form>
            </div></div>
        </div>
    </div>
</div>
@endsection
