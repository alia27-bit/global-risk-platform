@extends('layouts.app')

@section('title', 'Watchlist')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="mb-1">Favorite Monitoring List</h3>
            <p class="text-muted mb-0">Pantau negara prioritas dan akses indikator risikonya dengan cepat.</p>
        </div>
        <span class="badge rounded-pill text-bg-primary px-3 py-2">
            <i class="bi bi-binoculars me-1"></i> {{ $watchlists->count() }} negara dipantau
        </span>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="info-card h-100">
                <div class="card-header-navy">
                    <i class="bi bi-star-fill"></i> Negara yang Dipantau
                </div>
                <div class="card-body p-0">
                    @if ($watchlists->isEmpty())
                        <div class="text-center py-5 px-3">
                            <i class="bi bi-star text-muted d-block mb-3" style="font-size: 2.5rem"></i>
                            <h5>Watchlist masih kosong</h5>
                            <p class="text-muted mb-0">Pilih negara dari panel di samping untuk mulai memantau.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Negara</th>
                                        <th>Wilayah</th>
                                        <th>Risk Score</th>
                                        <th class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($watchlists as $watchlist)
                                        @php($country = $watchlist->country)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center gap-3">
                                                    @if ($country?->flag)
                                                        <img src="{{ $country->flag }}" alt="{{ $country->name }}" width="42" class="rounded shadow-sm">
                                                    @endif
                                                    <div>
                                                        <div class="fw-semibold">{{ $country?->name ?? 'Negara tidak tersedia' }}</div>
                                                        <small class="text-muted">{{ $country?->code }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $country?->region ?? '-' }}</td>
                                            <td>
                                                @if ($country?->riskScore)
                                                    @php($category = $country->riskScore->category)
                                                    <span class="badge text-bg-{{ $category === 'High' ? 'danger' : ($category === 'Medium' ? 'warning' : 'success') }}">
                                                        {{ number_format($country->riskScore->total_score, 2) }} · {{ $category }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">Belum dihitung</span>
                                                @endif
                                            </td>
                                            <td class="text-end pe-4 text-nowrap">
                                                @if ($country)
                                                    <a href="{{ route('countries.show', $country) }}" class="btn btn-sm btn-outline-primary" title="Lihat detail">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                @endif
                                                <form action="{{ route('watchlist.destroy', $watchlist) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus dari watchlist" onclick="return confirm('Hapus negara ini dari watchlist?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="info-card">
                <div class="card-header-navy">
                    <i class="bi bi-plus-circle"></i> Tambah Negara
                </div>
                <div class="card-body">
                    @if ($countries->isEmpty())
                        <div class="text-center py-3">
                            <i class="bi bi-check2-circle text-success fs-2"></i>
                            <p class="text-muted mt-2 mb-0">Semua negara yang tersedia sudah dipantau.</p>
                        </div>
                    @else
                        <input type="search" id="countrySearch" class="form-control mb-3" placeholder="Cari negara..." aria-label="Cari negara">
                        <div id="countryList" class="d-grid gap-2" style="max-height: 430px; overflow-y: auto">
                            @foreach ($countries as $country)
                                <form action="{{ route('watchlist.store', $country) }}" method="POST" class="country-option" data-name="{{ strtolower($country->name.' '.$country->code) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-light border w-100 d-flex align-items-center text-start gap-2">
                                        @if ($country->flag)
                                            <img src="{{ $country->flag }}" alt="" width="28" class="rounded">
                                        @endif
                                        <span class="flex-grow-1">
                                            <span class="d-block fw-semibold">{{ $country->name }}</span>
                                            <small class="text-muted">{{ $country->code }} · {{ $country->region ?: 'Wilayah tidak tersedia' }}</small>
                                        </span>
                                        <i class="bi bi-plus-lg text-primary"></i>
                                    </button>
                                </form>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('countrySearch')?.addEventListener('input', function () {
        const keyword = this.value.toLowerCase().trim();
        document.querySelectorAll('.country-option').forEach((item) => {
            item.classList.toggle('d-none', !item.dataset.name.includes(keyword));
        });
    });
</script>
@endpush
