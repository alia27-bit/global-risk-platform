@extends('layouts.app')

@section('title', __('messages.economic_indicator'))

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="mb-1">{{ __('messages.economic_indicator') }}</h3>
            <p class="text-muted mb-0">GDP, inflasi, pengangguran, ekspor, dan impor terbaru dari World Bank.</p>
        </div>
        <a href="{{ route('countries.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-globe2 me-1"></i>{{ __('messages.country') }}
        </a>
    </div>

    <div class="info-card">
        <div class="card-header-navy">
            <i class="bi bi-graph-up-arrow"></i>Data Ekonomi Negara
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Negara</th>
                            <th>GDP</th>
                            <th>Inflasi</th>
                            <th>Pengangguran</th>
                            <th>Ekspor</th>
                            <th>Impor</th>
                            <th>Diperbarui</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($indicators as $indicator)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $indicator->country?->name ?? '-' }}</td>
                                <td>{{ $indicator->gdp === null ? '-' : '$'.number_format($indicator->gdp, 0) }}</td>
                                <td>{{ $indicator->inflation === null ? '-' : number_format($indicator->inflation, 2).'%' }}</td>
                                <td>{{ $indicator->unemployment === null ? '-' : number_format($indicator->unemployment, 2).'%' }}</td>
                                <td>{{ $indicator->exports === null ? '-' : '$'.number_format($indicator->exports, 0) }}</td>
                                <td>{{ $indicator->imports === null ? '-' : '$'.number_format($indicator->imports, 0) }}</td>
                                <td>{{ $indicator->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    @if($indicator->country)
                                        <a href="{{ route('economy.show', $indicator->country) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i>{{ __('messages.detail') }}
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted py-5">Belum ada data indikator ekonomi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($indicators->hasPages())
            <div class="card-body border-top">{{ $indicators->links() }}</div>
        @endif
    </div>
</div>
@endsection
