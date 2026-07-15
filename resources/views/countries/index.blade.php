@extends('layouts.app')
@section('title', __('messages.country'))
@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="mb-0">{{ __('messages.country') }}</h3>
            <small class="text-muted">
                {{ __('messages.manage_countries_desc') }}
            </small>
        </div>

        <div class="d-flex gap-2">

            <a href="{{ route('countries.sync') }}"
                class="btn btn-success">

                <i class="bi bi-arrow-repeat"></i>

                Sinkronisasi API

            </a>

            <a href="{{ route('countries.index') }}"
                class="btn btn-primary">

                <i class="bi bi-arrow-clockwise"></i>

                {{ __('messages.refresh') }}

            </a>

        </div>

    </div>

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button class="btn-close"
                data-bs-dismiss="alert"></button>

        </div>

    @endif

    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button class="btn-close"
                data-bs-dismiss="alert"></button>

        </div>

    @endif

    <div class="card shadow">

        <div class="card-header bg-primary text-white">

            Daftar Negara

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th width="60">No</th>

                            <th>Bendera</th>

                            <th>Kode</th>

                            <th>Nama Negara</th>

                            <th>Ibu Kota</th>

                            <th>Wilayah</th>

                            <th>Mata Uang</th>

                            <th>Populasi</th>

                            <th width="200">Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($countries as $item)

                            <tr>

                                <td>{{ $loop->iteration + ($countries->currentPage() - 1) * $countries->perPage() }}</td>

                                <td>

                                    @if($item->flag)

                                        <img src="{{ $item->flag }}"
                                            width="50" alt="{{ $item->name }}">

                                    @else

                                        -

                                    @endif

                                </td>

                                <td>

                                    {{ $item->code }}

                                </td>

                                <td>

                                    {{ $item->name }}

                                </td>

                                <td>

                                    {{ $item->capital }}

                                </td>

                                <td>

                                    {{ $item->region }}

                                </td>

                                <td>

                                    {{ $item->currency }}

                                </td>

                                <td>

                                    {{ number_format($item->population, 0, ',', '.') }}

                                </td>

                                <td>

                                    <a href="{{ route('countries.show', $item) }}"
                                        class="btn btn-info btn-sm">
                                        <i class="bi bi-eye"></i> {{ __('messages.detail') }}
                                    </a>

                                    <a href="{{ route('weather.sync', $item->id) }}"
                                        class="btn btn-warning btn-sm"
                                        title="Sync Cuaca">
                                        <i class="bi bi-cloud-sun"></i>
                                    </a>

                                    <a href="{{ route('risk.calculate', $item) }}"
                                        class="btn btn-danger btn-sm"
                                        title="Hitung Risk">
                                        <i class="bi bi-shield-exclamation"></i>
                                    </a>

                                    @if(auth()->user()->peran === 'admin')
                                        <form action="{{ route('countries.destroy', $item) }}"
                                            method="POST"
                                            class="d-inline">

                                            @csrf

                                            @method('DELETE')

                                            <button
                                                onclick="return confirm('Hapus negara ini?')"
                                                class="btn btn-outline-danger btn-sm">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </form>
                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9" class="text-center py-4">

                                    {{ __('messages.no_country') }}

                                    <br><br>

                                    Klik tombol

                                    <strong>{{ __('messages.sync_api') }}</strong>

                                    untuk mengambil data dari REST Countries.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $countries->links() }}
            </div>

        </div>

    </div>

</div>

@endsection