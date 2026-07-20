@extends('layouts.app')

@section('title', __('messages.exchange_rate'))

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="mb-1">{{ __('messages.exchange_rate') }}</h3>
            <p class="text-muted mb-0">Kurs mata uang terbaru setiap negara terhadap USD.</p>
        </div>
        <a href="{{ route('countries.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-globe2 me-1"></i>{{ __('messages.country') }}
        </a>
    </div>

    <div class="info-card">
        <div class="card-header-navy">
            <i class="bi bi-currency-exchange"></i>Daftar Nilai Tukar
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Negara</th>
                            <th>Mata Uang</th>
                            <th>Nilai Tukar</th>
                            <th>Diperbarui</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exchangeRates as $rate)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $rate->country?->name ?? '-' }}</td>
                                <td>{{ $rate->base_currency }} / {{ $rate->target_currency }}</td>
                                <td>1 {{ $rate->base_currency }} = <strong>{{ number_format($rate->exchange_rate, 4) }}</strong> {{ $rate->target_currency }}</td>
                                <td>{{ $rate->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    @if($rate->country)
                                        <a href="{{ route('exchange.show', $rate->country) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i>{{ __('messages.detail') }}
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">Belum ada data nilai tukar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($exchangeRates->hasPages())
            <div class="card-body border-top">{{ $exchangeRates->links() }}</div>
        @endif
    </div>
</div>
@endsection
