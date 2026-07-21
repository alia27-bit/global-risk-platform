@extends('layouts.app')

@section('title', 'Kelola Artikel Analisis')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3>Artikel Analisis</h3>
            <p class="text-muted mb-0">Tambah, edit, hapus, dan publish artikel analisis.</p>
        </div>
        <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Artikel
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="info-card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Status</th>
                        <th>Publish</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($articles as $article)
                        <tr>
                            <td class="fw-semibold">{{ $article->title }}</td>
                            <td>{{ $article->user?->name ?? '-' }}</td>
                            <td>
                                <span class="badge text-bg-{{ $article->status === 'Published' ? 'success' : 'secondary' }}">
                                    {{ $article->status }}
                                </span>
                            </td>
                            <td>{{ $article->published_at?->format('d M Y H:i') ?? '-' }}</td>
                            <td class="text-nowrap">
                                <a href="{{ route('admin.articles.show', $article) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                                <form class="d-inline" method="POST" action="{{ route('admin.articles.destroy', $article) }}" onsubmit="return confirm('Hapus artikel ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada artikel.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $articles->links() }}</div>
    </div>
</div>
@endsection
