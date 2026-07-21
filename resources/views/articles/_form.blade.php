@csrf
<div class="mb-3">
    <label class="form-label">Judul</label>
    <input name="title" value="{{ old('title', $article->title ?? '') }}" class="form-control @error('title') is-invalid @enderror" required>
    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label class="form-label">Konten</label>
    <textarea name="content" rows="12" class="form-control @error('content') is-invalid @enderror" required>{{ old('content', $article->content ?? '') }}</textarea>
    @error('content')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
            @foreach (['Draft', 'Published'] as $status)
                <option value="{{ $status }}" @selected(old('status', $article->status ?? 'Draft') === $status)>{{ $status }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Tanggal Publish</label>
        <input type="datetime-local" name="published_at" value="{{ old('published_at', isset($article) && $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : '') }}" class="form-control">
    </div>
</div>
<button class="btn btn-primary">Simpan</button>
<a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary">Batal</a>
